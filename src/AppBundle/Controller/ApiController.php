<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use AppBundle\Repository\VacancyRepository;
use DoctrineTest\InstantiatorTestAsset\XMLReaderAsset;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->getDoctrine()->getManager();
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';

        /** @var \XMLReader $xmlReader */
        $content = $this->ParseXML($url,'vacancy',$limit,$offset);
        foreach ($content['result'] as $result){
            $vac = $em->getRepository('AppBundle\Entity\Vacancy')->findBy(['identifier' => $result['identifier']]);
            if (!$vac){
                $vac = new Vacancy();
                $vac->setIdentifier($result['id']?$result['id']:' ');
                $vac->setRegion($result['region']?$result['region']:' ');
                $vac->setOrganization($result['organization']?$result['organization']:' ');
                $vac->setIndustry($result['industry']?$result['industry']:' ');
                $vac->setProfession($result['profession']?$result['profession']:' ');
                $vac->setHiringOrganization($result['hiringOrganization']?$result['hiringOrganization']:' ');
                $vac->setCreationDate($result['creationDate']?$result['creationDate']:' ');
                $vac->setDatePosted($result['datePosted']?$result['datePosted']:' ');
                $vac->setIdentifier($result['identifier']?$result['identifier']:' ');
                $vac->setBaseSalary($result['baseSalary']?$result['baseSalary']:' ');
                $vac->setTitle($result['title']?$result['title']:' ');
                $vac->setEmploymentType($result['employmentType']?$result['employmentType']:' ');
                $vac->setWorkHours($result['workHours']?$result['workHours']:' ');
                $vac->setResponsibilities($result['responsibilities']?$result['responsibilities']:' ');
                $vac->setIncentiveCompensation($result['incentiveCompensation']?$result['incentiveCompensation']:' ');
                $vac->setRequirements($result['requirements']?$result['requirements']:' ');
                $vac->setSocialProtecteds($result['socialProtecteds']?$result['socialProtecteds']:' ');
                $vac->setMetroStations($result['metroStations']?$result['metroStations']:' ');
                $vac->setSource($result['source']?$result['source']:' ');
                $vac->setWorkPlaces($result['workPlaces']?$result['workPlaces']:' ');
                $vac->setAdditionalInfo($result['additionalInfo']?$result['additionalInfo']:' ');
                $vac->setDeleted($result['innerInfo']?$result['innerInfo']:' ');
                $vac->setVacUrl($result['vac_url']?$result['vac_url']:' ');
                $em->persist($vac);
            }
        }
        $em->flush();

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
                                $result[$i]['' . $nextName] = ' ';
                            }
                        } else {
                            if (!$found) {
                                $result[$i]['' . $nextName] = ' ';
                            }
                        }
                    }
                    if ($nextName === $xmlReader->name && !$found) {
                        $result[$i]['' . $nextName] = ' ';
                    }
                }else{
                    $result[$i]['' . $nextName] = '';
                }
            }
        }
//        var_dump($result);
        $progress['result'] = $result;
        return $progress;
    }
}
