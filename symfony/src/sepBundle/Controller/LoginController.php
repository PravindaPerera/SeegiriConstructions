<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
    public function loginAction(Request $request)
    {
        if($request->getMethod()=='POST')
        {
            $u_name = $request->get('username');
            $p_word = $request->get('password');
            $en_p_word = md5($p_word);
          //  return $this->render('sepBundle:Default:index.html.twig', array('rr'=>$en_p_word));
            $em = $this->getDoctrine()->getEntityManager();
            $repo_login = $em->getRepository('sepBundle:UserLogin');
            
            $user = $repo_login->findOneBy(array('username'=>$u_name, 'password'=>$en_p_word));
            
            if($user){
                $session = new Session();
                $session->set('id', $user->getUserId());
                
                $field = $user->getUserType();
                $url=$user->getUsername().".".$user->getUserId().".".$user->getUserType();
                
                $sattus=$user->getStatus();
                
                if($sattus==1){
                
                if($field=="supplier"){
                    return $this->redirect($this->generateUrl('profile_home_supplier',array('url'=>$url)));
                 //   return $this->render('sepBundle:Default:index.html.twig',array('flag'=>true, 'url'=>$url));
                }
                else if($field=="user"){
                    //return $this->render('sepBundle:Default:index.html.twig', array('rr'=>$r));
                    return $this->redirect($this->generateUrl('profile_home_user',array('url'=>$url)));
                }
                else if($field=="plant operator"){
                    return $this->redirect($this->generateUrl('profile_home_operator',array('url'=>$url)));
                    
                }
                else{
                    return $this->redirect($this->generateUrl('sep_homepage'));
                }
                
                }
                
                else{
                    return $this->render('sepBundle:Home:cover.html.twig', array('flag'=>false, 'flag1'=>false, 'flag2'=>true, 'flag3'=>false, 'flag4'=>true));
                }

               
            }
            else{
                return $this->redirect($this->generateUrl('login_fail'));
            }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }
    
    public function loginFailAction(Request $request){
        return $this->render('sepBundle:Home:cover.html.twig',array('flag'=>false, 'flag1'=>false, 'flag2'=>false, 'flag3'=>true));
    }
}
