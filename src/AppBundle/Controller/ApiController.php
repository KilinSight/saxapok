<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{

    const apikey = 'txQQ643brE1U';
    const botapikey = '914200924:AAHcU9V8CCXXHDsealePRW5Yw4ck-Om3Xzg';
    const saxapokWebhookUrl = 'http://902da367.ngrok.io/';
    const PARSEHUB_RUN_TOKEN = 'tmbJOi3f8c-T';

    /**
    * @Route("/api", name="api_index")
     * @param Request $request
     * @return mixed
    */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/api/get_regions", name="api_regions", options = {"expose" : true})
     * @return mixed
     */
    public function getRegionsAction()
    {
        $url = 'http://opendata.trudvsem.ru/7710538364-regions/data-20180109T024910-structure-20161130T143000.xml';
//        $url = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';

        $content = simplexml_load_file($url);
        return new JsonResponse(json_encode($content));
    }

    /**
     * @Route("/api/get_vacancies", name="api_vacancies", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function getVacanciesAction(Request $request)
    {
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';
        /** @var \XMLReader $xmlReader */
        $xmlReader = new \XMLReader();
        $xmlReader->open($url);

        $i = $offset;
        while ($i < $limit){
            if ($xmlReader->nodeType == \XMLReader::ELEMENT)
            $xmlReader->read();
            $content [] = $xmlReader->value;
            var_dump($xmlReader->value);
            $xmlReader->next();
            $i++;
        }
//        $content = simplexml_load_file($url);
        return new JsonResponse(json_encode($content));
    }

}
