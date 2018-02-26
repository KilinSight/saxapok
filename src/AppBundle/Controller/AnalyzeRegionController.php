<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnalyzeRegionController extends BaseController
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

    /**
     * @Route("/get_regions_from_DB", name="get_regions_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegionsFromDBAction(Request $request)
    {
        $text = $request->get('text');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('regions')->from('AppBundle\Entity\Regions', 'regions');
        if ($text){
            $qb->andWhere($qb->expr()->like('regions.name',$qb->expr()->literal('%'.$text.'%')));
        }
        $regions = $qb->getQuery()->getArrayResult();

        return new JsonResponse($regions);
    }

    /**
     * @Route("/get_industries_from_DB", name="get_industries_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getIndustriesFromDBAction(Request $request)
    {
        $text = $request->get('text');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('industries')->from('AppBundle\Entity\Industries', 'industries');
        if ($text){
            $qb->andWhere($qb->expr()->like('industries.name',$qb->expr()->literal('%'.$text.'%')));
        }
        $industries = $qb->getQuery()->getArrayResult();

        return new JsonResponse($industries);
    }

    /**
     * @Route("/get_professions_from_DB", name="get_professions_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfessionsFromDBAction(Request $request)
    {
        $text = $request->get('text');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('professions')->from('AppBundle\Entity\Professions', 'professions');
        if ($text){
            $qb->andWhere($qb->expr()->like('professions.name',$qb->expr()->literal('%'.$text.'%')));
        }

        $professions = $qb->getQuery()->getArrayResult();

        return new JsonResponse($professions);
    }

    /**
     * @Route("/get_organizations_from_DB", name="get_organizations_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrganizationsFromDBAction(Request $request)
    {
        $text = $request->get('text');
        $regions = $request->get('regions');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('organizations')
            ->from('AppBundle\Entity\Organizations', 'organizations');
        if ($text){
            $qb->andWhere($qb->expr()->like('organizations.name',$qb->expr()->literal('%'.$text.'%')));
        }
        if($regions){
            $qb->andWhere($qb->expr()->in('organizations.region', $regions));
        }

        $organizations = $qb->getQuery()->getArrayResult();

        return new JsonResponse($organizations);
    }

    /**
     * @Route("/get_resumes_from_DB", name="get_resumes_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getResumesFromDBAction(Request $request)
    {
        $text = $request->get('text');
        $regions = $request->get('regions');

        $professions = $request->get('professions');
        $industries = $request->get('industries');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('resumes')
            ->from('AppBundle\Entity\Resumes', 'resumes');
        if (count($regions)) {
            $qb->andWhere($qb->expr()->in('resumes.region', $regions));
        }
        if (count($professions)) {
            $qb->andWhere($qb->expr()->in('resumes.profession', $professions));
        }
        if (count($industries)) {
            $qb->andWhere($qb->expr()->in('resumes.industry', $industries));
        }
        if ($text){
            $qb->andWhere($qb->expr()->like('resumes.positionName',$qb->expr()->literal('%'.$text.'%')));
        }

        $resumes = $qb->getQuery()->getArrayResult();

        return new JsonResponse($resumes);
    }

    /**
     * @Route("/get_vacancies_from_DB", name="get_vacancies_from_DB", options={"expose":true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getVacanciesFromDBAction(Request $request)
    {
        $text = $request->get('text');
        $regions = $request->get('regions');
        $professions = $request->get('professions');
        $industries = $request->get('industries');
        $organizations = $request->get('organizations');

        $em = $this->getEm();
        $qb = $em->createQueryBuilder();
        $qb->select('vacancies')
            ->from('AppBundle\Entity\Vacancy', 'vacancies');
        if (count($regions)) {
            $qb->andWhere($qb->expr()->in('vacancies.region_id', $regions));
        }
        if (count($professions)) {
            $qb->andWhere($qb->expr()->in('vacancies.profession_id', $professions));
        }
        if (count($industries)) {
            $qb->andWhere($qb->expr()->in('vacancies.industry_id', $industries));
        }
        if (count($organizations)) {
            $qb->andWhere($qb->expr()->in('vacancies.organizations_id', $organizations));
        }
        if ($text){
            $qb->andWhere($qb->expr()->like('vacancies.title',$qb->expr()->literal('%'.$text.'%')));
        }

        $vacancies = $qb->getQuery()->getArrayResult();

        return new JsonResponse($vacancies);
    }


}
