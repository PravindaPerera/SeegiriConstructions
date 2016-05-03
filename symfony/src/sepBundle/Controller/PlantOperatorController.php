<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PlantOperatorController extends Controller
{

    public function loginOperatorAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
        $full_url = explode(".", $url);
        $id = $full_url[1];

        $em = $this->getDoctrine()->getEntityManager();
        $user_repo = $em->getRepository('sepBundle:UserLogin');
        $user = $user_repo->findOneBy(array('userId' => $id));
        $userLoginDetails = $user_repo->findAll();

        $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
        $userDetails = $userDetailRepo->findOneBy(array('u' => $user));
        
        $userProfilePics = $this->displayImage($url);
        //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $userProfilePics));
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:OperatorProfile:plantOperatorProfile.html.twig', array('flag1' => false, 'flag2' => true,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user,
            'ulogin'=>$userLoginDetails, 'im'=> $userProfilePics)); 
        }
        
        else{
            return $this->render('sepBundle:OperatorProfile:plantOperatorProfile.html.twig', array('flag1' => true, 'flag2' => false,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 
            'ulogin'=>$userLoginDetails, 'im'=> $userProfilePics)); 
        }
        
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
        //return $this->render('sepBundle:OperatorProfile:plantOperatorProfile.html.twig');
    }
    
    public function invenManagementAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            
        $full_url = explode('.', $url);
        $supId = $full_url[1];    
        
        $em = $this->getDoctrine()->getEntityManager();
        $user_repo = $em->getRepository('sepBundle:UserLogin');
        $user = $user_repo->findOneBy(array('userId' => $supId));

        $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
        $userDetails = $userDetailRepo->findOneBy(array('u' => $user));    
            
        $testDate = date('Y-m-d');
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $querry = $con->prepare("SELECT DISTINCT sup_type FROM supplier_details");
        $querry->execute();
        $typeDet = $querry->fetchAll();

        $cementVal = (int) 0;
        $chemVal = (int) 0;
        $metalVal = (int) 0;
        $sandVal = (int) 0;
        $dieselVal = (int) 0;
        $m_sandVal = (int) 0;
        $chipsVal = (int) 0;
        
        $querry = $con->prepare("SELECT * FROM reorder_levels");
        $querry->execute();
        $repo_reorderDetails = $querry->fetchAll();
               
        //cement
        $querry = $con->prepare("SELECT clossing_balance FROM cement ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_cement = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Cement");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitCement = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitCement) {
            
        }
        else{
            $cementVal = (int) 1;
        }
        
        //Sand
        $querry = $con->prepare("SELECT clossing_balance FROM sand ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_sand = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Sand");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitSand = (int) $result[0]["reorder_level"];
        if ($rr <= $limitSand) {
            
        }
        else{
            $sandVal = (int) 1;
        }
        //Chemical
        $querry = $con->prepare("SELECT clossing_balance FROM chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_chemical = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Chemical");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitChemical = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitChemical) {
            
        }
        if ($rr > $limitChemical) {
            $chemVal = (int) 1;
        }
        //Metal
        $querry = $con->prepare("SELECT clossing_balance FROM metal ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_metal = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Metal");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitMetal = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitMetal) {
            
        }
        else {
            $metalVal = (int) 1;
        }
        
        //Diesel
        $querry = $con->prepare("SELECT clossing_balance FROM diesel ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_diesel = $rr;
         
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Diesel");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitDiesel = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitDiesel) {
            
        }
        else {
            $dieselVal = (int) 1;
        }

        //Chips
        $querry = $con->prepare("SELECT clossing_balance FROM chips ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_chips = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Chips");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitChips = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitChips) {
            
        }
        else {
            $chipsVal = (int) 1;
        }
        //M-Sand
        $querry = $con->prepare("SELECT clossing_balance FROM m_sand ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        $rr = (int) $result[0]["clossing_balance"];
        $cb_m_sand = $rr;
          
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "M_Sand");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitM_Sand = (int) $result[0]["reorder_level"];
        
        if ($rr <= $limitM_Sand) {
            
        }
        else {
            $m_sandVal = (int) 1;
        }
        $counts = array("$cementVal", "$chemVal", "$metalVal", "$sandVal", "$dieselVal", "$m_sandVal", "$chipsVal");

        $userProfilePics = $this->displayImage($url);
        
        //Sales order details
        $qur = $con->prepare("SELECT * FROM sales_orders WHERE status = 0 ORDER BY sales_order_date DESC");
        $qur->execute();
        $salesOrderDet = $qur->fetchAll();
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:OperatorProfile:purAndinvenMgtPO.html.twig', array('url' => $url, 'toDate' => $testDate, 'types' => $typeDet, 'clbalance' => $counts, 'user'=>$user, 'userDetails' => $userDetails,
            'cbCement' => $cb_cement, 'cbChemical' => $cb_chemical, 'cbChips' => $cb_chips, 'cbMetal' => $cb_metal,  
            'cbSand' => $cb_sand, 'cbMSand' => $cb_m_sand, 'cbDiesel' => $cb_diesel,
            'lCement' => $limitCement, 'lChemical' => $limitChemical, 'lChips' => $limitChips, 'lMetal' => $limitMetal,  
            'lSand' => $limitSand, 'lMSand' => $limitM_Sand, 'lDiesel' => $limitDiesel,
            'reorderDetails' => $repo_reorderDetails, 'im'=>$userProfilePics, 
            'salesOrderDet' => $salesOrderDet, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
        return $this->render('sepBundle:OperatorProfile:purAndinvenMgtPO.html.twig', array('url' => $url, 'toDate' => $testDate, 'types' => $typeDet, 'clbalance' => $counts, 'user'=>$user, 'userDetails' => $userDetails,
            'cbCement' => $cb_cement, 'cbChemical' => $cb_chemical, 'cbChips' => $cb_chips, 'cbMetal' => $cb_metal,  
            'cbSand' => $cb_sand, 'cbMSand' => $cb_m_sand, 'cbDiesel' => $cb_diesel,
            'lCement' => $limitCement, 'lChemical' => $limitChemical, 'lChips' => $limitChips, 'lMetal' => $limitMetal,  
            'lSand' => $limitSand, 'lMSand' => $limitM_Sand, 'lDiesel' => $limitDiesel,
            'reorderDetails' => $repo_reorderDetails, 'im'=>$userProfilePics, 
            'salesOrderDet' => $salesOrderDet, 'flag1' => true, 'flag2' => false)); 
        }
        
         
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
        
    }
       
    public function stockUsageAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $usedDate = $request->get('calDate');
            $qty = $request->get('quantity');
            $rmType = $request->get('type');
            
            $month;
            
            $full_url = explode('-', $usedDate);
            $id = $full_url[1];
            $year = $full_url[0];
            
            if($id == "01"){
                $month = "January";
            }
            else if($id == "02"){
                $month = "February";
            }
            else if($id == "03"){
                $month = "March";
            }
            else if($id == "04"){
                $month = "April";
            }
            else if($id == "05"){
                $month = "May";
            }
            else if($id == "06"){
                $month = "June";
            }
            else if($id == "07"){
                $month = "July";
            }
            else if($id == "08"){
                $month = "August";
            }
            else if($id == "09"){
               $month = "September"; 
            }
            else if($id == "10"){
                $month = "October";
            }
            else if($id == "11"){
                $month = "November";
            }
            else if($id == "12"){
               $month = "December"; 
            }
            else{
                
            }


            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            if ($rmType == "Cement") {
                $querry = $con->prepare("SELECT * FROM cement ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];

                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM cement WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE cement SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();

   
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Cement();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }
                $querry = $con->prepare("SELECT * FROM cement WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "Chemical") {
                $querry = $con->prepare("SELECT * FROM chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Chemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "Sand") {
                $querry = $con->prepare("SELECT * FROM sand ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM sand WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE sand SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Sand();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM sand WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "Metal") {
                $querry = $con->prepare("SELECT * FROM metal ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM metal WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE metal SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Metal();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM metal WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "Chips") {
                $querry = $con->prepare("SELECT * FROM chips ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM chips WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE chips SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Chips();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM chips WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "Diesel") {
                $querry = $con->prepare("SELECT * FROM diesel ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM diesel WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE diesel SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Diesel();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM diesel WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            if ($rmType == "M-Sand") {
                $querry = $con->prepare("SELECT * FROM m_sand ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM m_sand WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (int) $val[0]['stock_used'] + (int) $qty;
                    $closeBal = (int) $val[0]['opening_balance'] + (int) $val[0]['stock_purchased'] - (int) $val1;

                    $querry = $con->prepare("UPDATE m_sand SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\MSand();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((int) $nRes[0]['clossing_balance'] - (int) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }

                $querry = $con->prepare("SELECT * FROM m_sand WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

                

            }
            return $this->redirect($this->generateUrl('purAndinvtMgt_PO', array('url' => $url)));
        } else {

        }
    }

    public function inventoryReorderMgtAction($url, Request $request){
        $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
            $nReorderLevel = $request->get('quantity');
            
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            
            $repo_orders = $em->getRepository('sepBundle:ReorderLevels');
            $reorderEntries = $repo_orders->findAll();

            for ($i = 0; $i < sizeof($reorderEntries); $i++) {
                if (isset($_POST[$reorderEntries[$i]->getReorderId()])) {
                    $id = $reorderEntries[$i]->getReorderId();
                    $querry = $con->prepare("update reorder_levels set reorder_level = :val where reorder_id = :rm ");
                    $querry->bindValue('val', $nReorderLevel);
                    $querry->bindValue('rm', $id);
                    $querry->execute();
                }
            }           
            
            return $this->redirect($this->generateUrl('purAndinvtMgt_PO', array('url' => $url)));
          
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }
    
    
    public function submitPurchaseAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == 'POST') {
            $supplier_name = $request->get('supName');
            $supplier_type = $request->get('type');
            $doId = $request->get('deliveryOrderID');
            $formDate = $request->get('calDate');
            $pur_amount = $request->get('qty');
            $cost = $request->get('cost');
            
            $month;
            
            $full_url = explode('-', $formDate);
            $id = $full_url[1];
            $year = $full_url[0];
            
            if($id == "01"){
                $month = "January";
            }
            else if($id == "02"){
                $month = "February";
            }
            else if($id == "03"){
                $month = "March";
            }
            else if($id == "04"){
                $month = "April";
            }
            else if($id == "05"){
                $month = "May";
            }
            else if($id == "06"){
                $month = "June";
            }
            else if($id == "07"){
                $month = "July";
            }
            else if($id == "08"){
                $month = "August";
            }
            else if($id == "09"){
               $month = "September"; 
            }
            else if($id == "10"){
                $month = "October";
            }
            else if($id == "11"){
                $month = "November";
            }
            else if($id == "12"){
               $month = "December"; 
            }
            else{
                
            }
            
            $full_url = explode('.', $url);
            $supId = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $user_repo = $em->getRepository('sepBundle:UserLogin');
            $user = $user_repo->findOneBy(array('userId' => $supId));

            $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $userDetailRepo->findOneBy(array('u' => $user));

            $date = new \DateTime($formDate);
            $strDate = $date->format('Y-m-d');

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
                                   
            $querry = $con->prepare("SELECT DISTINCT sup_type FROM supplier_details");
            $querry->execute();
            $typeDet = $querry->fetchAll();

            $qur = $con->prepare("SELECT user_id FROM supplier_details WHERE sup_company_name = :comp");
            $qur->bindValue('comp', $supplier_name);
            $qur->execute();
            $res = $qur->fetchAll();

            $id = $res[0]['user_id'];

            $querry = $con->prepare("SELECT * FROM orders WHERE (amount-purchased_amount)>= :qty AND pur_date= :dt AND sup_id= :sId");
            $querry->bindValue('qty', $pur_amount);
            $querry->bindValue('dt', $formDate);
            $querry->bindValue('sId', $id);
            $querry->execute();
            $result = $querry->fetchAll();

            //taken from purchaseRMAction to render the page with flag blocks
            $repo_orders = array();

            $querry = $con->prepare('SELECT * FROM orders ORDER BY date DESC');
            $querry->execute();
            $repo_orders = $querry->fetchAll();

            $repo_companies = $em->getRepository('sepBundle:SupplierDetails');
            $companies = $repo_companies->findAll();

            $testDate = date('Y-m-d');

            //Cement
            $querry = $con->prepare('SELECT * FROM cement ORDER BY date DESC');
            $querry->execute();
            $repo_cement = $querry->fetchAll();

            //Chemical
            $querry = $con->prepare('SELECT * FROM chemical ORDER BY date DESC');
            $querry->execute();
            $repo_chemical = $querry->fetchAll();

            //Chips
            $querry = $con->prepare('SELECT * FROM chips ORDER BY date DESC');
            $querry->execute();
            $repo_chips = $querry->fetchAll();

            //Sand
            $querry = $con->prepare('SELECT * FROM sand ORDER BY date DESC');
            $querry->execute();
            $repo_sand = $querry->fetchAll();

            //Diesel
            $querry = $con->prepare('SELECT * FROM diesel ORDER BY date DESC');
            $querry->execute();
            $repo_diesel = $querry->fetchAll();

            //Metal
            $querry = $con->prepare('SELECT * FROM metal ORDER BY date DESC');
            $querry->execute();
            $repo_metal = $querry->fetchAll();

            //M_Sand
            $querry = $con->prepare('SELECT * FROM m_sand ORDER BY date DESC');
            $querry->execute();
            $repo_m_sand = $querry->fetchAll();

            if ($result == []) {

                return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => true, 'flag1' => false, 'flag2' => false, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 'user'=>$user, 'userDetails' => $userDetails, 'types' => $typeDet));
            } else {
                $res = array($result[0]['order_id'], "$supplier_name", $result[0]['amount'], $result[0]['purchased_amount'], $result[0]['date']);
                $extra = (int) $res[2] - (int) $res[3];
                $new_pur_amount = (int) $res[3] + (int) $pur_amount;
                
                if ($extra >= (int) $pur_amount) {
                    $querry = $con->prepare("UPDATE orders SET purchased_amount = :pur_amount WHERE pur_date = :date  AND sup_id = :id");
                    $querry->bindValue('pur_amount', $new_pur_amount);
                    $querry->bindValue('date', $formDate);
                    $querry->bindValue('id', $id);
                    $querry->execute();

                    if ($supplier_type == "Cement") {
                        $qur = $con->prepare("SELECT date FROM cement ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM cement WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $cement_amount = $qur->fetchAll();
                            $opnBal = $cement_amount[0]['opening_balance'];
                            $usedBal = $cement_amount[0]['stock_used'];
                            $kk = $cement_amount[0]['stock_purchased'];
                            $prevCost = $cement_amount[0]['net_cost'];
                            $new_cement_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_cement_amount - (int) $usedBal;


                            $querry = $con->prepare("UPDATE cement SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_cement_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Cement
                            $querry = $con->prepare('SELECT * FROM cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM cement WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_cement = $qur->fetchAll();
                            $ob = (int) $clossing_cement[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;


                            $entry = new \sepBundle\Entity\Cement();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();

                            //Cement
                            $querry = $con->prepare('SELECT * FROM cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        }
                    }
                    if ($supplier_type == "Sand") {
                        $qur = $con->prepare("SELECT date FROM sand ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM sand WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $sand_amount = $qur->fetchAll();
                            $opnBal = $sand_amount[0]['opening_balance'];
                            $usedBal = $sand_amount[0]['stock_used'];
                            $kk = $sand_amount[0]['stock_purchased'];
                            $prevCost = $sand_amount[0]['net_cost'];
                            $new_sand_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_sand_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE sand SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_sand_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Sand
                            $querry = $con->prepare('SELECT * FROM sand ORDER BY date DESC');
                            $querry->execute();
                            $repo_sand = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM sand WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_sand = $qur->fetchAll();
                            $ob = (int) $clossing_sand[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\Sand();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //Sand
                            $querry = $con->prepare('SELECT * FROM sand ORDER BY date DESC');
                            $querry->execute();
                            $repo_sand = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                    }
                    if ($supplier_type == "Chemical") {
                        $qur = $con->prepare("SELECT date FROM chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_chemical_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (int) $clossing_chemical[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\Chemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //Chemical
                            $querry = $con->prepare('SELECT * FROM chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                    }
                    if ($supplier_type == "Chips") {
                        $qur = $con->prepare("SELECT date FROM chips ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM chips WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chips_amount = $qur->fetchAll();
                            $opnBal = $chips_amount[0]['opening_balance'];
                            $kk = $chips_amount[0]['stock_purchased'];
                            $usedBal = $chips_amount[0]['stock_used'];
                            $prevCost = $chips_amount[0]['net_cost'];
                            $new_chips_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_chips_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE chips SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chips_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chips
                            $querry = $con->prepare('SELECT * FROM chips ORDER BY date DESC');
                            $querry->execute();
                            $repo_chips = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM chips WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chips = $qur->fetchAll();
                            $ob = (int) $clossing_chips[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\Chips();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //Chips
                            $querry = $con->prepare('SELECT * FROM chips ORDER BY date DESC');
                            $querry->execute();
                            $repo_chips = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                    }
                    if ($supplier_type == "Diesel") {
                        $qur = $con->prepare("SELECT date FROM diesel ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM diesel WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $diesel_amount = $qur->fetchAll();
                            $opnBal = $diesel_amount[0]['opening_balance'];
                            $kk = $diesel_amount[0]['stock_purchased'];
                            $usedBal = $diesel_amount[0]['stock_used'];
                            $prevCost = $diesel_amount[0]['net_cost'];
                            $new_diesel_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_diesel_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE diesel SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_diesel_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Diesel
                            $querry = $con->prepare('SELECT * FROM diesel ORDER BY date DESC');
                            $querry->execute();
                            $repo_diesel = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM diesel WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_diesel = $qur->fetchAll();
                            $ob = (int) $clossing_diesel[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\Diesel();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //Diesel
                            $querry = $con->prepare('SELECT * FROM diesel ORDER BY date DESC');
                            $querry->execute();
                            $repo_diesel = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                    }
                    if ($supplier_type == "M-Sand") {
                        $qur = $con->prepare("SELECT date FROM m_sand ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM m_sand WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $m_sand_amount = $qur->fetchAll();
                            $opnBal = $m_sand_amount[0]['opening_balance'];
                            $kk = $m_sand_amount[0]['stock_purchased'];
                            $usedBal = $m_sand_amount[0]['stock_used'];
                            $prevCost = $m_sand_amount[0]['net_cost'];
                            $new_m_sand_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_m_sand_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE m_sand SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_m_sand_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //M_Sand
                            $querry = $con->prepare('SELECT * FROM m_sand ORDER BY date DESC');
                            $querry->execute();
                            $repo_m_sand = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM m_sand WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_m_sand = $qur->fetchAll();
                            $ob = (int) $clossing_m_sand[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\MSand();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //MSand
                            $querry = $con->prepare('SELECT * FROM m_sand ORDER BY date DESC');
                            $querry->execute();
                            $repo_m_sand = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                    }
                    if ($supplier_type == "Metal") {
                        $qur = $con->prepare("SELECT date FROM metal ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM metal WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $metal_amount = $qur->fetchAll();
                            $opnBal = $metal_amount[0]['opening_balance'];
                            $kk = $metal_amount[0]['stock_purchased'];
                            $usedBal = $metal_amount[0]['stock_used'];

                            $prevCost = $metal_amount[0]['net_cost'];
                            $new_metal_amount = (int) $kk + (int) $pur_amount;
                            $new_net_cost = (int) $prevCost + (int) $cost;
                            $closeBal = (int) $opnBal + (int) $new_metal_amount - (int) $usedBal;

                            $querry = $con->prepare("UPDATE metal SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_metal_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Metal
                            $querry = $con->prepare('SELECT * FROM metal ORDER BY date DESC');
                            $querry->execute();
                            $repo_metal = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM metal WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_metal = $qur->fetchAll();
                            $ob = (int) $clossing_metal[0]['clossing_balance'];
                            $pa = (int) $pur_amount;
                            $su = (int) 0;
                            $newCost = (int) $cost;
                            $cb = (int) $ob + (int) $pa;

                            $entry = new \sepBundle\Entity\Metal();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setNetCost($newCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);

                            $em->persist($entry);
                            $em->flush();

                            //Metal
                            $querry = $con->prepare('SELECT * FROM metal ORDER BY date DESC');
                            $querry->execute();
                            $repo_metal = $querry->fetchAll();

                           
                        }
                    }
                    
                    $pur_fig = (int)$pur_amount;
                    $pur_cos = (int)$cost;
                    
                    $pur_entry = new \sepBundle\Entity\Purchases();
                    $pur_entry->setDeliveryOrderId($doId);
                    $pur_entry->setMaterial($supplier_type);
                    $pur_entry->setSupplierName($supplier_name);
                    $pur_entry->setDate($date);
                    $pur_entry->setAmount($pur_fig);
                    $pur_entry->setCost($pur_cos);

                    $em->persist($pur_entry);
                    $em->flush();
                    
                    return $this->redirect($this->generateUrl('purAndinvtMgt_PO', array('url' => $url)));
                    //return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 'user'=>$user, 'userDetails' => $userDetails, 'types' => $typeDet));
                    
                } else {
                   
                }

            }
        } else {
         return $this->redirect($this->generateUrl('sep_homepage'));   
        }
        }
        else{
           return $this->redirect($this->generateUrl('sep_homepage')); 
        }

    }
    
    public function balAnalysisRMAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            
        $full_url = explode('.', $url);
        $supId = $full_url[1];
        
        $em = $this->getDoctrine()->getEntityManager();
        $user_repo = $em->getRepository('sepBundle:UserLogin');
        $user = $user_repo->findOneBy(array('userId' => $supId));

        $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
        $userDetails = $userDetailRepo->findOneBy(array('u' => $user));
        
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $repo_orders = array();

        $querry = $con->prepare('SELECT * FROM orders ORDER BY date DESC');
        $querry->execute();
        $repo_orders = $querry->fetchAll();
        //$compNames = $this->getSupplierCompany($repo_orders);
        $querry = $con->prepare("SELECT DISTINCT sup_type FROM supplier_details");
        $querry->execute();
        $typeDet = $querry->fetchAll();

        $repo_companies = $em->getRepository('sepBundle:SupplierDetails');
        $companies = $repo_companies->findAll();

        $testDate = date('Y-m-d');
        //Cement
        $querry = $con->prepare('SELECT * FROM cement ORDER BY date DESC');
        $querry->execute();
        $repo_cement = $querry->fetchAll();

        //Chemical
        $querry = $con->prepare('SELECT * FROM chemical ORDER BY date DESC');
        $querry->execute();
        $repo_chemical = $querry->fetchAll();

        //Chips
        $querry = $con->prepare('SELECT * FROM chips ORDER BY date DESC');
        $querry->execute();
        $repo_chips = $querry->fetchAll();

        //Sand
        $querry = $con->prepare('SELECT * FROM sand ORDER BY date DESC');
        $querry->execute();
        $repo_sand = $querry->fetchAll();

        //Diesel
        $querry = $con->prepare('SELECT * FROM diesel ORDER BY date DESC');
        $querry->execute();
        $repo_diesel = $querry->fetchAll();

        //Metal
        $querry = $con->prepare('SELECT * FROM metal ORDER BY date DESC');
        $querry->execute();
        $repo_metal = $querry->fetchAll();

        //M_Sand
        $querry = $con->prepare('SELECT * FROM m_sand ORDER BY date DESC');
        $querry->execute();
        $repo_m_sand = $querry->fetchAll();

        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:OperatorProfile:balanceOfRMPO.html.twig', array('url' => $url, 
            'names' => $companies, 'toDate' => $testDate, 'types' => $typeDet, 
            'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 
            'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 
            'user'=>$user, 'userDetails' => $userDetails,  'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
        return $this->render('sepBundle:OperatorProfile:balanceOfRMPO.html.twig', array('url' => $url, 
            'names' => $companies, 'toDate' => $testDate, 'types' => $typeDet, 
            'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 
            'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 
            'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
        }

        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }
    
    public function orderRMAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            
        $full_url = explode('.', $url);
        $supId = $full_url[1];
        
        $em = $this->getDoctrine()->getEntityManager();
        $user_repo = $em->getRepository('sepBundle:UserLogin');
        $user = $user_repo->findOneBy(array('userId' => $supId));

        $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
        $userDetails = $userDetailRepo->findOneBy(array('u' => $user));

        $em = $this->getDoctrine()->getEntityManager();
        $repo_login = $em->getRepository('sepBundle:UserLogin');
        $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $repo_orders = array();
        $testDate = date('Y-m-d');

        $querry = $con->prepare('SELECT * FROM orders  
                                 ORDER BY date DESC');
        $querry->execute();
        $repo_orders = $querry->fetchAll();
        
        $querry = $con->prepare('SELECT * FROM orders  
                                 ORDER BY date DESC');
        $querry->execute();
        $repo_orders_history = $querry->fetchAll();

        $querry = $con->prepare("SELECT DISTINCT sup_type FROM supplier_details");
        $querry->execute();
        $typeDet = $querry->fetchAll();

        //$compNames = $this->getSupplierCompany($repo_orders);
        $querry = $con->prepare("SELECT COUNT(ORDER_id) FROM orders");
        $querry->execute();
        $temp_order_count = $querry->fetchAll();
        $order_count = (int) $temp_order_count[0]["COUNT(ORDER_id)"];
        $loops = (int) ($order_count / (int) 25);
        $loops = $loops + (int) 1;
        $start = (int) 1;
        $end = (int) 0;
        $count = (int) 0;
        $ranges = array();
        for ($i = 0; $i < $loops; $i++) {
            $count++;
            $end = $start + (int) 24;
            if ($count == $loops) {
                $ranges[$i] = "$start to final entry";
            } else {
                $ranges[$i] = "$start to $end";
            }
            $start = $end + (int) 1;
        }
        $querry = $con->prepare('SELECT * FROM orders WHERE purchased_amount = 0 ORDER BY date DESC');
        $querry->execute();
        $repo_cancel_orders = $querry->fetchAll();
        
        $repo_companies = $em->getRepository('sepBundle:SupplierDetails');
        $companies = $repo_companies->findAll();
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:OperatorProfile:orderPlacementPO.html.twig', array('url' => $url, 'orders' => $repo_orders, 'cancelOrders' => $repo_cancel_orders, 'orderHistory' => $repo_orders_history, 
                'names' => $companies, 'ranges' => $ranges, 'types' => $typeDet, 'toDate' => $testDate, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
            return $this->render('sepBundle:OperatorProfile:orderPlacementPO.html.twig', array('url' => $url, 'orders' => $repo_orders, 'cancelOrders' => $repo_cancel_orders, 'orderHistory' => $repo_orders_history, 
                'names' => $companies, 'ranges' => $ranges, 'types' => $typeDet, 'toDate' => $testDate, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false)); 
        }
                
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }     
    }

    public function placeOrderAction(Request $request, $url) {
     
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == 'POST') {
            $type = $request->get('type');
            $name = $request->get('supName');
            $amount = $request->get('quantity');
            $or_date = $request->get('calOrderDate');
            $pur_date = $request->get('calPurchaseDate');

            $order_date = new \DateTime($or_date);
            $purchase_date = new \DateTime($pur_date);

            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();

            $querry = $connection->prepare("SELECT user_id FROM supplier_details WHERE sup_company_name = :name AND sup_type = :type");
            $querry->bindValue('name', $name);
            $querry->bindValue('type', $type);
            $querry->execute();
            $results = $querry->fetchAll();
            $id = $results[0]['user_id'];

            //  return $this->render('sepBundle:Default:index.html.twig', array('dd'=>$name));

            $repo_user = $em->getRepository('sepBundle:UserLogin');
            $user = $repo_user->findOneBy(array('userId' => $id));
            $status = 0;

            $order_access = new \sepBundle\Entity\Orders();

            $order_access->setSup($user);
            $order_access->setAmount($amount);
            $order_access->setDate($order_date);
            $order_access->setPurDate($purchase_date);
            $order_access->setStatus($status);
            $order_access->setPurchasedAmount($status);

            $em->persist($order_access);
            $em->flush();

            return $this->redirect($this->generateUrl('orderRM_PO', array('url' => $url)));
        } 
        else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    public function cancelOrderAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $repo_orders = $em->getRepository('sepBundle:Orders');
            $orders = $repo_orders->findAll();
            $con = $em->getConnection();
            for ($i = 0; $i < sizeof($orders); $i++) {
                if (isset($_POST[$orders[$i]->getOrderId()])) {
                    $id = $orders[$i]->getOrderId();
                    $qur = $con->prepare("DELETE FROM orders WHERE order_id = :id");
                    $qur->bindValue('id', $id);
                    $qur->execute();
                }
            }
            return $this->redirect($this->generateUrl('orderRM_PO', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
          return $this->redirect($this->generateUrl('sep_homepage'));  
        }

    }
    
    public function submitEditDetailsAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
        if ($request->getMethod() == "POST") {
            $user_f_name = $request->get('FName');
            $user_l_name = $request->get('LName');
            $user_contact = $request->get('Contact');

            $em = $this->getDoctrine()->getEntityManager();
            $repo_login = $em->getRepository('sepBundle:UserLogin');

            $full_url = explode(".", $url);
            $u_id = $full_url[1];

            $user = $repo_login->findOneBy(array('userId' => $u_id));

            $emo = $this->getDoctrine()->getEntityManager();
            $connection = $emo->getConnection();
            $repo_details = $em->getRepository('sepBundle:UserDetails');
            $testUser = $repo_details->findOneBy(array('u' => $user));

            if (!(is_null($user_f_name))) {
                $tempStatement = $connection->prepare("UPDATE user_details SET first_name = :f_name WHERE u_id = :u_id");
                $tempStatement->bindValue('f_name', $user_f_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($user_f_name == "") {
                $user_f_name = $testUser->getFirstName();
                $tempStatement = $connection->prepare("UPDATE user_details SET first_name = :f_name WHERE u_id = :u_id");
                $tempStatement->bindValue('f_name', $user_f_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if (!(is_null($user_l_name))) {
                $tempStatement = $connection->prepare("UPDATE user_details SET last_name = :l_name WHERE u_id = :u_id");
                $tempStatement->bindValue('l_name', $user_l_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($user_l_name == "") {
                $user_l_name = $testUser->getLastName();
                $tempStatement = $connection->prepare("UPDATE user_details SET last_name = :l_name WHERE u_id = :u_id");
                $tempStatement->bindValue('l_name', $user_l_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if (!(is_null($user_contact))) {
                $tempStatement = $connection->prepare("UPDATE user_details SET contact_number = :contact WHERE u_id = :u_id");
                $tempStatement->bindValue('contact', $user_contact);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($user_contact == "") {
                $user_contact = $testUser->getContactNumber();
                $tempStatement = $connection->prepare("UPDATE user_details SET contact_number = :contact WHERE u_id = :u_id");
                $tempStatement->bindValue('contact', $user_contact);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }
            $testUser->setFirstName($user_f_name);
            $testUser->setLastName($user_l_name);
            $testUser->setContactNumber((int) $user_contact);

            return $this->redirect($this->generateUrl('profile_home_operator', array('url' => $url)));
            //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => true, 'url' => $url, 'userDetails' => $testUser, 'user' => $user));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }            
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
            
        }

    }

    public function changePasswordAction(Request $request, $url) {
                $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $current = $request->get('current_password');
            $new = $request->get('new_password');
            
            $en_current = md5($current);
            $en_new = md5($new);

            $full_url = explode(".", $url);
            $u_id = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();



            $querry = $connection->prepare("SELECT password FROM user_login WHERE user_id = :id");
            $querry->bindValue('id', $u_id);
            $querry->execute();
            $results = $querry->fetchAll();

            $db_pass = $results[0]['password'];

            $repo_user = $em->getRepository('sepBundle:UserLogin');
            $user = $repo_user->findOneby(array('userId' => $u_id));
            $repo_details = $em->getRepository('sepBundle:UserDetails');
            $user_details = $repo_details->findOneBy(array('u' => $user));
            $name = $user->getUsername();

            if ($db_pass == $en_current) {

                $querry = $connection->prepare("UPDATE user_login SET password = :n_pass WHERE user_id = :id");
                $querry->bindValue('n_pass', $en_new);
                $querry->bindValue('id', $u_id);
                $querry->execute();

                return $this->redirect($this->generateUrl('profile_home_operator', array('url' => $url)));
                //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => false, 'flag1' => true, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 'userDetails' => $user_details, 'user' => $user));
            } else {
                return $this->redirect($this->generateUrl('sep_homepage'));
                //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => true, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => false, 'url' => $url, 'userDetails' => $user_details, 'user' => $user));
            }
        }
        else{
           return $this->redirect($this->generateUrl('sep_homepage')); 
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    public function changeUsernameAction($url, Request $request) {
                $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
                    if ($request->getMethod() == "POST") {
            $current = $request->get('current_username');
            $new = $request->get('new_username');

            $full_url = explode(".", $url);
            $u_id = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $connection = $em->getConnection();

            $querry = $connection->prepare("SELECT username FROM user_login WHERE user_id = :id");
            $querry->bindValue('id', $u_id);
            $querry->execute();
            $results = $querry->fetchAll();

            $db_u_name = $results[0]['username'];

            $repo_user = $em->getRepository('sepBundle:UserLogin');
            $user = $repo_user->findOneby(array('userId' => $u_id));
            $repo_details = $em->getRepository('sepBundle:UserDetails');
            $user_details = $repo_details->findOneBy(array('u' => $user));

            if ($db_u_name == $current) {

                $querry = $connection->prepare("UPDATE user_login SET username = :n_u_name WHERE user_id = :id");
                $querry->bindValue('n_u_name', $new);
                $querry->bindValue('id', $u_id);
                $querry->execute();

                $querry = $connection->prepare("SELECT username FROM user_login WHERE user_id = :id");
                $querry->bindValue('id', $u_id);
                $querry->execute();
                $res = $querry->fetchAll();
                $user->setUsername($new);

                // $name = $res[0]['username'];
                return $this->redirect($this->generateUrl('profile_home_user', array('url' => $url)));
                //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => true, 'flag4' => false, 'url' => $url, 'userDetails' => $user_details, 'user' => $user));
            } else {
                $querry = $connection->prepare("SELECT username FROM user_login WHERE user_id = :id");
                $querry->bindValue('id', $u_id);
                $querry->execute();
                $res = $querry->fetchAll();

                $user_details = $repo_details->findOneBy(array('u' => $user));
                //  $name = $res[0]['username'];
                return $this->redirect($this->generateUrl('profile_home_operator', array('url' => $url)));
                //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => true, 'flag3' => false, 'flag4' => false, 'url' => $url, 'userDetails' => $user_details, 'user' => $user));
            }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }
    
    
    public function imageUploadOperatorAction($url, Request $request){
        $full_url = explode('.', $url);
        $userId = $full_url[1];
        
        $full_url = explode(".", $url);
        $id = $full_url[1];
        
        if(isset($_POST['sumit'])){
            if(getimagesize($_FILES['image']['tmp_name'])== FALSE){
                echo "Please Select an Image";
            }
            else{
               $image = addslashes ($_FILES['image']['tmp_name']);
               $name = addslashes ($_FILES['image']['name']);
               $image = file_get_contents($image);
               $image = base64_encode($image);
               $this->saveImage($name, $image, $userId);
               
            }
            
            $userProfilePics = $this->displayImage($url);
            
            $em = $this->getDoctrine()->getEntityManager();
            $user_repo = $em->getRepository('sepBundle:UserLogin');
            $user = $user_repo->findOneBy(array('userId' => $id));
            $userLoginDetails = $user_repo->findAll();

            $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $userDetailRepo->findOneBy(array('u' => $user));

        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:OperatorProfile:plantOperatorProfile.html.twig', array('flag1' => false, 'flag2' => true,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user,
            'ulogin'=>$userLoginDetails, 'im'=> $userProfilePics)); 
        }
        
        else{
            return $this->render('sepBundle:OperatorProfile:plantOperatorProfile.html.twig', array('flag1' => true, 'flag2' => false,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 
            'ulogin'=>$userLoginDetails, 'im'=> $userProfilePics)); 
        }
                
        }
    }
    
    
    public function saveImage($name, $image, $userId){
        
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $queery = $con->prepare("update user_details set name = :nm, image = :im where u_id = :uid");
        $queery->bindValue('nm', $name);
        $queery->bindValue('im', $image);
        $queery->bindValue('uid', $userId);
        $queery->execute();
        
    }
    
    public function displayImage($url){
        $full_url = explode('.', $url);
        $userId = $full_url[1];
        
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $queery = $con->prepare("select image from user_details where u_id = :uid");
        $queery->bindValue('uid', $userId);        
        $queery->execute();
        $res = $queery->fetchAll();        
 
        return $res;
        
    }
    
}
