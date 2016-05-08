<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminProfileController extends Controller
{
    public function adminLoginAction($url, Request $request)
    {
            if ($request->getMethod() == "POST") {
            $u_name = $request->get('adminUser');
            $pass = $request->get('adminPass');
            $en_pass = md5($pass);

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $qur = $con->prepare("SELECT user_id FROM user_login WHERE username = :uName AND password  = :pWord");
            $qur->bindValue('uName', $u_name);
            $qur->bindValue('pWord', $en_pass);
            $qur->execute();
            $res = $qur->fetchAll();
            if ($res) {
                $full_url = explode(".", $url);
                $id = $full_url[1];
                
                $currYear = date("Y");
                //$currYear = date("2015");

                $em = $this->getDoctrine()->getEntityManager();
                $user_repo = $em->getRepository('sepBundle:UserLogin');
                $user = $user_repo->findOneBy(array('userId' => $id));

                $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
                $userDetails = $userDetailRepo->findOneBy(array('u' => $user));

                $supDetailRepo = $em->getRepository('sepBundle:SupplierDetails');
                $supDetails = $supDetailRepo->findAll();

                $userDRepo = $em->getRepository('sepBundle:UserDetails');
                $userD = $userDRepo->findAll();

                $con = $em->getConnection();
                $qur = $con->prepare("SELECT * FROM user_login WHERE status = '0'");
                $qur->execute();
                $res = $qur->fetchAll();
                
                $qur = $con->prepare("SELECT DISTINCT year FROM sales ORDER BY year DESC");
                $qur->execute();
                $acc_years = $qur->fetchAll();
                
                $janCost = 0;
                $febCost = 0;
                $marCost = 0;
                $aprCost = 0;
                $mayCost = 0;
                $junCost = 0;
                $julCost = 0;
                $augCost = 0;
                $sepCost = 0;
                $octCost = 0;
                $novCost = 0;
                $decCost = 0;
                
                $em = $this->getDoctrine()->getEntityManager();
                $con = $em->getConnection();
                $qur = $con->prepare("SELECT sum(sales_amount) sa, sum(payment_received) pr, month, year FROM sales where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $salesFig = $qur->fetchAll();
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM holcim_extra_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $holcimExtra_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($holcimExtra_cement); $i++) {
                        if($holcimExtra_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM holcim_ready_flow_plus_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $holcimReadyFlowPlus_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($holcimReadyFlowPlus_cement); $i++) {
                        if($holcimReadyFlowPlus_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM ordinary_portland_cement_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $ordinaryPortlandCement_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($ordinaryPortlandCement_cement); $i++) {
                        if($ordinaryPortlandCement_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else{}

                    }    
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM adcrete_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $adcrete_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($adcrete_chemical); $i++) {
                        if($adcrete_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM pozzolith300r_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $pozzolith300r_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($pozzolith300r_chemical); $i++) {
                        if($pozzolith300r_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else{}

                    }    
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM rheobuild561_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $rheobuild561_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($rheobuild561_chemical); $i++) {
                        if($rheobuild561_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else{}

                    }  
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM rheobuild1000_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $rheobuild1000_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($rheobuild1000_chemical); $i++) {
                        if($rheobuild1000_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else{}

                    }   
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM supercrete_hs_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $supercreteHS_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($supercreteHS_chemical); $i++) {
                        if($supercreteHS_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else{}

                    }   
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM supercrete_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $supercrete_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($supercrete_chemical); $i++) {
                        if($supercrete_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else{}

                    }    
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chips where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chips_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_chips_year); $i++) {
                        if($cost_chips_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM diesel where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_diesel_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_diesel_year); $i++) {
                        if($cost_diesel_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM m_sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_m_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_m_sand_year); $i++) {
                        if($cost_m_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM metal where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_metal_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_metal_year); $i++) {
                        if($cost_metal_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_sand_year); $i++) {
                        if($cost_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                
                $cost = (double) 0;
                $cost_holcimExtra_cement = (double) 0;
                $cost_holcimReadyFlowPlus_cement = (double) 0;
                $cost_ordinaryPortlandCement_cement = (double) 0;
                $cost_adcrete_chemical = (double) 0;
                $cost_pozzolith300r_chemical = (double) 0;
                $cost_rheobuild561_chemical = (double) 0;
                $cost_rheobuild1000_chemical = (double) 0;
                $cost_supercreteHS_chemical = (double) 0;
                $cost_supercrete_chemical = (double) 0;
                $cost_sand = (double) 0;
                $cost_metal = (double) 0;
                $cost_m_sand = (double) 0;
                $cost_diesel = (double) 0;
                $cost_chips = (double) 0;


                //Costs - Cement1
                $q1 = $con->prepare("SELECT * FROM holcim_extra_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_holcimExtra_cement = $cost_holcimExtra_cement + (double) $cement[$i]["net_cost"];
                }
                
                //Costs - Cement2
                $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_holcimReadyFlowPlus_cement = $cost_holcimReadyFlowPlus_cement + (double) $cement[$i]["net_cost"];
                }
                
                //Costs - Cement3
                $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_ordinaryPortlandCement_cement = $cost_ordinaryPortlandCement_cement + (double) $cement[$i]["net_cost"];
                }

                //Costs - Chemical1
                $q1 = $con->prepare("SELECT * FROM adcrete_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_adcrete_chemical = $cost_adcrete_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical2
                $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_pozzolith300r_chemical = $cost_pozzolith300r_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical3
                $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_rheobuild561_chemical = $cost_rheobuild561_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical4
                $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_rheobuild1000_chemical = $cost_rheobuild1000_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical5
                $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_supercreteHS_chemical = $cost_supercreteHS_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical6
                $q1 = $con->prepare("SELECT * FROM supercrete_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_supercrete_chemical = $cost_supercrete_chemical + (double) $chemical[$i]["net_cost"];
                }

                //Costs - Sand
                $q1 = $con->prepare("SELECT * FROM sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $sand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($sand); $i++) {
                    $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
                }

                //Costs - Metal
                $q1 = $con->prepare("SELECT * FROM metal where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $metal = $q1->fetchAll();
                for ($i = 0; $i < sizeof($metal); $i++) {
                    $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
                }

                //Costs - diesel
                $q1 = $con->prepare("SELECT * FROM diesel where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $diesel = $q1->fetchAll();
                for ($i = 0; $i < sizeof($diesel); $i++) {
                    $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
                }

                //Costs - chips
                $q1 = $con->prepare("SELECT * FROM chips where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chips = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chips); $i++) {
                    $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
                }

                //Costs - M-Sand
                $q1 = $con->prepare("SELECT * FROM m_sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $mSand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($mSand); $i++) {
                    $cost_m_sand = $cost_m_sand + (double) $mSand[$i]["net_cost"];
                }
                //Admin expenses 
                $q1 = $con->prepare("SELECT * FROM other_expenses");
                $q1->execute();
                $adminExp = $q1->fetchAll();
                $totAdminCost = (double) 0;
                for ($i = 0; $i < sizeof($adminExp); $i++) {
                    $totAdminCost = $totAdminCost + (double) $adminExp[$i]["amount"];
                }
                
                
               // return $this->render('sepBundle:Default:index.html.twig', array('rr' => $salesFig));
                
                return $this->render('sepBundle:Profile:adminProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 
                    'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 'ud' => $userD, 'sales'=>$salesFig,
                    'adminCost'=>$totAdminCost, 
                    'holcimExtracementC'=>$cost_holcimExtra_cement, 'holcimReadyFlowPluscementC'=>$cost_holcimReadyFlowPlus_cement, 'ordinaryPortlandCementcementC'=>$cost_ordinaryPortlandCement_cement,
                    'adcretechemicalC'=>$cost_adcrete_chemical, 'pozzolith300rchemicalC'=>$cost_pozzolith300r_chemical, 'rheobuild561chemicalC'=>$cost_rheobuild561_chemical,
                    'rheobuild1000chemicalC'=>$cost_rheobuild1000_chemical, 'supercreteHSchemicalC'=>$cost_supercreteHS_chemical, 'supercretechemicalC'=>$cost_supercrete_chemical,
                    'sandC'=>$cost_sand, 'chipsC'=>$cost_chips, 'mSandC'=>$cost_m_sand, 'dieselC'=>$cost_diesel, 'metalC'=>$cost_metal,
                    'holcimExtra_cement'=>$holcimExtra_cement, 'holcimReadyFlowPlus_cement'=>$holcimReadyFlowPlus_cement, 'ordinaryPortlandCement_cement'=>$ordinaryPortlandCement_cement,
                    'adcrete_chemical'=>$adcrete_chemical, 'pozzolith300r_chemical' => $pozzolith300r_chemical, 'rheobuild561_chemical'=>$rheobuild561_chemical,
                    'rheobuild1000_chemical'=>$rheobuild1000_chemical, 'supercreteHS_chemical'=>$supercreteHS_chemical, 'supercrete_chemical' => $supercrete_chemical,
                    'sandCYear'=>$cost_sand_year, 'chipsCYear'=>$cost_chips_year, 'mSandCYear'=>$cost_m_sand_year, 'dieselCYear'=>$cost_diesel_year, 'metalCYear'=>$cost_metal_year,
                    'jan'=>$janCost, 'feb'=>$febCost, 'mar'=>$marCost, 'apr'=>$aprCost, 'may'=>$mayCost, 'jun'=>$junCost, 'jul'=>$julCost, 'aug'=>$augCost, 'sep'=>$sepCost, 'oct'=>$octCost, 'nov'=>$novCost, 'dec'=>$decCost,
                    'currYear'=>$currYear, 'acc_years'=>$acc_years)); 
            } else {
                return $this->redirect($this->generateUrl('sep_homepage'));
            }
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }
    
        public function adminChangePasswordAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $current = $request->get('current_password');
            $new = $request->get('new_password');
            $re_new = $request->get('re_new_password');
            $rr_new = md5($re_new);
            $cur = (string) md5($current);
            $type = "Admin";

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            $qur = $con->prepare("UPDATE user_login SET password = :pass WHERE user_type = 'Admin'");
            $qur->bindValue('pass', $rr_new);
            $qur->execute();
            return $this->redirect($this->generateUrl('admin_login', array('url' => $url)));
        } else {
            
        }
    }
    
        public function adminUsernameChangeAction($url, Request $request) {
        if ($request->getMethod() == "POST") {

            $current = $request->get('FName');
            $new = $request->get('NName');

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $qur = $con->prepare("UPDATE user_login SET username = :uname WHERE user_type = 'Admin'");
            $qur->bindValue('uname', $new);
            $qur->execute();
            return $this->redirect($this->generateUrl('admin_login', array('url' => $url)));
        } else {
            
        }
    }
    
        public function adminGrantAcceptanceAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $repo_users = $em->getRepository('sepBundle:UserLogin');
            $users = $repo_users->findAll();
            $con = $em->getConnection();
            for ($i = 0; $i < sizeof($users); $i++) {
                if (isset($_POST[$users[$i]->getUserId()])) {
                    $id = $users[$i]->getUserId();
                    $qur = $con->prepare("UPDATE user_login SET status = '1' WHERE user_id = :id");
                    $qur->bindValue('id', $id);
                    $qur->execute();
                }
            }
            
            return $this->redirect($this->generateUrl('admin_login', array('url' => $url)));

        } else {
            
        }
    }
    
    public function selectedYearAdminDisplayAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST"){
                
                $selYear = $request->get('year');
                
                $full_url = explode(".", $url);
                $id = $full_url[1];
                
                $currYear = date("$selYear");

                $em = $this->getDoctrine()->getEntityManager();
                $user_repo = $em->getRepository('sepBundle:UserLogin');
                $user = $user_repo->findOneBy(array('userId' => $id));

                $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
                $userDetails = $userDetailRepo->findOneBy(array('u' => $user));

                $supDetailRepo = $em->getRepository('sepBundle:SupplierDetails');
                $supDetails = $supDetailRepo->findAll();

                $userDRepo = $em->getRepository('sepBundle:UserDetails');
                $userD = $userDRepo->findAll();

                $con = $em->getConnection();
                $qur = $con->prepare("SELECT * FROM user_login WHERE status = '0'");
                $qur->execute();
                $res = $qur->fetchAll();
                
                $qur = $con->prepare("SELECT DISTINCT year FROM sales ORDER BY year DESC");
                $qur->execute();
                $acc_years = $qur->fetchAll();
                
                $janCost = 0;
                $febCost = 0;
                $marCost = 0;
                $aprCost = 0;
                $mayCost = 0;
                $junCost = 0;
                $julCost = 0;
                $augCost = 0;
                $sepCost = 0;
                $octCost = 0;
                $novCost = 0;
                $decCost = 0;
                
                $em = $this->getDoctrine()->getEntityManager();
                $con = $em->getConnection();
                $qur = $con->prepare("SELECT sum(sales_amount) sa, sum(payment_received) pr, month, year FROM sales where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $salesFig = $qur->fetchAll();
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM holcim_extra_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $holcimExtra_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($holcimExtra_cement); $i++) {
                        if($holcimExtra_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else if($holcimExtra_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $holcimExtra_cement[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM holcim_ready_flow_plus_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $holcimReadyFlowPlus_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($holcimReadyFlowPlus_cement); $i++) {
                        if($holcimReadyFlowPlus_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else if($holcimReadyFlowPlus_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $holcimReadyFlowPlus_cement[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM ordinary_portland_cement_cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $ordinaryPortlandCement_cement = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($ordinaryPortlandCement_cement); $i++) {
                        if($ordinaryPortlandCement_cement[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else if($ordinaryPortlandCement_cement[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $ordinaryPortlandCement_cement[$i]["sa"];
                        }
                        else{}

                    }    
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM adcrete_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $adcrete_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($adcrete_chemical); $i++) {
                        if($adcrete_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else if($adcrete_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $adcrete_chemical[$i]["sa"];
                        }
                        else{}

                    }
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM pozzolith300r_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $pozzolith300r_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($pozzolith300r_chemical); $i++) {
                        if($pozzolith300r_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else if($pozzolith300r_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $pozzolith300r_chemical[$i]["sa"];
                        }
                        else{}

                    }    
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM rheobuild561_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $rheobuild561_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($rheobuild561_chemical); $i++) {
                        if($rheobuild561_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else if($rheobuild561_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $rheobuild561_chemical[$i]["sa"];
                        }
                        else{}

                    }  
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM rheobuild1000_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $rheobuild1000_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($rheobuild1000_chemical); $i++) {
                        if($rheobuild1000_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else if($rheobuild1000_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $rheobuild1000_chemical[$i]["sa"];
                        }
                        else{}

                    }   
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM supercrete_hs_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $supercreteHS_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($supercreteHS_chemical); $i++) {
                        if($supercreteHS_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else if($supercreteHS_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $supercreteHS_chemical[$i]["sa"];
                        }
                        else{}

                    }   
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM supercrete_chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $supercrete_chemical = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($supercrete_chemical); $i++) {
                        if($supercrete_chemical[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else if($supercrete_chemical[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $supercrete_chemical[$i]["sa"];
                        }
                        else{}

                    }    
                    
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chips where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chips_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_chips_year); $i++) {
                        if($cost_chips_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_chips_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM diesel where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_diesel_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_diesel_year); $i++) {
                        if($cost_diesel_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_diesel_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM m_sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_m_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_m_sand_year); $i++) {
                        if($cost_m_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_m_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM metal where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_metal_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_metal_year); $i++) {
                        if($cost_metal_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_metal_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_sand_year); $i++) {
                        if($cost_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (double) $cost_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                
                $cost = (double) 0;
                $cost_holcimExtra_cement = (double) 0;
                $cost_holcimReadyFlowPlus_cement = (double) 0;
                $cost_ordinaryPortlandCement_cement = (double) 0;
                $cost_adcrete_chemical = (double) 0;
                $cost_pozzolith300r_chemical = (double) 0;
                $cost_rheobuild561_chemical = (double) 0;
                $cost_rheobuild1000_chemical = (double) 0;
                $cost_supercreteHS_chemical = (double) 0;
                $cost_supercrete_chemical = (double) 0;
                $cost_sand = (double) 0;
                $cost_metal = (double) 0;
                $cost_m_sand = (double) 0;
                $cost_diesel = (double) 0;
                $cost_chips = (double) 0;


                //Costs - Cement1
                $q1 = $con->prepare("SELECT * FROM holcim_extra_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_holcimExtra_cement = $cost_holcimExtra_cement + (double) $cement[$i]["net_cost"];
                }
                
                //Costs - Cement2
                $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_holcimReadyFlowPlus_cement = $cost_holcimReadyFlowPlus_cement + (double) $cement[$i]["net_cost"];
                }
                
                //Costs - Cement3
                $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_ordinaryPortlandCement_cement = $cost_ordinaryPortlandCement_cement + (double) $cement[$i]["net_cost"];
                }

                //Costs - Chemical1
                $q1 = $con->prepare("SELECT * FROM adcrete_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_adcrete_chemical = $cost_adcrete_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical2
                $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_pozzolith300r_chemical = $cost_pozzolith300r_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical3
                $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_rheobuild561_chemical = $cost_rheobuild561_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical4
                $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_rheobuild1000_chemical = $cost_rheobuild1000_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical5
                $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_supercreteHS_chemical = $cost_supercreteHS_chemical + (double) $chemical[$i]["net_cost"];
                }
                
                //Costs - Chemical6
                $q1 = $con->prepare("SELECT * FROM supercrete_chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_supercrete_chemical = $cost_supercrete_chemical + (double) $chemical[$i]["net_cost"];
                }

                //Costs - Sand
                $q1 = $con->prepare("SELECT * FROM sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $sand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($sand); $i++) {
                    $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
                }

                //Costs - Metal
                $q1 = $con->prepare("SELECT * FROM metal where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $metal = $q1->fetchAll();
                for ($i = 0; $i < sizeof($metal); $i++) {
                    $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
                }

                //Costs - diesel
                $q1 = $con->prepare("SELECT * FROM diesel where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $diesel = $q1->fetchAll();
                for ($i = 0; $i < sizeof($diesel); $i++) {
                    $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
                }

                //Costs - chips
                $q1 = $con->prepare("SELECT * FROM chips where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chips = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chips); $i++) {
                    $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
                }

                //Costs - M-Sand
                $q1 = $con->prepare("SELECT * FROM m_sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $mSand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($mSand); $i++) {
                    $cost_m_sand = $cost_m_sand + (double) $mSand[$i]["net_cost"];
                }
                //Admin expenses 
                $q1 = $con->prepare("SELECT * FROM other_expenses");
                $q1->execute();
                $adminExp = $q1->fetchAll();
                $totAdminCost = (double) 0;
                for ($i = 0; $i < sizeof($adminExp); $i++) {
                    $totAdminCost = $totAdminCost + (double) $adminExp[$i]["amount"];
                }
                
                
               // return $this->render('sepBundle:Default:index.html.twig', array('rr' => $salesFig));
                
                return $this->render('sepBundle:Profile:adminProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 
                    'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 'ud' => $userD, 'sales'=>$salesFig,
                    'adminCost'=>$totAdminCost, 
                    'holcimExtracementC'=>$cost_holcimExtra_cement, 'holcimReadyFlowPluscementC'=>$cost_holcimReadyFlowPlus_cement, 'ordinaryPortlandCementcementC'=>$cost_ordinaryPortlandCement_cement,
                    'adcretechemicalC'=>$cost_adcrete_chemical, 'pozzolith300rchemicalC'=>$cost_pozzolith300r_chemical, 'rheobuild561chemicalC'=>$cost_rheobuild561_chemical,
                    'rheobuild1000chemicalC'=>$cost_rheobuild1000_chemical, 'supercreteHSchemicalC'=>$cost_supercreteHS_chemical, 'supercretechemicalC'=>$cost_supercrete_chemical,
                    'sandC'=>$cost_sand, 'chipsC'=>$cost_chips, 'mSandC'=>$cost_m_sand, 'dieselC'=>$cost_diesel, 'metalC'=>$cost_metal,
                    'holcimExtra_cement'=>$holcimExtra_cement, 'holcimReadyFlowPlus_cement'=>$holcimReadyFlowPlus_cement, 'ordinaryPortlandCement_cement'=>$ordinaryPortlandCement_cement,
                    'adcrete_chemical'=>$adcrete_chemical, 'pozzolith300r_chemical' => $pozzolith300r_chemical, 'rheobuild561_chemical'=>$rheobuild561_chemical,
                    'rheobuild1000_chemical'=>$rheobuild1000_chemical, 'supercreteHS_chemical'=>$supercreteHS_chemical, 'supercrete_chemical' => $supercrete_chemical,
                    'sandCYear'=>$cost_sand_year, 'chipsCYear'=>$cost_chips_year, 'mSandCYear'=>$cost_m_sand_year, 'dieselCYear'=>$cost_diesel_year, 'metalCYear'=>$cost_metal_year,
                    'jan'=>$janCost, 'feb'=>$febCost, 'mar'=>$marCost, 'apr'=>$aprCost, 'may'=>$mayCost, 'jun'=>$junCost, 'jul'=>$julCost, 'aug'=>$augCost, 'sep'=>$sepCost, 'oct'=>$octCost, 'nov'=>$novCost, 'dec'=>$decCost,
                    'currYear'=>$currYear, 'acc_years'=>$acc_years)); 
            }
            else{
                return $this->redirect($this->generateUrl('sep_homepage'));
            }
                
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
            
    }
}
