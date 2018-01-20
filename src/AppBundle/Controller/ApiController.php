<?php

namespace AppBundle\Controller;

use DoctrineTest\InstantiatorTestAsset\XMLReaderAsset;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\XMLparserService;

class ApiController extends Controller
{

    var $reader;
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
        $content = $this->ParseXML($url,'vacancy',$limit,$offset);

        return new JsonResponse(json_encode($content));
    }

    private function ParseXML($url,$name,$limit = 1000, $offset = 0){
        $progress = [
          'done' => false,
          'offset' => $offset + $limit,
        ];
        $xmlReader = new \XMLReader();
        $xmlReader->open($url);
        $found = false;
        $i=0;
        while ($xmlReader->read() && $i++<$limit+$offset) {
            if ($i === 0){
                $startName = $xmlReader->name;
            }elseif($i < $offset && $i>1){
                $xmlReader->next($name);
            }
            if ($startName === $xmlReader->name && $xmlReader->nodeType === \XMLReader::END_ELEMENT){
                $progress['done'] = true;
            }
            while ($xmlReader->read() && $xmlReader->name !== $name) {
                $nextName = $xmlReader->name;
                $found = false;
                if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                    while ($xmlReader->read() && $xmlReader->name !== $nextName) {
                        if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                            if ($xmlReader->hasValue) {
                                $result[$i]['' . $nextName] = $xmlReader->value;
                                $found = true;
                            } else {
                                $result[$i]['' . $nextName] = '';
                            }
                        } else {
                            if (!$found) {
                                $result[$i]['' . $nextName] = '';
                            }
                        }
                    }
                    if ($nextName === $xmlReader->name && !$found) {
                        $result[$i]['' . $nextName] = '';
                    }
                }else{
                    $result[$i]['' . $nextName] = '';
                }
            }
        }
//        var_dump($result);
//        $progress['result'] = $result;
        return $progress;
    }
}
