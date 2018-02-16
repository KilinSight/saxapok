<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends Controller
{
    /**
 * @Route("/settings", name="project_settings")
 * @param Request $request
 * @return mixed
 */
    public function indexAction(Request $request)
    {

        return $this->render('default/settings.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }
}
