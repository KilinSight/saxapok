<?php
/**
 * Created by PhpStorm.
 * User: KilinSight
 * Date: 19.02.2018
 * Time: 20:06
 */

namespace AppBundle\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    /** @return EntityManager */
    public function getEm(){
        /** @var EntityManager $em */
        return $em = $this->getDoctrine()->getManager();
    }
}