<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SignUpController extends Controller {

    public function signUpSupplierAction(Request $request) {
        if ($request->getMethod() == 'POST') 
            {
            $comp_name = $request->get('comp_name');
            $comp_type = $request->get('type');
            $comp_num = $request->get('contact_num');
            $comp_address = $request->get('address');
            $u_name = $request->get('u_name');
            $pass = $request->get('pass');
            $en_pass = md5($pass);


            $em = $this->getDoctrine()->getEntityManager();
            $repo_login = $em->getRepository('sepBundle:UserLogin');

            $ul_access = new \sepBundle\Entity\UserLogin();
            $sd_access = new \sepBundle\Entity\SupplierDetails();

            $ul_access->setUsername($u_name);
            $ul_access->setPassword($en_pass);
            $ul_access->setUserType("supplier");
            $ul_access->setStatus(0);
            
            $em->persist($ul_access);
            $em->flush();
            
            $user=$repo_login->findOneBy(array('username'=>$u_name, 'password'=>$en_pass)); 
                    
            $sd_access->setSupCompanyName($comp_name);
            $sd_access->setSupType($comp_type);
            $sd_access->setContactNumber($comp_num);
            $sd_access->setAddress($comp_address);
            $sd_access->setUser($user);

            

            $em->persist($sd_access);
            $em->flush();
            
        return $this->render('sepBundle:Home:cover.html.twig', array('flag'=>true, 'flag1'=>false, 'flag2'=>false, 'flag3'=>false));
        
        }
        else{
            return $this->render('sepBundle:Home:cover.html.twig', array('flag'=>false, 'flag1'=>true, 'flag2'=>false, 'flag3'=>false));
        }

    }
    
    public function signUpUserAction(Request $request){
        if($request->getMethod()=='POST'){
            $f_name = $request->get('f_name');
            $l_name = $request->get('l_name');
            $u_name = $request->get('u_name');
            $type = $request->get('type');
            
            $contact_num = $request->get('contact_num');
            $pass = $request->get('pass');
            $en_pass = md5($pass);


            $em = $this->getDoctrine()->getEntityManager();
            $repo_login = $em->getRepository('sepBundle:UserLogin');


            $ul_access = new \sepBundle\Entity\UserLogin();
            $ud_access = new \sepBundle\Entity\UserDetails();

            $ul_access->setUsername($u_name);
            $ul_access->setPassword($en_pass);
            $ul_access->setUserType($type);
            $ul_access->setStatus(0);
            
            $em->persist($ul_access);
            $em->flush();
            
            $user=$repo_login->findOneBy(array('username'=>$u_name, 'password'=>$en_pass)); 
                    
            $ud_access->setFirstName($f_name);
            $ud_access->setLastName($l_name);
            $ud_access->setContactNumber($contact_num);
            $ud_access->setU($user);
            

            $em->persist($ud_access);
            $em->flush();
            
        return $this->render('sepBundle:Home:cover.html.twig', array('flag'=>true, 'flag1'=>false, 'flag2'=>false, 'flag3'=>false));
        }
        else{
            return $this->render('sepBundle:Home:cover.html.twig', array('flag'=>false, 'flag1'=>true, 'flag2'=>false, 'flag3'=>false));
        }
    }


}
