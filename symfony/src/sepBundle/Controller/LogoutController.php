<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LogoutController extends Controller
{
    public function logoutAction(Request $request)
    {
        $session= $request->getSession();
        $session->clear();
        $session->invalidate();
        return $this->redirect($this->generateUrl('sep_homepage'));
               
    }

}
