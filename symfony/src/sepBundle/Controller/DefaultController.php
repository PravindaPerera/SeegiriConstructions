<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('sepBundle:Home:cover.html.twig',array('flag'=>false, 'flag1'=>false, 'flag2'=>false, 'flag3'=>false, 'flag4'=>true));
    }
}
