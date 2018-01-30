<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnalyzeVacancyController extends Controller
{
    /**
 * @Route("/vacancies", name="analyze_vacancy")
 * @param Request $request
 * @return mixed
 */
    public function indexAction(Request $request)
    {
        $apiController = new ApiController();
//        $regions = $apiController->getRegionsAction();
        // replace this example code with whatever you need
        return $this->render('default/analyze_vacancy_index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }
}
