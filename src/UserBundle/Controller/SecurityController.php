<?php
namespace UserBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use UserBundle\Entity\User;
use UserBundle\Entity\UserCustomization;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {

        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
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
                        $user->setStatus(User::USER_STATUS_ACTIVE);
                        $em->persist($user);
                        $em->flush();

                        $userCustomization = new UserCustomization();
                        $userCustomization->setUser($user);
                        $userCustomization->setFirstname($firstname);
                        $userCustomization->setLastname($lastname);
                        if($birthday){
                            $userCustomization->setBirthday(new \DateTime($birthday));
                        }

                        $em->persist($userCustomization);
                        $em->flush();

                        $this->redirectToRoute('login');
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