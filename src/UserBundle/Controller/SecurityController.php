<?php
namespace UserBundle\Controller;

use AppBundle\Manager\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use \Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\Role;
use UserBundle\Entity\User;
use UserBundle\Entity\UserCustomization;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     * @return Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $lastUsername = $authenticationUtils->getLastUsername();

        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }else{
            if($lastUsername){
                $em = $this->getDoctrine()->getManager();
                /** @var User $userEntity */
                $userEntity = $em->getRepository(User::class)->loadUserByUsername($lastUsername);
                if($userEntity && !$userEntity->isEnabled()){
                    return $this->redirectToRoute('verify_email', ['_email' => $userEntity->getEmail(), 'info' => 'To your email was sent a verify code url. Please confirm your account before login.']);
                }
            }
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $info = $request->get('info', '');

        // last username entered by the user

        return $this->render('@User/security/login.html.twig', [
            'last_username' => $lastUsername,
            'info'         => $info,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/new-password", name="new_password")
     * @param Request $request
     * @return Response
     */
    public function newPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->get('email', '');
        $error = '';
        $info = '';
        $userEntity = null;

        if(!$email){
            throw new BadRequestHttpException('User not found');
        }else{
            $userEntity = $em->getRepository(User::class)->loadUserByUsername($email);
        }
        if($userEntity){
            /** @var User $userEntity */
            if($userEntity->isEnabled() && $userEntity->getUserCustomization()->getEmailVerifyCode() && $request->getMethod() === Request::METHOD_POST){
                $password = $request->get('_password', null);
                $repeatPassword = $request->get('_password_repeat', null);

                if(!$repeatPassword || !$password){
                    $error = 'Fields "Password" and "Repeated password" are required.';
                }

                if($repeatPassword !== $password){
                    $error = 'Fields "Password" and "Repeat password" must be equal.';
                }

                if(!$error){
                    $encoder = $this->get('security.password_encoder');
                    $userEntity->setPassword($encoder->encodePassword($userEntity, $password));
                    $userEntity->setStatus(User::USER_STATUS_ACTIVE);
                    $userCustomization = $userEntity->getUserCustomization();
                    $userCustomization->setEmailVerifyCode(null);
                    $em->persist($userEntity);
                    $em->persist($userCustomization);
                    $em->flush();
                    sleep(3);

                    return $this->redirectToRoute('login', ['info' => 'Password was successfully changed. Now you can sign in']);
                }
            }elseif($request->getMethod() === Request::METHOD_POST){
                return $this->redirectToRoute('recover_password');
            }
        }else{
            return $this->redirectToRoute('recover_password');
        }

        return $this->render('@User/security/new_password.html.twig', [
            'email' => $email,
            'error' => $error,
            'info' => $info
        ]);
    }

    /**
     * @Route("/recover-password", name="recover_password")
     * @param string|null $token
     * @param Request $request
     * @return Response
     */
    public function recoverPasswordAction(Request $request)
    {
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();

        $mailerService = $this->get(MailerService::class);
        $error = '';
        $email = $request->get('_email', '');
        $token = $request->get('token', '');
        $info = $request->get('info', '');

        if($token){
            /** @var User $user */
            $user = $em->getRepository(User::class)->loadUserByVerifyToken($token);

            if($user && $user->isEnabled()){
                return $this->redirectToRoute('new_password', ['email' => $user->getEmail()]);
            }
        }elseif($request->getMethod() === Request::METHOD_POST){
            $userEntity = null;
            if(!$email){
                $error = '"Email" field is required';
            }else{
                /** @var User $userEntity */
                $userEntity = $em->getRepository(User::class)->loadUserByUsername($email);
            }
            if(!$userEntity){
                $error = 'User ' . $email . ' not found';
            }
            if(!$error){
                $verifyCode = MailerService::generateVerifyCode();

                $userCustomization = $userEntity->getUserCustomization();
                $userCustomization->setEmailVerifyCode($verifyCode);
                $em->persist($userCustomization);
                $em->flush();
                $verifyUrl = $this->generateUrl('recover_password', ['token' => $verifyCode], UrlGeneratorInterface::ABSOLUTE_URL);

                $mailerService->sendMessageTo($email, 'Hello! This is verify URL to approve your email. If you don\'t know about us, please ignore this email. <br>' . $verifyUrl);
                return $this->redirectToRoute('login', ['info' => 'To your email was sent a recover password url. Check your email']);
            }
        }

        return $this->render('@User/security/recover_password.html.twig', [
            'email' => $email,
            'error' => $error,
            'info' => $info,
        ]);
    }

    /**
     * @Route("/verify-email", name="verify_email")
     * @param string|null $token
     * @param Request $request
     * @return Response
     */
    public function verifyEmailAction(Request $request)
    {
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();

        $mailerService = $this->get(MailerService::class);
        $error = '';
        $email = $request->get('_email', '');
        $token = $request->get('token', '');
        $info = $request->get('info', '');

        if($request->getMethod() === Request::METHOD_POST){
            if(!$email){
                $error = '"Email" field is required';
            }else{
                /** @var User $userEntity */
                $userEntity = $em->getRepository(User::class)->loadUserByUsername($email);
            }
            if(!$userEntity){
                $error = 'User ' . $email . ' not found';
            }

            if(!$error){
                $verifyCode = MailerService::generateVerifyCode();
                /** @var User $userEntity */
                $userEntity = $em->getRepository(User::class)->loadUserByUsername($email);
                $userCustomization = $userEntity->getUserCustomization();
                $userCustomization->setEmailVerifyCode($verifyCode);
                $em->persist($userCustomization);
                $em->flush();
                $verifyUrl = $this->generateUrl('verify_email', ['token' => $verifyCode], UrlGeneratorInterface::ABSOLUTE_URL);

                $mailerService->sendMessageTo($email, 'Hello! This is verify URL to approve your email. If you don\'t know about us, please ignore this email. <br>' . $verifyUrl);
                return $this->redirectToRoute('login', ['info' => 'To your email was sent a verify code url. Please confirm your account before login.']);
            }
        }else{
            if($token){
                /** @var User $user */
                $user = $em->getRepository(User::class)->loadUserByVerifyToken($token);

                if($user){
                    $user->setStatus(User::USER_STATUS_ACTIVE);
                    $userCustomization = $user->getUserCustomization();
                    $userCustomization->setEmailVerifyCode(null);
                    $em->persist($user);
                    $em->persist($userCustomization);
                    $em->flush();
                    sleep(3);

                   return $this->redirectToRoute('login', ['info' => 'Account was successfully verified. Now you can sign in']);
                }
            }
        }

        return $this->render('@User/security/verify_email.html.twig', [
            'email' => $email,
            'error' => $error,
            'info' => $info,
        ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/logout", name="logout")
     * @param Request $request
     * @return void
     */
    public function logoutAction(Request $request)
    {

    }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        $mailerService = $this->get(MailerService::class);

        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }
        $error = '';
        $username = $request->get('_username', null);
        $email = $request->get('_email', null);
        $password = $request->get('_password', null);
        $passwordRepeat = $request->get('_password_repeat', null);
        $firstname = $request->get('_firstname', null);
        $lastname = $request->get('_lastname', null);
        $birthday = $request->get('_birthday', null);

        if($request->getMethod() === Request::METHOD_POST){

            if($password !== $passwordRepeat){
                $error = "Entered passwords are not equal. Repeat input please.";
            }

            if(!$username){
                $error .= 'Field "Username" is required.<br>';
            }
            if(!$email){
                $error .= 'Field "Email" is required.<br>';
            }
            if(!$password){
                $error .= 'Field "Password" is required.<br>';
            }

            if(!$passwordRepeat){
                $error .= ' Please fill out field "Repeat password".<br>';
            }

            if(!$error){
                /** @var EntityManagerInterface $em */
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository(User::class)->loadUserByUsername($username);
                if(!$user){
                    $user = $em->getRepository(User::class)->loadUserByUsername($email);
                    if(!$user){
                        $encoder = $this->get('security.password_encoder');
                        $user = new User();
                        $user->setUsername($username);
                        $user->setEmail($email);
                        $user->setPassword($encoder->encodePassword($user, $password));
                        $user->setStatus(User::USER_STATUS_DISABLED);
                        $em->persist($user);

                        $verifyCode = MailerService::generateVerifyCode();

                        $userCustomization = new UserCustomization();
                        $userCustomization->setUser($user);
                        $userCustomization->setFirstname($firstname);
                        $userCustomization->setLastname($lastname);
                        $userCustomization->setEmailVerifyCode($verifyCode);
                        $user->setUserCustomization($userCustomization);

                        if($birthday){
                            $userCustomization->setBirthday(new \DateTime($birthday));
                        }

                        $userRole = $em->getRepository(Role::class)->findOneBy(['role' => Role::ROLE_USER]);

                        $user->addRole($userRole);

                        $em->persist($userCustomization);
                        $em->flush();


                        $verifyUrl = $this->generateUrl('verify_email', ['token' => $verifyCode], UrlGeneratorInterface::ABSOLUTE_URL);

                        $mailerService->sendMessageTo($email, 'Hello! This is verify URL to approve your email. If you don\'t know about us, please ignore this email. <br>' . $verifyUrl);
                        return $this->redirectToRoute('login', ['info' => 'To your email was sent a verify code url. Please confirm your account before login.']);
                    }else{
                        $error = 'Email already in use. Please change your email.';
                        $email = '';
                    }
                }else{
                    $error = 'Username already in use. Please change your username.';
                    $username = '';
                }
            }
        }


        return $this->render('@User/security/register.html.twig', [
            'error' => $error,
            'username' => $username,
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'birthday' => $birthday,
        ]);
    }
}