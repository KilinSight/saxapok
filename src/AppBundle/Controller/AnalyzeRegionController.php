<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AnalyzeRegionController extends Controller
{
    /**
 * @Route("/regions", name="analyze_regions")
 * @param Request $request
 * @return mixed
 */
    public function indexAction(Request $request)
    {
        return $this->render('default/analyze_region_index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR
        ]);
    }

}
