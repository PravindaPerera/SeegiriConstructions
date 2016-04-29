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
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_cement_year = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($cost_cement_year); $i++) {
                        if($cost_cement_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chemical_year = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($cost_chemical_year); $i++) {
                        if($cost_chemical_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chips where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chips_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_chips_year); $i++) {
                        if($cost_chips_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM diesel where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_diesel_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_diesel_year); $i++) {
                        if($cost_diesel_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM m_sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_m_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_m_sand_year); $i++) {
                        if($cost_m_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM metal where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_metal_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_metal_year); $i++) {
                        if($cost_metal_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_sand_year); $i++) {
                        if($cost_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                
                $cost = (int) 0;
                $cost_cement = (int) 0;
                $cost_chemical = (int) 0;
                $cost_sand = (int) 0;
                $cost_metal = (int) 0;
                $cost_m_sand = (int) 0;
                $cost_diesel = (int) 0;
                $cost_chips = (int) 0;


                //Costs - Cement
                $q1 = $con->prepare("SELECT * FROM cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_cement = $cost_cement + (int) $cement[$i]["net_cost"];
                }

                //Costs - Chemical
                $q1 = $con->prepare("SELECT * FROM chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_chemical = $cost_chemical + (int) $chemical[$i]["net_cost"];
                }

                //Costs - Sand
                $q1 = $con->prepare("SELECT * FROM sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $sand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($sand); $i++) {
                    $cost_sand = $cost_sand + (int) $sand[$i]["net_cost"];
                }

                //Costs - Metal
                $q1 = $con->prepare("SELECT * FROM metal where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $metal = $q1->fetchAll();
                for ($i = 0; $i < sizeof($metal); $i++) {
                    $cost_metal = $cost_metal + (int) $metal[$i]["net_cost"];
                }

                //Costs - diesel
                $q1 = $con->prepare("SELECT * FROM diesel where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $diesel = $q1->fetchAll();
                for ($i = 0; $i < sizeof($diesel); $i++) {
                    $cost_diesel = $cost_diesel + (int) $diesel[$i]["net_cost"];
                }

                //Costs - chips
                $q1 = $con->prepare("SELECT * FROM chips where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chips = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chips); $i++) {
                    $cost_chips = $cost_chips + (int) $chips[$i]["net_cost"];
                }

                //Costs - M-Sand
                $q1 = $con->prepare("SELECT * FROM m_sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $mSand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($mSand); $i++) {
                    $cost_m_sand = $cost_m_sand + (int) $mSand[$i]["net_cost"];
                }
                //Admin expenses 
                $q1 = $con->prepare("SELECT * FROM other_expenses");
                $q1->execute();
                $adminExp = $q1->fetchAll();
                $totAdminCost = (int) 0;
                for ($i = 0; $i < sizeof($adminExp); $i++) {
                    $totAdminCost = $totAdminCost + (int) $adminExp[$i]["amount"];
                }
                
                
               // return $this->render('sepBundle:Default:index.html.twig', array('rr' => $salesFig));
                
                return $this->render('sepBundle:Profile:adminProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 
                    'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 'ud' => $userD, 'sales'=>$salesFig,
                    'adminCost'=>$totAdminCost, 'cementC'=>$cost_cement, 'chemicalC'=>$cost_chemical, 'sandC'=>$cost_sand, 'chipsC'=>$cost_chips, 'mSandC'=>$cost_m_sand, 'dieselC'=>$cost_diesel, 'metalC'=>$cost_metal,
                    'cementCYear'=>$cost_cement_year, 'chemicalCYear'=>$cost_chemical_year, 'sandCYear'=>$cost_sand_year, 'chipsCYear'=>$cost_chips_year, 'mSandCYear'=>$cost_m_sand_year, 'dieselCYear'=>$cost_diesel_year, 'metalCYear'=>$cost_metal_year,
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
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM cement where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_cement_year = $qur->fetchAll();

                    for ($i = 0; $i < sizeof($cost_cement_year); $i++) {
                        if($cost_cement_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else if($cost_cement_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_cement_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chemical where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chemical_year = $qur->fetchAll();
                
                    for ($i = 0; $i < sizeof($cost_chemical_year); $i++) {
                        if($cost_chemical_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else if($cost_chemical_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_chemical_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM chips where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_chips_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_chips_year); $i++) {
                        if($cost_chips_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else if($cost_chips_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_chips_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM diesel where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_diesel_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_diesel_year); $i++) {
                        if($cost_diesel_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else if($cost_diesel_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_diesel_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM m_sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_m_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_m_sand_year); $i++) {
                        if($cost_m_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else if($cost_m_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_m_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM metal where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_metal_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_metal_year); $i++) {
                        if($cost_metal_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else if($cost_metal_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_metal_year[$i]["sa"];
                        }
                        else{}

                    }
                
                $qur = $con->prepare("SELECT sum(net_cost) sa, month, year FROM sand where year = :yr GROUP BY month, year ORDER BY year");
                $qur->bindValue('yr', $currYear);
                $qur->execute();
                $cost_sand_year = $qur->fetchAll();
                
                for ($i = 0; $i < sizeof($cost_sand_year); $i++) {
                        if($cost_sand_year[$i]["month"] == "January"){
                           $janCost =  $janCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "February"){
                            $febCost =  $febCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "March"){
                            $marCost =  $marCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "April"){
                            $aprCost =  $aprCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "May"){
                            $mayCost =  $mayCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "June"){
                            $junCost =  $junCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "July"){
                            $julCost =  $julCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "August"){
                            $augCost =  $augCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "September"){
                            $sepCost =  $sepCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "October"){
                            $octCost =  $octCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "November"){
                            $novCost =  $novCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else if($cost_sand_year[$i]["month"] == "December"){
                            $decCost =  $decCost + (int) $cost_sand_year[$i]["sa"];
                        }
                        else{}

                    }
                
                
                $cost = (int) 0;
                $cost_cement = (int) 0;
                $cost_chemical = (int) 0;
                $cost_sand = (int) 0;
                $cost_metal = (int) 0;
                $cost_m_sand = (int) 0;
                $cost_diesel = (int) 0;
                $cost_chips = (int) 0;


                //Costs - Cement
                $q1 = $con->prepare("SELECT * FROM cement where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $cement = $q1->fetchAll();
                for ($i = 0; $i < sizeof($cement); $i++) {
                    $cost_cement = $cost_cement + (int) $cement[$i]["net_cost"];
                }

                //Costs - Chemical
                $q1 = $con->prepare("SELECT * FROM chemical where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chemical = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chemical); $i++) {
                    $cost_chemical = $cost_chemical + (int) $chemical[$i]["net_cost"];
                }

                //Costs - Sand
                $q1 = $con->prepare("SELECT * FROM sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $sand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($sand); $i++) {
                    $cost_sand = $cost_sand + (int) $sand[$i]["net_cost"];
                }

                //Costs - Metal
                $q1 = $con->prepare("SELECT * FROM metal where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $metal = $q1->fetchAll();
                for ($i = 0; $i < sizeof($metal); $i++) {
                    $cost_metal = $cost_metal + (int) $metal[$i]["net_cost"];
                }

                //Costs - diesel
                $q1 = $con->prepare("SELECT * FROM diesel where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $diesel = $q1->fetchAll();
                for ($i = 0; $i < sizeof($diesel); $i++) {
                    $cost_diesel = $cost_diesel + (int) $diesel[$i]["net_cost"];
                }

                //Costs - chips
                $q1 = $con->prepare("SELECT * FROM chips where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $chips = $q1->fetchAll();
                for ($i = 0; $i < sizeof($chips); $i++) {
                    $cost_chips = $cost_chips + (int) $chips[$i]["net_cost"];
                }

                //Costs - M-Sand
                $q1 = $con->prepare("SELECT * FROM m_sand where year = :yr");
                $q1->bindValue('yr', $currYear);
                $q1->execute();
                $mSand = $q1->fetchAll();
                for ($i = 0; $i < sizeof($mSand); $i++) {
                    $cost_m_sand = $cost_m_sand + (int) $mSand[$i]["net_cost"];
                }
                //Admin expenses 
                $q1 = $con->prepare("SELECT * FROM other_expenses");
                $q1->execute();
                $adminExp = $q1->fetchAll();
                $totAdminCost = (int) 0;
                for ($i = 0; $i < sizeof($adminExp); $i++) {
                    $totAdminCost = $totAdminCost + (int) $adminExp[$i]["amount"];
                }

                
                return $this->render('sepBundle:Profile:adminProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 
                    'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 'ud' => $userD, 'sales'=>$salesFig,
                    'adminCost'=>$totAdminCost, 'cementC'=>$cost_cement, 'chemicalC'=>$cost_chemical, 'sandC'=>$cost_sand, 'chipsC'=>$cost_chips, 'mSandC'=>$cost_m_sand, 'dieselC'=>$cost_diesel, 'metalC'=>$cost_metal,
                    'cementCYear'=>$cost_cement_year, 'chemicalCYear'=>$cost_chemical_year, 'sandCYear'=>$cost_sand_year, 'chipsCYear'=>$cost_chips_year, 'mSandCYear'=>$cost_m_sand_year, 'dieselCYear'=>$cost_diesel_year, 'metalCYear'=>$cost_metal_year,
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
