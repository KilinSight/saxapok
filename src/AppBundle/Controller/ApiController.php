<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vacancy;
use AppBundle\Repository\VacancyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
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
        return new JsonResponse($content);
    }

    /**
     * @Route("/api/get_raw_xml", name="get_raw_xml", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function readRawXMLAction(Request $request)
    {
        $result=[];
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = $request->get('url');
        $xmlReader = new \XMLReader();
        $xmlReader->open($url,true,LIBXML_PARSEHUGE);
        $i=0;
        while ($xmlReader->read() && $i++<$limit+$offset) {
            $result[$xmlReader->name] = $xmlReader->value;
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/api/get_vacancies", name="api_vacancies", options = {"expose" : true})
     * @param Request $request
     * @return mixed
     */
    public function getVacanciesAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $url = $request->get('url');
        $name = $request->get('name');
//        $urlsArray = [
//            'vacancies' =>'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml',
//            'resumes' => 'http://opendata.trudvsem.ru/7710538364-cv/data-20180206T053855-structure-20161130T143000.xml',
//            'organizations' => 'http://opendata.trudvsem.ru/7710538364-organizations/data-20180206T053855-structure-20161130T143000.xml',
//            'regions' => 'http://opendata.trudvsem.ru/7710538364-regions/data-20180206T053855-structure-20161130T143000.xml',
//            'professions' => 'http://opendata.trudvsem.ru/7710538364-professions/data-20180206T053855-structure-20161130T143000.xml',
//            'spheres' => 'http://opendata.trudvsem.ru/7710538364-industries/data-20180206T053855-structure-20161130T143000.xml',
//            'stats_citizens' => 'http://opendata.trudvsem.ru/7710538364-stat-citizens/data-20180205T050000-structure-20170101T000000.xml',
//            'stats_company' => 'http://opendata.trudvsem.ru/7710538364-stat-company/data-20180205T050000-structure-20170101T000000.xml'
//        ];
//        $vacancyUrl = 'http://opendata.trudvsem.ru/7710538364-vacancy/data-20180113T031742-structure-20161130T143000.xml';
//        $resumesUrl = 'http://opendata.trudvsem.ru/7710538364-cv/data-20180206T053855-structure-20161130T143000.xml';
//        $organizationsUrl = 'http://opendata.trudvsem.ru/7710538364-organizations/data-20180206T053855-structure-20161130T143000.xml';
//        $regionsUrl = 'http://opendata.trudvsem.ru/7710538364-regions/data-20180206T053855-structure-20161130T143000.xml';
//        $professionsUrl = 'http://opendata.trudvsem.ru/7710538364-professions/data-20180206T053855-structure-20161130T143000.xml';
//        $spheresUrl = 'http://opendata.trudvsem.ru/7710538364-industries/data-20180206T053855-structure-20161130T143000.xml';
//        $statsCitizensUrl = 'http://opendata.trudvsem.ru/7710538364-stat-citizens/data-20180205T050000-structure-20170101T000000.xml';
//        $statsCompaniesUrl = 'http://opendata.trudvsem.ru/7710538364-stat-company/data-20180205T050000-structure-20170101T000000.xml';

        /** @var \XMLReader $xmlReader */

        var_dump($url);
        $content = $this->ParseXML($url,$name,$limit,$offset);
        $result[]=$content;
        return new JsonResponse($content);
    }

    private function ParseXML($url,$name,$limit = 1000, $offset = 0){
        $progress = [
          'done' => false,
          'offset' => $offset + $limit,
        ];
        $em = $this->getDoctrine()->getManager();
        $xmlReader = new \XMLReader();
        $xmlReader->open($url,true,LIBXML_PARSEHUGE);
        $found = false;
        $foundAttribute = [];
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
                $foundAttribute['success'] = false;
                $foundAttribute['result'] = '';
                if ($xmlReader->getAttribute('resource') !== null) {
                    $foundAttribute['result'] = $this->parseNode($xmlReader->getAttribute('resource'));
                    $foundAttribute['success'] = true;
//                    print_r("\n");
//                    print_r($xmlReader->name.' : '.$xmlReader->getAttribute('resource'));
//                    print_r($xmlReader->name.' : '.$this->parseNode($xmlReader->getAttribute('resource')));
                }
                if ($xmlReader->getAttribute('about') !== null) {
                    $foundAttribute['result'] = $this->parseNode($xmlReader->getAttribute('about'));
                    $foundAttribute['success'] = true;
                }

                if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                    while ($xmlReader->read() && $xmlReader->name !== $nextName) {
                        if ($xmlReader->nodeType !== \XMLReader::END_ELEMENT) {
                            if ($xmlReader->hasValue) {
                                $result['' . $nextName] = $xmlReader->value?$xmlReader->value:' ';
                                $found = true;
                            } elseif($foundAttribute['success']) {
                                $result['' . $nextName] = $foundAttribute['result'];
                            }else{
                                $result['' . $nextName] = ' ';
                            }
                        } else {
                            if (!$foundAttribute['success']) {
                                if (!$found){
                                    $result['' . $nextName] = ' ';
                                }
                            }else{
                                $result['' . $nextName] = $foundAttribute['result'];
                            }
                        }
                    }
                    if ($nextName === $xmlReader->name && !$found) {
                        if (!$foundAttribute['success']){
                            $result['' . $nextName] = ' ';
                        }else{
                            $result['' . $nextName] = $foundAttribute['result'];
                        }

                    }
                }else{
                    if (!$foundAttribute['success']) {
                        if (!$found){
                            $result['' . $nextName] = ' ';
                        }
                    }else{
                        $result['' . $nextName] = $foundAttribute['result'];
                    }
                }
            }
            $vac = $em->getRepository('AppBundle\Entity\Vacancy')->findBy(['identifier' => $result['identifier']]);
            if (!$vac){
                print_r($result);
//                $this->setVacancyToDB($result);
//                unset($result);
            }else{
                unset($result);
            }
            unset($vac);

        }
        return $progress;
    }

    /**@param String $node
 *
 * @return integer
 */
    public function parseNode($node){
        $attribute = array_pop(explode('#',$node));
        return $attribute;
    }


    /**@param array $result*/
    private function setVacancyToDB($result){
        $em = $this->getDoctrine()->getManager();
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
        $em->flush();
    }
}
