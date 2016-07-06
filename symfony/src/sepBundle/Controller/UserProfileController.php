<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;

class UserProfileController extends Controller {

    //Done
    public function loginUserAction($url, Request $request) {
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
        $allUserDetails = $userDetailRepo->findAll();

        $supDetailRepo = $em->getRepository('sepBundle:SupplierDetails');
        $supDetails = $supDetailRepo->findAll();

        $userDRepo = $em->getRepository('sepBundle:UserDetails');
        $userD = $userDRepo->findAll();
        

        $con = $em->getConnection();
        $qur = $con->prepare("SELECT distinct customer_name, contact_num FROM sales");
        $qur->execute();
        $customerDetails = $qur->fetchAll();
        

        $con = $em->getConnection();
        $qur = $con->prepare("SELECT * FROM user_login WHERE status = '0'");
        $qur->execute();
        $res = $qur->fetchAll();
        
        $userProfilePics = $this->displayImage($url);
        //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $userProfilePics));
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag1' => false, 'flag2' => true,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 
            'aud'=>$allUserDetails, 'ulogin'=>$userLoginDetails, 'cusd'=>$customerDetails, 'ud' => $userD, 'im'=> $userProfilePics)); 
        }
        
        else{
            return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag1' => true, 'flag2' => false,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 
            'aud'=>$allUserDetails, 'ulogin'=>$userLoginDetails, 'cusd'=>$customerDetails, 'ud' => $userD, 'im'=> $userProfilePics)); 
        }
        
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }

    //Done
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

            return $this->redirect($this->generateUrl('profile_home_user', array('url' => $url)));
            //return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'flag4' => true, 'url' => $url, 'userDetails' => $testUser, 'user' => $user));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }            
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
            
        }

    }

    //Done
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

                return $this->redirect($this->generateUrl('profile_home_user', array('url' => $url)));
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

    //Done
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
                return $this->redirect($this->generateUrl('profile_home_user', array('url' => $url)));
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

    //Done
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
            return $this->render('sepBundle:Profile:orderPlacement.html.twig', array('url' => $url, 'orders' => $repo_orders, 'cancelOrders' => $repo_cancel_orders, 'orderHistory' => $repo_orders_history, 
                'names' => $companies, 'ranges' => $ranges, 'types' => $typeDet, 'toDate' => $testDate, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
            return $this->render('sepBundle:Profile:orderPlacement.html.twig', array('url' => $url, 'orders' => $repo_orders, 'cancelOrders' => $repo_cancel_orders, 'orderHistory' => $repo_orders_history, 
                'names' => $companies, 'ranges' => $ranges, 'types' => $typeDet, 'toDate' => $testDate, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false)); 
        }
                
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }     
    }

    //Done
    public function selectSupplierAction(Request $request, $url) {
        if ($request->getMethod() == 'POST') {
            $type = $request->get('type');
            $listOfSupplier = $this->getOrderSup($type);

            $rr = $listOfSupplier;
            return new Response(json_encode($rr));
        }
    }

    //Done
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

            return $this->redirect($this->generateUrl('orderRM', array('url' => $url)));
        } 
        else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    //Done
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
            return $this->redirect($this->generateUrl('orderRM', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
          return $this->redirect($this->generateUrl('sep_homepage'));  
        }

    }

    //Done
    public function userMessageAction($url, Request $request) {
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

        $sup_details = $repo_details->findAll();
        $inboxMessages = $this->loadInbox();
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:Profile:message.html.twig', array('url' => $url, 
                'myMessage' => $inboxMessages, 'user'=>$user, 'userDetails' => $userDetails, 
                'sd' => $sup_details, 'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));  
        }
        
        else{
            return $this->render('sepBundle:Profile:message.html.twig', array('url' => $url, 
                'myMessage' => $inboxMessages, 'user'=>$user, 'userDetails' => $userDetails, 
                'sd' => $sup_details, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false)); 
        }

        
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }


    //Done
    public function composeMessageAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $full_url = explode('.', $url);

            $userId = $full_url[1];

            $compName = $request->get('sup_name');
            $subject = $request->get('subject');
            $body = $request->get('message');
            $todayDate = date('Y-m-d H:i:s');
            $sendDate = new \DateTime($todayDate);
            $repStatus = 1;

            $em = $this->getDoctrine()->getEntityManager();
            $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            $repo_login = $em->getRepository('sepBundle:UserLogin');
            $sup_details = $repo_details->findOneBy(array('supCompanyName' => $compName));
            $supplier = $sup_details->getUser();

                $comp_message = new \sepBundle\Entity\Messaging();
                $comp_message->setSupplier($supplier);
                $comp_message->setMessageHeaderByCompany($subject);
                $comp_message->setMessageByCompany($body);
                $comp_message->setSendTime($sendDate);
                $comp_message->setMessageStatus($repStatus);

                $em->persist($comp_message);
                $em->flush();

            $inboxMessages = $this->loadInbox();
            $sup_details = $repo_details->findAll();
           
            return $this->redirect($this->generateUrl('user_message', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }
    
    //Done
    public function userReplyMessageAction($url, Request $request){
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $full_url = explode('.', $url);

            $userId = $full_url[1];
            $msg = $request->get('messageId');
            $compName = $request->get('sup_name');
            $subject = $request->get('subject');
            $body = $request->get('message');
            $todayDate = date('Y-m-d H:i:s');
            $sendDate = new \DateTime($todayDate);
            $repStatus = 1;

            $em = $this->getDoctrine()->getEntityManager();
            $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            $repo_login = $em->getRepository('sepBundle:UserLogin');
            $sup_details = $repo_details->findOneBy(array('supCompanyName' => $compName));
            $supplier = $sup_details->getUser();
            
            $con = $em->getConnection();
            $qur = $con->prepare('UPDATE messaging SET message_header_by_company = :hdMsg , message_by_company = :bdyMsg , reply_time = :ddtt WHERE message_id = :id');
            $qur->bindValue('hdMsg', $subject);
            $qur->bindValue('bdyMsg', $body);
            $qur->bindValue('ddtt', $todayDate);
            $qur->bindValue('id', $msg);
            $qur->execute();


            $inboxMessages = $this->loadInbox();
            $sup_details = $repo_details->findAll();
            
            return $this->redirect($this->generateUrl('user_message', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }


    //Done
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
        
        //Cement1
        $querry = $con->prepare('SELECT * FROM holcim_extra_cement ORDER BY date DESC');
        $querry->execute();
        $repo_holcim_extra_cement = $querry->fetchAll();
        
        //Cement2
        $querry = $con->prepare('SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC');
        $querry->execute();
        $repo_holcim_ready_flow_plus_cement = $querry->fetchAll();
        
        //Cement3
        $querry = $con->prepare('SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC');
        $querry->execute();
        $repo_ordinary_portland_cement_cement = $querry->fetchAll();

        //Chemical1
        $querry = $con->prepare('SELECT * FROM adcrete_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_adcrete_chemical = $querry->fetchAll();
        
        //Chemical2
        $querry = $con->prepare('SELECT * FROM pozzolith300r_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_pozzolith300r_chemical = $querry->fetchAll();
        
        //Chemical3
        $querry = $con->prepare('SELECT * FROM rheobuild561_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_rheobuild561_chemical = $querry->fetchAll();
        
        //Chemical4
        $querry = $con->prepare('SELECT * FROM rheobuild1000_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_rheobuild1000_chemical = $querry->fetchAll();
        
        //Chemical5
        $querry = $con->prepare('SELECT * FROM supercrete_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_supercrete_chemical = $querry->fetchAll();
        
        //Chemical6
        $querry = $con->prepare('SELECT * FROM supercrete_hs_chemical ORDER BY date DESC');
        $querry->execute();
        $repo_supercrete_hs_chemical = $querry->fetchAll();

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
        return $this->render('sepBundle:Profile:balanceOfRM.html.twig', array('url' => $url, 
            'names' => $companies, 'toDate' => $testDate, 'types' => $typeDet, 
            'holcim_extra_cement' => $repo_holcim_extra_cement, 'holcim_ready_flow_plus_cement' => $repo_holcim_ready_flow_plus_cement, 'ordinary_portland_cement' => $repo_ordinary_portland_cement_cement, 
            'adcrete_chemical' => $repo_adcrete_chemical, 'pozzolith300r_chemical' => $repo_pozzolith300r_chemical, 'rheobuild561_chemical' => $repo_rheobuild561_chemical,
            'rheobuild1000_chemical' => $repo_rheobuild1000_chemical, 'supercrete_chemical' => $repo_supercrete_chemical, 'supercrete_hs_chemical' => $repo_supercrete_hs_chemical,
            'chips' => $repo_chips, 
            'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 
            'user'=>$user, 'userDetails' => $userDetails,  'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
        return $this->render('sepBundle:Profile:balanceOfRM.html.twig', array('url' => $url, 
            'names' => $companies, 'toDate' => $testDate, 'types' => $typeDet, 
            'holcim_extra_cement' => $repo_holcim_extra_cement, 'holcim_ready_flow_plus_cement' => $repo_holcim_ready_flow_plus_cement, 'ordinary_portland_cement' => $repo_ordinary_portland_cement_cement, 
            'adcrete_chemical' => $repo_adcrete_chemical, 'pozzolith300r_chemical' => $repo_pozzolith300r_chemical, 'rheobuild561_chemical' => $repo_rheobuild561_chemical,
            'rheobuild1000_chemical' => $repo_rheobuild1000_chemical, 'supercrete_chemical' => $repo_supercrete_chemical, 'supercrete_hs_chemical' => $repo_supercrete_hs_chemical, 
            'chips' => $repo_chips, 
            'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 
            'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
        }

        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }
    
    //Done
    public function searchForOrderAction($url, Request $request) {
            
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $type = $request->get('type');
            $supplier = $request->get('supName');
            $date = $request->get('calDate');
            $amount = $request->get('qty');

            $em1 = $this->getDoctrine()->getEntityManager();
            $con1 = $em1->getConnection();

            $qur = $con1->prepare("SELECT user_id FROM supplier_details WHERE sup_company_name = :comp and sup_type = :type");
            $qur->bindValue('comp', $supplier);
            $qur->bindValue('type', $type);
            $qur->execute();
            $res = $qur->fetchAll();

            $id = $res[0]['user_id'];

            $querry = $con1->prepare("SELECT * FROM orders WHERE (amount-purchased_amount) >= :value AND pur_date = :dt AND sup_id = :sId");
            $querry->bindValue('dt', $date);
            $querry->bindValue('sId', $id);
            $querry->bindValue('value', $amount);
            $querry->execute();
            $result = $querry->fetchAll();


            if ($result == []) {
                $sRes = array("Empty");

                return new Response(json_encode($sRes));
  
            } else {

                $res = array($result[0]['order_id'], "$supplier", $result[0]['amount'], $result[0]['purchased_amount'], $result[0]['date']);

                return new Response(json_encode($res));

            }

        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }


    //Done
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

            $qur = $con->prepare("SELECT user_id FROM supplier_details WHERE sup_company_name = :comp and sup_type = :tpe");
            $qur->bindValue('comp', $supplier_name);
            $qur->bindValue('tpe', $supplier_type);
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

            //Cement1
            $querry = $con->prepare('SELECT * FROM holcim_extra_cement ORDER BY date DESC');
            $querry->execute();
            $repo_holcim_extra_cement = $querry->fetchAll();

            //Cement2
            $querry = $con->prepare('SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC');
            $querry->execute();
            $repo_holcim_ready_flow_plus_cement = $querry->fetchAll();

            //Cement3
            $querry = $con->prepare('SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC');
            $querry->execute();
            $repo_ordinary_portland_cement_cement = $querry->fetchAll();

            //Chemical1
            $querry = $con->prepare('SELECT * FROM adcrete_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_adcrete_chemical = $querry->fetchAll();

            //Chemical2
            $querry = $con->prepare('SELECT * FROM pozzolith300r_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_pozzolith300r_chemical = $querry->fetchAll();

            //Chemical3
            $querry = $con->prepare('SELECT * FROM rheobuild561_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_rheobuild561_chemical = $querry->fetchAll();

            //Chemical4
            $querry = $con->prepare('SELECT * FROM rheobuild1000_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_rheobuild1000_chemical = $querry->fetchAll();

            //Chemical5
            $querry = $con->prepare('SELECT * FROM supercrete_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_supercrete_chemical = $querry->fetchAll();

            //Chemical6
            $querry = $con->prepare('SELECT * FROM supercrete_hs_chemical ORDER BY date DESC');
            $querry->execute();
            $repo_supercrete_hs_chemical = $querry->fetchAll();

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
                
                return $this->redirect($this->generateUrl('sep_homepage'));

                //return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => true, 'flag1' => false, 'flag2' => false, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand, 'user'=>$user, 'userDetails' => $userDetails, 'types' => $typeDet));
            } else {
                $res = array($result[0]['order_id'], "$supplier_name", $result[0]['amount'], $result[0]['purchased_amount'], $result[0]['date']);
                $extra = (double) $res[2] - (double) $res[3];
                $new_pur_amount = (double) $res[3] + (double) $pur_amount;
                
                if ($extra >= (double) $pur_amount) {
                    $querry = $con->prepare("UPDATE orders SET purchased_amount = :pur_amount WHERE pur_date = :date  AND sup_id = :id");
                    $querry->bindValue('pur_amount', $new_pur_amount);
                    $querry->bindValue('date', $formDate);
                    $querry->bindValue('id', $id);
                    $querry->execute();

                    //Cement 1
                    if ($supplier_type == "Holcim Extra") {
                        $qur = $con->prepare("SELECT date FROM holcim_extra_cement ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\HolcimExtraCement();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                            $dd = $lastDate[0]['date'];
                            
                            if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM holcim_extra_cement WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $cement_amount = $qur->fetchAll();
                            $opnBal = $cement_amount[0]['opening_balance'];
                            $usedBal = $cement_amount[0]['stock_used'];
                            $kk = $cement_amount[0]['stock_purchased'];
                            $prevCost = $cement_amount[0]['net_cost'];
                            $new_cement_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_cement_amount - (double) $usedBal;


                            $querry = $con->prepare("UPDATE holcim_extra_cement SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_cement_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            
                            //Cement
                            $querry = $con->prepare('SELECT * FROM holcim_extra_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM holcim_extra_cement WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_cement = $qur->fetchAll();
                            $ob = (double) $clossing_cement[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;


                            $entry = new \sepBundle\Entity\HolcimExtraCement();
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
                            $querry = $con->prepare('SELECT * FROM holcim_extra_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        }
                        }
                        

                        
                    }
                    
                    //Cement 2
                    if ($supplier_type == "Holcim Ready Flow Plus") {
                        $qur = $con->prepare("SELECT date FROM holcim_ready_flow_plus_cement ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\HolcimReadyFlowPlusCement();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                           $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $cement_amount = $qur->fetchAll();
                            $opnBal = $cement_amount[0]['opening_balance'];
                            $usedBal = $cement_amount[0]['stock_used'];
                            $kk = $cement_amount[0]['stock_purchased'];
                            $prevCost = $cement_amount[0]['net_cost'];
                            $new_cement_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_cement_amount - (double) $usedBal;


                            $querry = $con->prepare("UPDATE holcim_ready_flow_plus_cement SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_cement_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Cement
                            $querry = $con->prepare('SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM holcim_ready_flow_plus_cement WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_cement = $qur->fetchAll();
                            $ob = (double) $clossing_cement[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;


                            $entry = new \sepBundle\Entity\HolcimReadyFlowPlusCement();
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
                            $querry = $con->prepare('SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        } 
                        }
                        
                    }
                    
                    //Cement 3
                    if ($supplier_type == "Ordinary Portland Cement") {
                        $qur = $con->prepare("SELECT date FROM ordinary_portland_cement_cement ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\OrdinaryPortlandCementCement();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                            $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM ordinary_portland_cement_cement WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $cement_amount = $qur->fetchAll();
                            $opnBal = $cement_amount[0]['opening_balance'];
                            $usedBal = $cement_amount[0]['stock_used'];
                            $kk = $cement_amount[0]['stock_purchased'];
                            $prevCost = $cement_amount[0]['net_cost'];
                            $new_cement_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_cement_amount - (double) $usedBal;


                            $querry = $con->prepare("UPDATE ordinary_portland_cement_cement SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_cement_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Cement
                            $querry = $con->prepare('SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM ordinary_portland_cement_cement WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_cement = $qur->fetchAll();
                            $ob = (double) $clossing_cement[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;


                            $entry = new \sepBundle\Entity\OrdinaryPortlandCementCement();
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
                            $querry = $con->prepare('SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC');
                            $querry->execute();
                            $repo_cement = $querry->fetchAll();

                            // return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_cement, 'chemical' => $repo_chemical, 'chips' => $repo_chips, 'sand' => $repo_sand, 'diesel' => $repo_diesel, 'metal' => $repo_metal, 'm_sand' => $repo_m_sand,));
                        }
                        }
                        
                    }
                    
                    if ($supplier_type == "Sand") {
                        $qur = $con->prepare("SELECT date FROM sand ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Sand();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
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
                            $new_sand_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_sand_amount - (double) $usedBal;

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
                            $ob = (double) $clossing_sand[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

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
                        
                    }
                    
                    //Chemical 1
                    if ($supplier_type == "Adcrete") {
                        $qur = $con->prepare("SELECT date FROM adcrete_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\AdcreteChemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                           $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM adcrete_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE adcrete_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM adcrete_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM adcrete_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\AdcreteChemical();
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
                            $querry = $con->prepare('SELECT * FROM adcrete_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } 
                        }
                        
                    }
                    
                    //Chemical 2
                    if ($supplier_type == "Supercrete") {
                        $qur = $con->prepare("SELECT date FROM supercrete_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\SupercreteChemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                            $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM supercrete_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE supercrete_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM supercrete_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM supercrete_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\SupercreteChemical();
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
                            $querry = $con->prepare('SELECT * FROM supercrete_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                        }
                        
                    }
                    
                    //Chemical 3
                    if ($supplier_type == "Supercrete - HS") {
                        $qur = $con->prepare("SELECT date FROM supercrete_hs_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\SupercreteHsChemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                            $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM supercrete_hs_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE supercrete_hs_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM supercrete_hs_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM supercrete_hs_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\SupercreteHsChemical();
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
                            $querry = $con->prepare('SELECT * FROM supercrete_hs_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }
                        }
                        
                    }
                    
                    //Chemical 4
                    if ($supplier_type == "Rheobuild 561") {
                        $qur = $con->prepare("SELECT date FROM rheobuild561_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Rheobuild561Chemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                           $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM rheobuild561_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE rheobuild561_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM rheobuild561_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM rheobuild561_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\Rheobuild561Chemical();
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
                            $querry = $con->prepare('SELECT * FROM rheobuild561_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } 
                        }
                        
                    }
                    
                    //Chemical 5
                    if ($supplier_type == "Rheobuild 1000") {
                        $qur = $con->prepare("SELECT date FROM rheobuild1000_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Rheobuild1000Chemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                         $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM rheobuild1000_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE rheobuild1000_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM rheobuild1000_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM rheobuild1000_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\Rheobuild1000Chemical();
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
                            $querry = $con->prepare('SELECT * FROM rheobuild1000_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }   
                        }
                        
                    }
                    
                    //Chemical 6
                    if ($supplier_type == "Pozzolith 300R") {
                        $qur = $con->prepare("SELECT date FROM pozzolith300r_chemical ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Pozzolith300rChemical();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                          $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM pozzolith300r_chemical WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $chemical_amount = $qur->fetchAll();
                            $opnBal = $chemical_amount[0]['opening_balance'];
                            $kk = $chemical_amount[0]['stock_purchased'];
                            $usedBal = $chemical_amount[0]['stock_used'];
                            $prevCost = $chemical_amount[0]['net_cost'];
                            $new_chemical_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chemical_amount - (double) $usedBal;

                            $querry = $con->prepare("UPDATE pozzolith300r_chemical SET stock_purchased = :s_amount, net_cost = :nCost, clossing_balance = :stkUsed WHERE date = :date");
                            $querry->bindValue('s_amount', $new_chemical_amount);
                            $querry->bindValue('nCost', $new_net_cost);
                            $querry->bindValue('stkUsed', $closeBal);
                            $querry->bindValue('date', $formDate);
                            $querry->execute();
                            //Chemical
                            $querry = $con->prepare('SELECT * FROM pozzolith300r_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //   return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        } else {
                            $qur = $con->prepare("SELECT clossing_balance FROM pozzolith300r_chemical WHERE date = :date");
                            $qur->bindValue('date', $dd);
                            $qur->execute();
                            $clossing_chemical = $qur->fetchAll();
                            $ob = (double) $clossing_chemical[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\Pozzolith300rChemical();
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
                            $querry = $con->prepare('SELECT * FROM pozzolith300r_chemical ORDER BY date DESC');
                            $querry->execute();
                            $repo_chemical = $querry->fetchAll();

                            //  return $this->render('sepBundle:Profile:purchaseRM.html.twig', array('url' => $url, 'flag' => false, 'flag1' => false, 'flag2' => true, 'orders' => $repo_orders, 'names' => $companies, 'toDate' => $testDate, 'cement' => $repo_sand, 'sand' => $repo_sand));
                        }  
                        }
                        
                    }
                    
                    if ($supplier_type == "Chips") {
                        $qur = $con->prepare("SELECT date FROM chips ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Chips();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
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
                            $new_chips_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_chips_amount - (double) $usedBal;

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
                            $ob = (double) $clossing_chips[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

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
                        
                    }
                    if ($supplier_type == "Diesel") {
                        $qur = $con->prepare("SELECT date FROM diesel ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == 0){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Diesel();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setTransportation(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
                          $dd = $lastDate[0]['date'];

                        if ($dd == $formDate) {
                            $qur = $con->prepare("SELECT * FROM diesel WHERE date = :date");
                            $qur->bindValue('date', $formDate);
                            $qur->execute();
                            $diesel_amount = $qur->fetchAll();
                            $opnBal = $diesel_amount[0]['opening_balance'];
                            $kk = $diesel_amount[0]['stock_purchased'];
                            $usedBal = $diesel_amount[0]['stock_used'];
                            $movedBal = $diesel_amount[0]['transportation'];
                            $prevCost = $diesel_amount[0]['net_cost'];
                            $new_diesel_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_diesel_amount - (double) $usedBal - (double) $movedBal;

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
                            $ob = (double) $clossing_diesel[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

                            $entry = new \sepBundle\Entity\Diesel();
                            $entry->setDate($date);
                            $entry->setOpeningBalance($ob);
                            $entry->setClossingBalance($cb);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed($su);
                            $entry->setTransportation($su);
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
                        
                    }
                    if ($supplier_type == "M-Sand") {
                        $qur = $con->prepare("SELECT date FROM m_sand ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\MSand();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                        else{
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
                            $new_m_sand_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_m_sand_amount - (double) $usedBal;

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
                            $ob = (double) $clossing_m_sand[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

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
                        
                    }
                    if ($supplier_type == "Metal") {
                        $qur = $con->prepare("SELECT date FROM metal ORDER BY date DESC LIMIT 1");
                        $qur->execute();
                        $lastDate = $qur->fetchAll();
                        if($lastDate == []){
                            $pa = (double)$pur_amount;
                            $nCost = (double)$cost;
                            $entry = new \sepBundle\Entity\Metal();
                            $entry->setDate($date);
                            $entry->setOpeningBalance(0);
                            $entry->setClossingBalance($pa);
                            $entry->setStockPurchased($pa);
                            $entry->setStockUsed(0);
                            $entry->setNetCost($nCost);
                            $entry->setMonth($month);
                            $entry->setYear($year);
                            
                            $em->persist($entry);
                            $em->flush();
                        }
                            
                        else{
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
                            $new_metal_amount = (double) $kk + (double) $pur_amount;
                            $new_net_cost = (double) $prevCost + (double) $cost;
                            $closeBal = (double) $opnBal + (double) $new_metal_amount - (double) $usedBal;

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
                            $ob = (double) $clossing_metal[0]['clossing_balance'];
                            $pa = (double) $pur_amount;
                            $su = (double) 0;
                            $newCost = (double) $cost;
                            $cb = (double) $ob + (double) $pa;

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
                        
                    }
                    
                    $pur_fig = (double)$pur_amount;
                    $pur_cos = (double)$cost;
                    
                    $pur_entry = new \sepBundle\Entity\Purchases();
                    $pur_entry->setDeliveryOrderId($doId);
                    $pur_entry->setMaterial($supplier_type);
                    $pur_entry->setSupplierName($supplier_name);
                    $pur_entry->setDate($date);
                    $pur_entry->setAmount($pur_fig);
                    $pur_entry->setCost($pur_cos);

                    $em->persist($pur_entry);
                    $em->flush();
                    
                    return $this->redirect($this->generateUrl('purAndinvtMgt', array('url' => $url)));
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

    //Done
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

        $limitholcim_extra_cementVal = (double) 0;
        $limitholcim_ready_flow_plus_cementVal = (double) 0;
        $limitordinary_portland_cement_cementVal = (double) 0;
        
        $limitadcrete_chemicalVal = (double) 0;
        $pozzolith300rVal = (double) 0;
        $rheobuild561Val = (double) 0;
        $rheobuild1000_chemicalVal = (double) 0;
        $supercrete_chemicalVal = (double) 0;
        $supercrete_hs_chemicalVal = (double) 0;
        
        $metalVal = (double) 0;
        $sandVal = (double) 0;
        $dieselVal = (double) 0;
        $m_sandVal = (double) 0;
        $chipsVal = (double) 0;
        
        $querry = $con->prepare("SELECT * FROM reorder_levels");
        $querry->execute();
        $repo_reorderDetails = $querry->fetchAll();
        
        $querry = $con->prepare("SELECT * FROM purchases ORDER BY date DESC");
        $querry->execute();
        $repo_purchases = $querry->fetchAll();
               
        //cement 1
        $querry = $con->prepare("SELECT clossing_balance FROM holcim_extra_cement ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
            $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_holcim_extra_cement = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Holcim Extra");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitholcim_extra_cement = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitholcim_extra_cement) {
            
        }
        else{
            $limitholcim_extra_cementVal = (double) 1;
        }
        
        //cement 2
        $querry = $con->prepare("SELECT clossing_balance FROM holcim_ready_flow_plus_cement ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        
        $cb_holcim_ready_flow_plus_cement = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Holcim Ready Flow Plus");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitholcim_ready_flow_plus_cement = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitholcim_ready_flow_plus_cement) {
            
        }
        else{
            $limitholcim_ready_flow_plus_cementVal = (double) 1;
        }
        
        //cement 3
        $querry = $con->prepare("SELECT clossing_balance FROM ordinary_portland_cement_cement ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_ordinary_portland_cement_cement = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Ordinary Portland Cement");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitordinary_portland_cement_cement = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitordinary_portland_cement_cement) {
            
        }
        else{
            $limitordinary_portland_cement_cementVal = (double) 1;
        }
        
        //Sand
        $querry = $con->prepare("SELECT clossing_balance FROM sand ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_sand = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Sand");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitSand = (double) $result[0]["reorder_level"];
        if ($rr <= $limitSand) {
            
        }
        else{
            $sandVal = (double) 1;
        }
        
        //Chemical 1
        $querry = $con->prepare("SELECT clossing_balance FROM adcrete_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_adcrete_chemical = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Adcrete");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitadcrete_chemical = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitadcrete_chemical) {
            
        }
        if ($rr > $limitadcrete_chemical) {
            $limitadcrete_chemicalVal = (double) 1;
        }
        
        //Chemical 2
        $querry = $con->prepare("SELECT clossing_balance FROM pozzolith300r_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll(); 
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_pozzolith300r = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Pozzolith 300R");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitpozzolith300r = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitpozzolith300r) {
            
        }
        if ($rr > $limitpozzolith300r) {
            $pozzolith300rVal = (double) 1;
        }
        
        //Chemical 3
        $querry = $con->prepare("SELECT clossing_balance FROM rheobuild561_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_rheobuild561 = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Rheobuild 561");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitrheobuild561 = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitrheobuild561) {
            
        }
        if ($rr > $limitrheobuild561) {
            $rheobuild561Val = (double) 1;
        }
        
        //Chemical 4
        $querry = $con->prepare("SELECT clossing_balance FROM rheobuild1000_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_rheobuild1000_chemical = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Rheobuild 1000");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitrheobuild1000_chemical = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitrheobuild1000_chemical) {
            
        }
        if ($rr > $limitrheobuild1000_chemical) {
            $rheobuild1000_chemicalVal = (double) 1;
        }
        
        //Chemical 5
        $querry = $con->prepare("SELECT clossing_balance FROM supercrete_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_supercrete_chemical = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Supercrete");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitsupercrete_chemical = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitsupercrete_chemical) {
            
        }
        if ($rr > $limitsupercrete_chemical) {
            $supercrete_chemicalVal = (double) 1;
        }
        
        //Chemical 6
        $querry = $con->prepare("SELECT clossing_balance FROM supercrete_hs_chemical ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_supercrete_hs_chemical = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Supercrete - HS");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitsupercrete_hs_chemical = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitsupercrete_hs_chemical) {
            
        }
        if ($rr > $limitsupercrete_hs_chemical) {
            $supercrete_hs_chemicalVal = (double) 1;
        }
        
        //Metal
        $querry = $con->prepare("SELECT clossing_balance FROM metal ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_metal = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Metal");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitMetal = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitMetal) {
            
        }
        else {
            $metalVal = (double) 1;
        }
        
        //Diesel
        $querry = $con->prepare("SELECT clossing_balance FROM diesel ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_diesel = $rr;
         
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Diesel");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitDiesel = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitDiesel) {
            
        }
        else {
            $dieselVal = (double) 1;
        }

        //Chips
        $querry = $con->prepare("SELECT clossing_balance FROM chips ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_chips = $rr;
        
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "Chips");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitChips = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitChips) {
            
        }
        else {
            $chipsVal = (double) 1;
        }
        //M-Sand
        $querry = $con->prepare("SELECT clossing_balance FROM m_sand ORDER BY date DESC LIMIT 1");
        $querry->execute();
        $result = $querry->fetchAll();
        
        if($result == []){
          $rr = 0;  
        }
        else{
           $rr = (double) $result[0]["clossing_balance"];
        }
        
        $cb_m_sand = $rr;
          
        $querry = $con->prepare("SELECT reorder_level FROM reorder_levels WHERE title = :rm");
        $querry->bindValue('rm', "M_Sand");
        $querry->execute();
        $result = $querry->fetchAll();
        $limitM_Sand = (double) $result[0]["reorder_level"];
        
        if ($rr <= $limitM_Sand) {
            
        }
        else {
            $m_sandVal = (double) 1;
        }
        $counts = array("$limitholcim_extra_cementVal", "$limitholcim_ready_flow_plus_cementVal", "$limitordinary_portland_cement_cementVal", "$supercrete_hs_chemicalVal", "$pozzolith300rVal", "$rheobuild561Val", "$rheobuild1000_chemicalVal", "$supercrete_chemicalVal", "$limitadcrete_chemicalVal", "$metalVal", "$sandVal", "$dieselVal", "$m_sandVal", "$chipsVal");

        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:Profile:purAndinvenMgt.html.twig', array('url' => $url, 'toDate' => $testDate, 'types' => $typeDet, 'clbalance' => $counts, 'user'=>$user, 'userDetails' => $userDetails,
            'cbHolcim' => $cb_holcim_extra_cement, 'cbReadyFlow'=>$cb_holcim_ready_flow_plus_cement, 'cbPortland'=>$cb_ordinary_portland_cement_cement,
            'cbadcrete' => $cb_adcrete_chemical, 'cbpozzolith300r' => $cb_pozzolith300r, 'cbrheobuild561' => $cb_rheobuild561,
            
            'cbrheobuild1000' => $cb_rheobuild1000_chemical, 'cbsupercrete' => $cb_supercrete_chemical, 'cbsupercrete_hs' => $cb_supercrete_hs_chemical,
            'cbChips' => $cb_chips, 'cbMetal' => $cb_metal,  
            'cbSand' => $cb_sand, 'cbMSand' => $cb_m_sand, 'cbDiesel' => $cb_diesel,
            'lholcim' => $limitholcim_extra_cement, 'lreadyFlow'=> $limitholcim_ready_flow_plus_cement, 'lPortland'=>$limitordinary_portland_cement_cement,
            'ladcrete' => $limitadcrete_chemical, 'lpozzolith300r' => $limitpozzolith300r, 'lrheobuild561' => $limitrheobuild561,
            
            'lrheobuild1000' => $limitrheobuild1000_chemical, 'lsupercrete' => $limitsupercrete_chemical, 'lsupercrete_hs' => $limitsupercrete_hs_chemical,
            'lChips' => $limitChips, 'lMetal' => $limitMetal,  
            'lSand' => $limitSand, 'lMSand' => $limitM_Sand, 'lDiesel' => $limitDiesel,
            'reorderDetails' => $repo_reorderDetails, 'purchasesHis' => $repo_purchases, 'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true)); 
        }
        
        else{
        return $this->render('sepBundle:Profile:purAndinvenMgt.html.twig', array('url' => $url, 'toDate' => $testDate, 'types' => $typeDet, 'clbalance' => $counts, 'user'=>$user, 'userDetails' => $userDetails,
            'cbHolcim' => $cb_holcim_extra_cement, 'cbReadyFlow'=>$cb_holcim_ready_flow_plus_cement, 'cbPortland'=>$cb_ordinary_portland_cement_cement,
            'cbadcrete' => $cb_adcrete_chemical, 'cbpozzolith300r' => $cb_pozzolith300r, 'cbrheobuild561' => $cb_rheobuild561,
            
            'cbrheobuild1000' => $cb_rheobuild1000_chemical, 'cbsupercrete' => $cb_supercrete_chemical, 'cbsupercrete_hs' => $cb_supercrete_hs_chemical,
            'cbChips' => $cb_chips, 'cbMetal' => $cb_metal,  
            'cbSand' => $cb_sand, 'cbMSand' => $cb_m_sand, 'cbDiesel' => $cb_diesel,
            'lholcim' => $limitholcim_extra_cement, 'lreadyFlow'=> $limitholcim_ready_flow_plus_cement, 'lPortland'=>$limitordinary_portland_cement_cement,
            'ladcrete' => $limitadcrete_chemical, 'lpozzolith300r' => $limitpozzolith300r, 'lrheobuild561' => $limitrheobuild561,
            
            'lrheobuild1000' => $limitrheobuild1000_chemical, 'lsupercrete' => $limitsupercrete_chemical, 'lsupercrete_hs' => $limitsupercrete_hs_chemical,
            'lChips' => $limitChips, 'lMetal' => $limitMetal,  
            'lSand' => $limitSand, 'lMSand' => $limitM_Sand, 'lDiesel' => $limitDiesel,
            'reorderDetails' => $repo_reorderDetails, 'purchasesHis' => $repo_purchases, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false)); 
        }
        
         
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
        
    }
       
    //Done
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
            
            //Cement 1
            if ($rmType == "Holcim Extra") {
                $querry = $con->prepare("SELECT * FROM holcim_extra_cement ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];

                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM holcim_extra_cement WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE holcim_extra_cement SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();

   
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\HolcimExtraCement();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }
                $querry = $con->prepare("SELECT * FROM holcim_extra_cement WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Cement 2
            if ($rmType == "Holcim Ready Flow Plus") {
                $querry = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];

                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE holcim_ready_flow_plus_cement SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();

   
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\HolcimReadyFlowPlusCement();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }
                $querry = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Cement 3
            if ($rmType == "Ordinary Portland Cement") {
                $querry = $con->prepare("SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];

                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM ordinary_portland_cement_cement WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE ordinary_portland_cement_cement SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();

   
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\OrdinaryPortlandCementCement();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }
                $querry = $con->prepare("SELECT * FROM ordinary_portland_cement_cement WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 1
            if ($rmType == "Adcrete") {
                $querry = $con->prepare("SELECT * FROM adcrete_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM adcrete_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE adcrete_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\AdcreteChemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM adcrete_chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 2
            if ($rmType == "Supercrete") {
                $querry = $con->prepare("SELECT * FROM supercrete_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM supercrete_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE supercrete_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\SupercreteChemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM supercrete_chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 3
            if ($rmType == "Supercrete - HS") {
                $querry = $con->prepare("SELECT * FROM supercrete_hs_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM supercrete_hs_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE supercrete_hs_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\SupercreteHsChemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM supercrete_hs_chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 4
            if ($rmType == "Rheobuild 561") {
                $querry = $con->prepare("SELECT * FROM rheobuild561_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM rheobuild561_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE rheobuild561_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Rheobuild561Chemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM rheobuild561_chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 5
            if ($rmType == "Rheobuild 1000") {
                $querry = $con->prepare("SELECT * FROM rheobuild1000_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM rheobuild1000_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE rheobuild1000_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Rheobuild1000Chemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM rheobuild1000_chemical WHERE date = :date");
                $querry->bindValue('date', $usedDate);
                $querry->execute();
                $res = $querry->fetchAll();

            }
            
            //Chemical 6
            if ($rmType == "Pozzolith 300R") {
                $querry = $con->prepare("SELECT * FROM pozzolith300r_chemical ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM pozzolith300r_chemical WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

                    $querry = $con->prepare("UPDATE pozzolith300r_chemical SET stock_used = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Pozzolith300rChemical();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setNetCost(0);
                    $entry->setMonth($month);
                    $entry->setYear($year);

                    $em->persist($entry);
                    $em->flush();
                }


                $querry = $con->prepare("SELECT * FROM pozzolith300r_chemical WHERE date = :date");
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
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

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
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
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
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

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
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
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
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

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
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
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
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val[0]['transportation'] - (double) $val1;

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
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed($qty);
                    $entry->setTransportation(0);
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
            if ($rmType == "Transportation") {
                $querry = $con->prepare("SELECT * FROM diesel ORDER BY date DESC LIMIT 1");
                $querry->execute();
                $nRes = $querry->fetchAll();
                $dt = $nRes[0]['date'];
                if ($dt == $usedDate) {
                    $querry = $con->prepare("SELECT * FROM diesel WHERE date = :date");
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                    $val = $querry->fetchAll();
                    $val1 = (double) $val[0]['transportation'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val[0]['stock_used'] - (double) $val1;

                    $querry = $con->prepare("UPDATE diesel SET transportation = :val1, clossing_balance = :cb WHERE date = :date");
                    $querry->bindValue('val1', $val1);
                    $querry->bindValue('cb', $closeBal);
                    $querry->bindValue('date', $usedDate);
                    $querry->execute();
                } else {
                    $date = new \DateTime($usedDate);
                    $entry = new \sepBundle\Entity\Diesel();
                    $entry->setDate($date);
                    $entry->setOpeningBalance($nRes[0]['clossing_balance']);
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
                    $entry->setStockPurchased(0);
                    $entry->setStockUsed(0);
                    $entry->setTransportation($qty);
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
                    $val1 = (double) $val[0]['stock_used'] + (double) $qty;
                    $closeBal = (double) $val[0]['opening_balance'] + (double) $val[0]['stock_purchased'] - (double) $val1;

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
                    $entry->setClossingBalance((double) $nRes[0]['clossing_balance'] - (double) $qty);
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
            return $this->redirect($this->generateUrl('purAndinvtMgt', array('url' => $url)));
        } else {

        }
    }

    //Done
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
            
            return $this->redirect($this->generateUrl('purAndinvtMgt', array('url' => $url)));
          
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }


    //Done
    public function salesAnalysisAction($url, Request $request) {
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
        $qur = $con->prepare("SELECT * FROM sales ORDER BY sales_date DESC");
        $qur->execute();
        $res = $qur->fetchAll();
        
        //Aged debtor analysis  - less than 30 days
        $qur = $con->prepare("SELECT sales_id, customer_name, contact_num, sales_date, invoice_num, DATE_ADD(sales_date,INTERVAL 30 DAY) AS after_30_days, sales_amount, payment_received "
                . "FROM sales WHERE sales_amount > payment_received AND "
                . "(CURDATE() < DATE_ADD(sales_date,INTERVAL 30 DAY)) ORDER BY sales_date DESC");
        $qur->execute();
        $agedDebt1 = $qur->fetchAll();
        
        //Aged debtor analysis  - 30 to 60 days
        $qur = $con->prepare("SELECT sales_id, customer_name, contact_num, sales_date, invoice_num, DATE_ADD(sales_date,INTERVAL 30 DAY) AS after_30_days, sales_amount, payment_received "
                . "FROM sales WHERE sales_amount > payment_received AND "
                . "(CURDATE() BETWEEN DATE_ADD(sales_date,INTERVAL 30 DAY) AND DATE_ADD(sales_date,INTERVAL 60 DAY)) ORDER BY sales_date DESC");
        $qur->execute();
        $agedDebt2 = $qur->fetchAll();
        
        //Aged debtor analysis  - 60 to 90 days
        $qur = $con->prepare("SELECT sales_id, customer_name, contact_num, sales_date, invoice_num, DATE_ADD(sales_date,INTERVAL 30 DAY) AS after_30_days, sales_amount, payment_received "
                . "FROM sales WHERE sales_amount > payment_received AND "
                . "(CURDATE() BETWEEN DATE_ADD(sales_date,INTERVAL 60 DAY) AND DATE_ADD(sales_date,INTERVAL 90 DAY)) ORDER BY sales_date DESC");
        $qur->execute();
        $agedDebt3 = $qur->fetchAll();
        
        //Aged debtor analysis  - more than 90 days
        $qur = $con->prepare("SELECT sales_id, customer_name, contact_num, sales_date, invoice_num, DATE_ADD(sales_date,INTERVAL 30 DAY) AS after_30_days, sales_amount, payment_received "
                . "FROM sales WHERE sales_amount > payment_received AND "
                . "(CURDATE() > DATE_ADD(sales_date,INTERVAL 60 DAY)) ORDER BY sales_date DESC");
        $qur->execute();
        $agedDebt4 = $qur->fetchAll();
        
        //Sales order details
        $qur = $con->prepare("SELECT * FROM sales_orders WHERE status = 0 ORDER BY sales_order_date DESC, sales_order_id DESC");
        $qur->execute();
        $salesOrderDet = $qur->fetchAll();
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:Profile:salesAnalysis.html.twig', array('url' => $url, 
            'toDate' => $testDate, 'sales' => $res, 'user'=>$user, 'userDetails' => $userDetails,
            'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true,
            'agedDebt1'=> $agedDebt1, 'agedDebt2'=> $agedDebt2, 'agedDebt3'=> $agedDebt3, 'agedDebt4'=> $agedDebt4,
            'salesOrderDet' => $salesOrderDet)); 
        }
        
        else{
        return $this->render('sepBundle:Profile:salesAnalysis.html.twig', array('url' => $url, 
            'toDate' => $testDate, 'sales' => $res, 'user'=>$user, 'userDetails' => $userDetails,
            'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false,
            'agedDebt1'=> $agedDebt1, 'agedDebt2'=> $agedDebt2, 'agedDebt3'=> $agedDebt3, 'agedDebt4'=> $agedDebt4,
            'salesOrderDet' => $salesOrderDet));
        }
        
        
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }
    
    //Done
    public function salesEntryAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            
            if ($request->getMethod() == 'POST') {
            $cusName = $request->get('cus_name');
            $contact = $request->get('contact');
            
            $salesInvNum = $request->get('invNumber');
            $salesDate = $request->get('calDate');
            
            $salesVal = $request->get('sales');
            $payment = $request->get('payment');
            
            $ndt = $request->get('ndt');
            $vat = $request->get('vat');
            $svat = $request->get('svat');
            
            $month;
            
            $full_url = explode('-', $salesDate);
            $supId = $full_url[1];
            $year = $full_url[0];
            
            $salesOrderDetails = $request->get('salesDescID');
            
            
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $qur = $con->prepare("UPDATE sales_orders SET status = 1 WHERE sales_order_id = :id");
            $qur->bindValue('id', $salesOrderDetails);
            $qur->execute();

            
            if($supId == "01"){
                $month = "January";
            }
            else if($supId == "02"){
                $month = "February";
            }
            else if($supId == "03"){
                $month = "March";
            }
            else if($supId == "04"){
                $month = "April";
            }
            else if($supId == "05"){
                $month = "May";
            }
            else if($supId == "06"){
                $month = "June";
            }
            else if($supId == "07"){
                $month = "July";
            }
            else if($supId == "08"){
                $month = "August";
            }
            else if($supId == "09"){
               $month = "September"; 
            }
            else if($supId == "10"){
                $month = "October";
            }
            else if($supId == "11"){
                $month = "November";
            }
            else if($supId == "12"){
               $month = "December"; 
            }
            else{
                
            }
            
            
            //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $month));

            $date = new \DateTime($salesDate);
            $cn = (int) $contact;
            $sv = (double) $salesVal;
            $pay = (double) $payment;
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            $entry = new \sepBundle\Entity\Sales();
            
            $entry->setCustomerName($cusName);
            $entry->setContactNum($cn);
            $entry->setInvoiceNum($salesInvNum);
            
            $entry->setSalesDate($date);
            $entry->setMonth($month);
            $entry->setYear($year);
            
            $entry->setSalesAmount($sv);
            $entry->setPaymentReceived($pay);
            
            $entry->setNdt($ndt);
            $entry->setVat($vat);
            $entry->setSvat($svat);

            $em->persist($entry);
            $em->flush();

            
            return $this->redirect($this->generateUrl('salesAnalysis', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    //Done
        public function salesOrderEntryAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            
            if ($request->getMethod() == 'POST') {
            $cusName = $request->get('cus_name');
            $contact = $request->get('contact');
            $salesInvNum = $request->get('invoiceNum');
            $salesDate = $request->get('calDate');
            $location = $request->get('location');
            $grade = $request->get('grade');
            $qty = $request->get('qty');
           
            
            //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $month));

            $date = new \DateTime($salesDate);
            $quantity = (double) $qty;
            $status = 0;
            
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            $entry = new \sepBundle\Entity\SalesOrders();
            
            $entry->setCustomerName($cusName);
            $entry->setContactNum($contact);
            $entry->setInvoiceNum($salesInvNum);
            $entry->setSalesOrderDate($date);
            $entry->setLocation($location);
            $entry->setGrade($grade);
            $entry->setQuantity($quantity);
            $entry->setStatus($status);

            $em->persist($entry);
            $em->flush();

            
            return $this->redirect($this->generateUrl('salesAnalysis', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }
    
    //Done
    public function verifyCreditSalesEntryAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $val = $request->get('paymentVal');
            $id = $request->get('salesID');
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            $querry = $con->prepare("SELECT * FROM sales WHERE sales_id = :id");
            $querry->bindValue('id', $id);
            $querry->execute();
            $result = $querry->fetchAll();
            $test = (double) $result[0]["sales_amount"] - (double) $result[0]["payment_received"];
            if ((double) $test < (double) $val) {
                $rr = "False";
                return new Response(json_encode($rr));
            } else {
                $rr = "True";
                return new Response(json_encode($rr));
            }
        } else {
            
        }
    }

    //Done
    public function creditSalesEntryAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $cusName = $request->get('customer');
            $salesDesc = $request->get('salesID');
            $payment = $request->get('paymentVal');
            
            $desc = explode(' ', $salesDesc);
            $salesID = $desc[0];

            $em = $this->getDoctrine()->getEntityManager();
            $repo_sales = $em->getRepository('sepBundle:Sales');
            $sales = $repo_sales->findOneBy(array('salesId' => $salesID));
            $payRecv = $sales->getPaymentReceived();
            $newPayRecv = (double) $payRecv + (double) $payment;

            $con = $em->getConnection();
            $qur = $con->prepare("UPDATE sales SET payment_received = :val WHERE sales_id = :id");
            $qur->bindValue('val', $newPayRecv);
            $qur->bindValue('id', $salesID);
            $qur->execute();

            return $this->redirect($this->generateUrl('salesAnalysis', array('url' => $url)));
            
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    //Done
    public function selectCustomerAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $sid = $request->get('salesID');
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $qur = $con->prepare("SELECT customer_name FROM sales WHERE sales_id = :sid");
            $qur->bindValue('sid', $sid);
            $qur->execute();
            $res = $qur->fetchAll();
            return new Response(json_encode($res));
        } else {
            
        }
    }
    
    //Done
    public function SalesOrderDetailsAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $salesOrderDetails = $request->get('salesDescID');
            $em = $this->getDoctrine()->getEntityManager();

            $con = $em->getConnection();
            $qur = $con->prepare("SELECT customer_name, contact_num, grade, quantity, invoice_num FROM sales_orders WHERE sales_order_id = :id");
            $qur->bindValue('id', $salesOrderDetails);
            $qur->execute();
            $res = $qur->fetchAll();
            
            return new Response(json_encode($res));
        } else {
            
        }
    }
    
    //Done
    public function CreditSalesOrderDetailsAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $salesOrderDetails = $request->get('salesID');
            $em = $this->getDoctrine()->getEntityManager();

            $con = $em->getConnection();
            $qur = $con->prepare("SELECT customer_name, invoice_num FROM sales WHERE sales_id = :id");
            $qur->bindValue('id', $salesOrderDetails);
            $qur->execute();
            $res = $qur->fetchAll();
            
            return new Response(json_encode($res));
        } else {
            
        }
    }
    
    //Done
    public function cancelSalesOrderAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $repo_sales_orders = $em->getRepository('sepBundle:SalesOrders');
            $sales_orders = $repo_sales_orders->findAll();
            $con = $em->getConnection();
            for ($i = 0; $i < sizeof($sales_orders); $i++) {
                if (isset($_POST[$sales_orders[$i]->getSalesOrderId()])) {
                    $id = $sales_orders[$i]->getSalesOrderId();
                    $qur = $con->prepare("DELETE FROM sales_orders WHERE sales_order_id = :id");
                    $qur->bindValue('id', $id);
                    $qur->execute();
                }
            }
            return $this->redirect($this->generateUrl('salesAnalysis', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
          return $this->redirect($this->generateUrl('sep_homepage'));  
        }

    }

    //Done
        public function profitAnalysisAction($url, Request $request) {
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
        $income = (double) 0;
        $cost = (double) 0;
        
        $cost_holcim_extra_cement = (double) 0;
        $cost_holcim_ready = (double) 0;
        $cost_ordinary_portland = (double) 0;
        
        $cost_adcrete = (double) 0;
        $cost_pozzolith300r = (double) 0;
        $cost_rheobuild561 = (double) 0;
        $cost_rheobuild1000 = (double) 0;
        $cost_supercrete = (double) 0;
        $cost_supercrete_hs = (double) 0;
        
        $cost_sand = (double) 0;
        $cost_metal = (double) 0;
        $cost_m_sand = (double) 0;
        $cost_diesel = (double) 0;
        $cost_chips = (double) 0;

        //Income - Sales
        $q1 = $con->prepare("SELECT * FROM sales ORDER BY sales_date DESC");
        $q1->execute();
        $sales = $q1->fetchAll();
        for ($i = 0; $i < (int) sizeof($sales); $i++) {
            $income = $income + (double) $sales[$i]["sales_amount"];
        }

        //Costs - Cement 1
        $q1 = $con->prepare("SELECT * FROM holcim_extra_cement ORDER BY date DESC");
        $q1->execute();
        $holcim_extra_cement = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_extra_cement); $i++) {
            $cost_holcim_extra_cement = $cost_holcim_extra_cement+ (double) $holcim_extra_cement[$i]["net_cost"];
        }
        
        //Costs - Cement 2
        $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC");
        $q1->execute();
        $holcim_ready_flow_plus = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_ready_flow_plus); $i++) {
            $cost_holcim_ready = $cost_holcim_ready + (double) $holcim_ready_flow_plus[$i]["net_cost"];
        }
        
        //Costs - Cement 3
        $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC");
        $q1->execute();
        $ordinary_portland = $q1->fetchAll();
        for ($i = 0; $i < sizeof($ordinary_portland); $i++) {
            $cost_ordinary_portland = $cost_ordinary_portland + (double) $ordinary_portland[$i]["net_cost"];
        }

        //Costs - Chemical 1
        $q1 = $con->prepare("SELECT * FROM adcrete_chemical ORDER BY date DESC");
        $q1->execute();
        $adcrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($adcrete); $i++) {
            $cost_adcrete = $cost_adcrete + (double) $adcrete[$i]["net_cost"];
        }
        
        //Costs - Chemical 2
        $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical ORDER BY date DESC");
        $q1->execute();
        $pozzolith300r = $q1->fetchAll();
        for ($i = 0; $i < sizeof($pozzolith300r); $i++) {
            $cost_pozzolith300r = $cost_pozzolith300r + (double) $pozzolith300r[$i]["net_cost"];
        }
        
        //Costs - Chemical 3
        $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical ORDER BY date DESC");
        $q1->execute();
        $rheobuild561 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild561); $i++) {
            $cost_rheobuild561 = $cost_rheobuild561 + (double) $rheobuild561[$i]["net_cost"];
        }
        
        //Costs - Chemical 4
        $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical ORDER BY date DESC");
        $q1->execute();
        $rheobuild1000 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild1000); $i++) {
            $cost_rheobuild1000 = $cost_rheobuild1000 + (double) $rheobuild1000[$i]["net_cost"];
        }
        
        //Costs - Chemical 5
        $q1 = $con->prepare("SELECT * FROM supercrete_chemical ORDER BY date DESC");
        $q1->execute();
        $supercrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete); $i++) {
            $cost_supercrete = $cost_supercrete + (double) $supercrete[$i]["net_cost"];
        }
        
        //Costs - Chemical 6
        $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical ORDER BY date DESC");
        $q1->execute();
        $supercrete_hs = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete_hs); $i++) {
            $cost_supercrete_hs = $cost_supercrete_hs + (double) $supercrete_hs[$i]["net_cost"];
        }

        //Costs - Sand
        $q1 = $con->prepare("SELECT * FROM sand ORDER BY date DESC");
        $q1->execute();
        $sand = $q1->fetchAll();
        for ($i = 0; $i < sizeof($sand); $i++) {
            $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
        }

        //Costs - Metal
        $q1 = $con->prepare("SELECT * FROM metal ORDER BY date DESC");
        $q1->execute();
        $metal = $q1->fetchAll();
        for ($i = 0; $i < sizeof($metal); $i++) {
            $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
        }

        //Costs - diesel
        $q1 = $con->prepare("SELECT * FROM diesel ORDER BY date DESC");
        $q1->execute();
        $diesel = $q1->fetchAll();
        for ($i = 0; $i < sizeof($diesel); $i++) {
            $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
        }

        //Costs - chips
        $q1 = $con->prepare("SELECT * FROM chips ORDER BY date DESC");
        $q1->execute();
        $chips = $q1->fetchAll();
        for ($i = 0; $i < sizeof($chips); $i++) {
            $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
        }

        //Costs - M-Sand
        $q1 = $con->prepare("SELECT * FROM m_sand ORDER BY date DESC");
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

        //Admin expense titles
        $q1 = $con->prepare("SELECT * FROM expense_details");
        $q1->execute();
        $adminExpTitles = $q1->fetchAll();

        $cost = (double) $cost_holcim_extra_cement + (double) $cost_holcim_ready + (double) $cost_ordinary_portland + (double) $cost_adcrete + (double) $cost_pozzolith300r + (double) $cost_rheobuild561 + (double) $cost_rheobuild1000 + (double) $cost_supercrete + (double) $cost_supercrete_hs + (double) $cost_chips + (double) $cost_sand + (double) $cost_metal + (double) $cost_m_sand + (double) $cost_diesel;
        $pOrl = $this->calcProfit($income, $cost, $totAdminCost);
        
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:Profile:profitAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
            'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
            'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
            'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
            'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs,
            
            'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
            'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));
        }
        
        else{
        return $this->render('sepBundle:Profile:profitAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
            'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
            'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
            'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
            'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs,
            
            'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
            'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
        }
        

        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    } 
    
    //Done
    public function periodProfitAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
                    if ($request->getMethod() == "POST") {
            
            $full_url = explode('.', $url);
            $supId = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $user_repo = $em->getRepository('sepBundle:UserLogin');
            $user = $user_repo->findOneBy(array('userId' => $supId));

            $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $userDetailRepo->findOneBy(array('u' => $user));    

            $testDate = date('Y-m-d');
            
            $dt = $request->get('calDate');
            $dateRange = explode('-', $dt);
            $strtDate = $dateRange[0];
            $endDate = $dateRange[1];
            
            $strtDate = preg_replace('/\s+/', '', $strtDate);
            $endDate = preg_replace('/\s+/', '', $endDate);
            
            $tempDate = explode('/', $strtDate);
            $strtDateMM = $tempDate[0];
            $strtDateDD = $tempDate[1];
            $strtDateYY = $tempDate[2];
            
            $tempDate = explode('/', $endDate);
            $endDateMM = $tempDate[0];
            $endDateDD = $tempDate[1];
            $endDateYY = $tempDate[2];
            
            $strtDate = $strtDateYY."-".$strtDateMM."-".$strtDateDD;
            $endDate = $endDateYY."-".$endDateMM."-".$endDateDD;
            

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $income = (double) 0;
            $cost = (double) 0;
            
            $cost_holcim_extra_cement = (double) 0;
            $cost_holcim_ready = (double) 0;
            $cost_ordinary_portland = (double) 0;

            $cost_adcrete = (double) 0;
            $cost_pozzolith300r = (double) 0;
            $cost_rheobuild561 = (double) 0;
            $cost_rheobuild1000 = (double) 0;
            $cost_supercrete = (double) 0;
            $cost_supercrete_hs = (double) 0;

            $cost_sand = (double) 0;
            $cost_metal = (double) 0;
            $cost_m_sand = (double) 0;
            $cost_diesel = (double) 0;
            $cost_chips = (double) 0;

            //Income - Sales
            $q1 = $con->prepare("SELECT * FROM sales WHERE sales_date BETWEEN :sd AND :ed ORDER BY sales_date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $sales = $q1->fetchAll();
            for ($i = 0; $i < (int) sizeof($sales); $i++) {
                $income = $income + (double) $sales[$i]["sales_amount"];
            }

            //Costs - Cement 1
            $q1 = $con->prepare("SELECT * FROM holcim_extra_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $holcim_extra_cement = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_extra_cement); $i++) {
            $cost_holcim_extra_cement = $cost_holcim_extra_cement+ (double) $holcim_extra_cement[$i]["net_cost"];
        }
            
            //Costs - Cement 2
            $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $holcim_ready_flow_plus = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_ready_flow_plus); $i++) {
            $cost_holcim_ready = $cost_holcim_ready + (double) $holcim_ready_flow_plus[$i]["net_cost"];
        }
            
            //Costs - Cement 3
            $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $ordinary_portland = $q1->fetchAll();
        for ($i = 0; $i < sizeof($ordinary_portland); $i++) {
            $cost_ordinary_portland = $cost_ordinary_portland + (double) $ordinary_portland[$i]["net_cost"];
        }
            
            //Costs - Chemical 1
            $q1 = $con->prepare("SELECT * FROM adcrete_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
           $adcrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($adcrete); $i++) {
            $cost_adcrete = $cost_adcrete + (double) $adcrete[$i]["net_cost"];
        }
            
            //Costs - Chemical 2
            $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $pozzolith300r = $q1->fetchAll();
        for ($i = 0; $i < sizeof($pozzolith300r); $i++) {
            $cost_pozzolith300r = $cost_pozzolith300r + (double) $pozzolith300r[$i]["net_cost"];
        }
            
            //Costs - Chemical 3
            $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
           $rheobuild561 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild561); $i++) {
            $cost_rheobuild561 = $cost_rheobuild561 + (double) $rheobuild561[$i]["net_cost"];
        }
            
            //Costs - Chemical 4
            $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $rheobuild1000 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild1000); $i++) {
            $cost_rheobuild1000 = $cost_rheobuild1000 + (double) $rheobuild1000[$i]["net_cost"];
        }
            
            //Costs - Chemical 5
            $q1 = $con->prepare("SELECT * FROM supercrete_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $supercrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete); $i++) {
            $cost_supercrete = $cost_supercrete + (double) $supercrete[$i]["net_cost"];
        }
            
            //Costs - Chemical 6
            $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $supercrete_hs = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete_hs); $i++) {
            $cost_supercrete_hs = $cost_supercrete_hs + (double) $supercrete_hs[$i]["net_cost"];
        }

            //Costs - Sand
            $q1 = $con->prepare("SELECT * FROM sand WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $sand = $q1->fetchAll();
            for ($i = 0; $i < sizeof($sand); $i++) {
                $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
            }

            //Costs - Metal
            $q1 = $con->prepare("SELECT * FROM metal WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $metal = $q1->fetchAll();
            for ($i = 0; $i < sizeof($metal); $i++) {
                $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
            }

            //Costs - diesel
            $q1 = $con->prepare("SELECT * FROM diesel WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $diesel = $q1->fetchAll();
            for ($i = 0; $i < sizeof($diesel); $i++) {
                $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
            }

            //Costs - chips
            $q1 = $con->prepare("SELECT * FROM chips WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $chips = $q1->fetchAll();
            for ($i = 0; $i < sizeof($chips); $i++) {
                $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
            }

            //Costs - M-Sand
            $q1 = $con->prepare("SELECT * FROM m_sand WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $mSand = $q1->fetchAll();
            for ($i = 0; $i < sizeof($mSand); $i++) {
                $cost_m_sand = $cost_m_sand + (double) $mSand[$i]["net_cost"];
            }
            //Admin expenses 
            $q1 = $con->prepare("SELECT * FROM other_expenses WHERE date BETWEEN :sd AND :ed");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $adminExp = $q1->fetchAll();
            $totAdminCost = (double) 0;
            for ($i = 0; $i < sizeof($adminExp); $i++) {
                $totAdminCost = $totAdminCost + (double) $adminExp[$i]["amount"];
            }
            
            //Admin expense titles
            $q1 = $con->prepare("SELECT * FROM expense_details");
            $q1->execute();
            $adminExpTitles = $q1->fetchAll();

            $cost = (double) $cost_holcim_extra_cement + (double) $cost_holcim_ready + (double) $cost_ordinary_portland + (double) $cost_adcrete + (double) $cost_pozzolith300r + (double) $cost_rheobuild561 + (double) $cost_rheobuild1000 + (double) $cost_supercrete + (double) $cost_supercrete_hs + (double) $cost_chips + (double) $cost_sand + (double) $cost_metal + (double) $cost_m_sand + (double) $cost_diesel;
            $pOrl = $this->calcProfit($income, $cost, $totAdminCost);
            
            $userProfilePics = $this->displayImage($url);
        
            if($userProfilePics[0]['image'] == null)
            {
                return $this->render('sepBundle:Profile:profitAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
                    'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
                    'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
                    'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
                    'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs, 
                    'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
                    'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));
            }

            else{
                return $this->render('sepBundle:Profile:profitAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
                    'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
                    'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
                    'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
                    'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs,
                    'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
                    'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
            }
        
        } 
        else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }
    
    public function cashFlowAnalysisAction($url, Request $request) {
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
        $income = (double) 0;
        $cost = (double) 0;
        
        $cost_holcim_extra_cement = (double) 0;
        $cost_holcim_ready = (double) 0;
        $cost_ordinary_portland = (double) 0;

        $cost_adcrete = (double) 0;
        $cost_pozzolith300r = (double) 0;
        $cost_rheobuild561 = (double) 0;
        $cost_rheobuild1000 = (double) 0;
        $cost_supercrete = (double) 0;
        $cost_supercrete_hs = (double) 0;
        
        $cost_sand = (double) 0;
        $cost_metal = (double) 0;
        $cost_m_sand = (double) 0;
        $cost_diesel = (double) 0;
        $cost_chips = (double) 0;

        //Income - Sales
        $q1 = $con->prepare("SELECT * FROM sales ORDER BY sales_date DESC");
        $q1->execute();
        $sales = $q1->fetchAll();
        for ($i = 0; $i < (int) sizeof($sales); $i++) {
            $income = $income + (double) $sales[$i]["payment_received"];
        }

        //Costs - Cement 1
        $q1 = $con->prepare("SELECT * FROM holcim_extra_cement ORDER BY date DESC");
        $q1->execute();
        $holcim_extra_cement = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_extra_cement); $i++) {
            $cost_holcim_extra_cement = $cost_holcim_extra_cement+ (double) $holcim_extra_cement[$i]["net_cost"];
        }
        
        //Costs - Cement 2
        $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement ORDER BY date DESC");
        $q1->execute();
        $holcim_ready_flow_plus = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_ready_flow_plus); $i++) {
            $cost_holcim_ready = $cost_holcim_ready + (double) $holcim_ready_flow_plus[$i]["net_cost"];
        }
        
        //Costs - Cement 3
        $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement ORDER BY date DESC");
        $q1->execute();
        $ordinary_portland = $q1->fetchAll();
        for ($i = 0; $i < sizeof($ordinary_portland); $i++) {
            $cost_ordinary_portland = $cost_ordinary_portland + (double) $ordinary_portland[$i]["net_cost"];
        }

        //Costs - Chemical 1
        $q1 = $con->prepare("SELECT * FROM adcrete_chemical ORDER BY date DESC");
        $q1->execute();
        $adcrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($adcrete); $i++) {
            $cost_adcrete = $cost_adcrete + (double) $adcrete[$i]["net_cost"];
        }
        
        //Costs - Chemical 2
        $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical ORDER BY date DESC");
        $q1->execute();
        $pozzolith300r = $q1->fetchAll();
        for ($i = 0; $i < sizeof($pozzolith300r); $i++) {
            $cost_pozzolith300r = $cost_pozzolith300r + (double) $pozzolith300r[$i]["net_cost"];
        }
        
        //Costs - Chemical 3
        $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical ORDER BY date DESC");
        $q1->execute();
        $rheobuild561 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild561); $i++) {
            $cost_rheobuild561 = $cost_rheobuild561 + (double) $rheobuild561[$i]["net_cost"];
        }
        
        //Costs - Chemical 4
        $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical ORDER BY date DESC");
        $q1->execute();
        $rheobuild1000 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild1000); $i++) {
            $cost_rheobuild1000 = $cost_rheobuild1000 + (double) $rheobuild1000[$i]["net_cost"];
        }
        
        //Costs - Chemical 5
        $q1 = $con->prepare("SELECT * FROM supercrete_chemical ORDER BY date DESC");
        $q1->execute();
        $supercrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete); $i++) {
            $cost_supercrete = $cost_supercrete + (double) $supercrete[$i]["net_cost"];
        }
        
        //Costs - Chemical 6
        $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical ORDER BY date DESC");
        $q1->execute();
        $supercrete_hs = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete_hs); $i++) {
            $cost_supercrete_hs = $cost_supercrete_hs + (double) $supercrete_hs[$i]["net_cost"];
        }

        //Costs - Sand
        $q1 = $con->prepare("SELECT * FROM sand ORDER BY date DESC");
        $q1->execute();
        $sand = $q1->fetchAll();
        for ($i = 0; $i < sizeof($sand); $i++) {
            $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
        }

        //Costs - Metal
        $q1 = $con->prepare("SELECT * FROM metal ORDER BY date DESC");
        $q1->execute();
        $metal = $q1->fetchAll();
        for ($i = 0; $i < sizeof($metal); $i++) {
            $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
        }

        //Costs - diesel
        $q1 = $con->prepare("SELECT * FROM diesel ORDER BY date DESC");
        $q1->execute();
        $diesel = $q1->fetchAll();
        for ($i = 0; $i < sizeof($diesel); $i++) {
            $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
        }

        //Costs - chips
        $q1 = $con->prepare("SELECT * FROM chips ORDER BY date DESC");
        $q1->execute();
        $chips = $q1->fetchAll();
        for ($i = 0; $i < sizeof($chips); $i++) {
            $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
        }

        //Costs - M-Sand
        $q1 = $con->prepare("SELECT * FROM m_sand ORDER BY date DESC");
        $q1->execute();
        $mSand = $q1->fetchAll();
        for ($i = 0; $i < sizeof($mSand); $i++) {
            $cost_m_sand = $cost_m_sand + (double) $mSand[$i]["net_cost"];
        }
        //Admin expenses 
        $q1 = $con->prepare("SELECT * FROM other_expenses where payment_status = '1'");
        $q1->execute();
        $adminExp = $q1->fetchAll();
        $totAdminCost = (double) 0;
        for ($i = 0; $i < sizeof($adminExp); $i++) {
            $totAdminCost = $totAdminCost + (double) $adminExp[$i]["amount"];
        }

        //Admin expense titles
        $q1 = $con->prepare("SELECT * FROM expense_details");
        $q1->execute();
        $adminExpTitles = $q1->fetchAll();

        $cost = (double) $cost_holcim_extra_cement + (double) $cost_holcim_ready + (double) $cost_ordinary_portland + (double) $cost_adcrete + (double) $cost_pozzolith300r + (double) $cost_rheobuild561 + (double) $cost_rheobuild1000 + (double) $cost_supercrete + (double) $cost_supercrete_hs + (double) $cost_chips + (double) $cost_sand + (double) $cost_metal + (double) $cost_m_sand + (double) $cost_diesel;
        $pOrl = $this->calcProfit($income, $cost, $totAdminCost);
        
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null){
        return $this->render('sepBundle:Profile:cashFlowAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
            'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
            'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
            'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
            'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs, 
            'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
            'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));
        }
        
        else{
        return $this->render('sepBundle:Profile:cashFlowAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
            'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
            'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
            'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
            'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs, 
            'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
            'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
        }
        

        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }
    
    
    public function periodCashFlowAnalysisAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            
            $full_url = explode('.', $url);
            $supId = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $user_repo = $em->getRepository('sepBundle:UserLogin');
            $user = $user_repo->findOneBy(array('userId' => $supId));

            $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $userDetailRepo->findOneBy(array('u' => $user));    

            $testDate = date('Y-m-d');
            
            $dt = $request->get('calDate');
            $dateRange = explode('-', $dt);
            $strtDate = $dateRange[0];
            $endDate = $dateRange[1];
            
            $strtDate = preg_replace('/\s+/', '', $strtDate);
            $endDate = preg_replace('/\s+/', '', $endDate);
            
            $tempDate = explode('/', $strtDate);
            $strtDateMM = $tempDate[0];
            $strtDateDD = $tempDate[1];
            $strtDateYY = $tempDate[2];
            
            $tempDate = explode('/', $endDate);
            $endDateMM = $tempDate[0];
            $endDateDD = $tempDate[1];
            $endDateYY = $tempDate[2];
            
            $strtDate = $strtDateYY."-".$strtDateMM."-".$strtDateDD;
            $endDate = $endDateYY."-".$endDateMM."-".$endDateDD;
            

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            
            $income = (double) 0;
            $cost = (double) 0;
            
            $cost_holcim_extra_cement = (double) 0;
            $cost_holcim_ready = (double) 0;
            $cost_ordinary_portland = (double) 0;

            $cost_adcrete = (double) 0;
            $cost_pozzolith300r = (double) 0;
            $cost_rheobuild561 = (double) 0;
            $cost_rheobuild1000 = (double) 0;
            $cost_supercrete = (double) 0;
            $cost_supercrete_hs = (double) 0;
            
            $cost_sand = (double) 0;
            $cost_metal = (double) 0;
            $cost_m_sand = (double) 0;
            $cost_diesel = (double) 0;
            $cost_chips = (double) 0;

            //Income - Sales
            $q1 = $con->prepare("SELECT * FROM sales WHERE sales_date BETWEEN :sd AND :ed ORDER BY sales_date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $sales = $q1->fetchAll();
            for ($i = 0; $i < (int) sizeof($sales); $i++) {
                $income = $income + (double) $sales[$i]["payment_received"];
            }

            //Costs - Cement 1
            $q1 = $con->prepare("SELECT * FROM holcim_extra_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $holcim_extra_cement = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_extra_cement); $i++) {
            $cost_holcim_extra_cement = $cost_holcim_extra_cement+ (double) $holcim_extra_cement[$i]["net_cost"];
        }
            
            //Costs - Cement 2
            $q1 = $con->prepare("SELECT * FROM holcim_ready_flow_plus_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $holcim_ready_flow_plus = $q1->fetchAll();
        for ($i = 0; $i < sizeof($holcim_ready_flow_plus); $i++) {
            $cost_holcim_ready = $cost_holcim_ready + (double) $holcim_ready_flow_plus[$i]["net_cost"];
        }
            
            //Costs - Cement 3
            $q1 = $con->prepare("SELECT * FROM ordinary_portland_cement_cement WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $ordinary_portland = $q1->fetchAll();
        for ($i = 0; $i < sizeof($ordinary_portland); $i++) {
            $cost_ordinary_portland = $cost_ordinary_portland + (double) $ordinary_portland[$i]["net_cost"];
        }
            
            //Costs - Chemical 1
            $q1 = $con->prepare("SELECT * FROM adcrete_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
           $adcrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($adcrete); $i++) {
            $cost_adcrete = $cost_adcrete + (double) $adcrete[$i]["net_cost"];
        }
            
            //Costs - Chemical 2
            $q1 = $con->prepare("SELECT * FROM pozzolith300r_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $pozzolith300r = $q1->fetchAll();
        for ($i = 0; $i < sizeof($pozzolith300r); $i++) {
            $cost_pozzolith300r = $cost_pozzolith300r + (double) $pozzolith300r[$i]["net_cost"];
        }
            
            //Costs - Chemical 3
            $q1 = $con->prepare("SELECT * FROM rheobuild561_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
           $rheobuild561 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild561); $i++) {
            $cost_rheobuild561 = $cost_rheobuild561 + (double) $rheobuild561[$i]["net_cost"];
        }
            
            //Costs - Chemical 4
            $q1 = $con->prepare("SELECT * FROM rheobuild1000_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $rheobuild1000 = $q1->fetchAll();
        for ($i = 0; $i < sizeof($rheobuild1000); $i++) {
            $cost_rheobuild1000 = $cost_rheobuild1000 + (double) $rheobuild1000[$i]["net_cost"];
        }
            
            //Costs - Chemical 5
            $q1 = $con->prepare("SELECT * FROM supercrete_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $supercrete = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete); $i++) {
            $cost_supercrete = $cost_supercrete + (double) $supercrete[$i]["net_cost"];
        }
            
            //Costs - Chemical 6
            $q1 = $con->prepare("SELECT * FROM supercrete_hs_chemical WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $supercrete_hs = $q1->fetchAll();
        for ($i = 0; $i < sizeof($supercrete_hs); $i++) {
            $cost_supercrete_hs = $cost_supercrete_hs + (double) $supercrete_hs[$i]["net_cost"];
        }

            //Costs - Sand
            $q1 = $con->prepare("SELECT * FROM sand WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $sand = $q1->fetchAll();
            for ($i = 0; $i < sizeof($sand); $i++) {
                $cost_sand = $cost_sand + (double) $sand[$i]["net_cost"];
            }

            //Costs - Metal
            $q1 = $con->prepare("SELECT * FROM metal WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $metal = $q1->fetchAll();
            for ($i = 0; $i < sizeof($metal); $i++) {
                $cost_metal = $cost_metal + (double) $metal[$i]["net_cost"];
            }

            //Costs - diesel
            $q1 = $con->prepare("SELECT * FROM diesel WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $diesel = $q1->fetchAll();
            for ($i = 0; $i < sizeof($diesel); $i++) {
                $cost_diesel = $cost_diesel + (double) $diesel[$i]["net_cost"];
            }

            //Costs - chips
            $q1 = $con->prepare("SELECT * FROM chips WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $chips = $q1->fetchAll();
            for ($i = 0; $i < sizeof($chips); $i++) {
                $cost_chips = $cost_chips + (double) $chips[$i]["net_cost"];
            }

            //Costs - M-Sand
            $q1 = $con->prepare("SELECT * FROM m_sand WHERE date BETWEEN :sd AND :ed ORDER BY date DESC");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $mSand = $q1->fetchAll();
            for ($i = 0; $i < sizeof($mSand); $i++) {
                $cost_m_sand = $cost_m_sand + (double) $mSand[$i]["net_cost"];
            }
            //Admin expenses 
            $q1 = $con->prepare("SELECT * FROM other_expenses WHERE payment_status = '1' AND date BETWEEN :sd AND :ed");
            $q1->bindValue('sd', $strtDate);
            $q1->bindValue('ed', $endDate);
            $q1->execute();
            $adminExp = $q1->fetchAll();
            $totAdminCost = (double) 0;
            for ($i = 0; $i < sizeof($adminExp); $i++) {
                $totAdminCost = $totAdminCost + (double) $adminExp[$i]["amount"];
            }
            
            //Admin expense titles
            $q1 = $con->prepare("SELECT * FROM expense_details");
            $q1->execute();
            $adminExpTitles = $q1->fetchAll();

            $cost = (double) $cost_holcim_extra_cement + (double) $cost_holcim_ready + (double) $cost_ordinary_portland + (double) $cost_adcrete + (double) $cost_pozzolith300r + (double) $cost_rheobuild561 + (double) $cost_rheobuild1000 + (double) $cost_supercrete + (double) $cost_supercrete_hs + (double) $cost_chips + (double) $cost_sand + (double) $cost_metal + (double) $cost_m_sand + (double) $cost_diesel;
            $pOrl = $this->calcProfit($income, $cost, $totAdminCost);
            
            $userProfilePics = $this->displayImage($url);
        
            if($userProfilePics[0]['image'] == null)
            {
                return $this->render('sepBundle:Profile:cashFlowAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
                    'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
                    'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
                    'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
                    'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs,  
                    'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
                    'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));
            }

            else{
                return $this->render('sepBundle:Profile:cashFlowAnalysis.html.twig', array('url' => $url, 'toDate' => $testDate, 'user'=>$user, 'userDetails' => $userDetails,
                    'profitOrloss' => $pOrl, 'costOfSales'=>$cost, 'income'=>$income, 'adminCost'=>$totAdminCost,
                    'holcim_extra'=>$holcim_extra_cement, 'holcim_ready'=>$holcim_ready_flow_plus, 'ordinary_portland'=>$ordinary_portland,
            
                    'adcrete'=>$adcrete, 'pozzolith300r'=>$pozzolith300r, 'rheobuild561'=>$rheobuild561,
            
                    'rheobuild1000'=>$rheobuild1000, 'supercrete'=>$supercrete, 'supercrete_hs'=>$supercrete_hs, 
                    'sand'=>$sand, 'chips'=>$chips, 'mSand'=>$mSand, 'diesel'=>$diesel, 'metal'=>$metal, 'adminExpenses'=> $adminExp, 'adminExpTitles'=>$adminExpTitles, 'sales'=>$sales,
                    'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
            }
        
        } 
        else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }


    public function otherExpensesAction($url, Request $request) {
        
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
        $expense_repo = $em->getRepository('sepBundle:ExpenseDetails');
        $expenses = $expense_repo->findAll();
        $con = $em->getConnection();
        $qur = $con->prepare("SELECT * FROM other_expenses ORDER BY date DESC");
        $qur->execute();
        $res = $qur->fetchAll();
        $testDate = date('Y-m-d');
        
        $userProfilePics = $this->displayImage($url);
        
        if($userProfilePics[0]['image'] == null)
        {
            return $this->render('sepBundle:Profile:otherExpenses.html.twig', array('url' => $url, 
                'toDate' => $testDate, 'expenses' => $expenses, 'expenseHistory' => $res, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => false, 'flag2' => true));
        }

        else{
            return $this->render('sepBundle:Profile:otherExpenses.html.twig', array('url' => $url, 
                'toDate' => $testDate, 'expenses' => $expenses, 'expenseHistory' => $res, 
                'user'=>$user, 'userDetails' => $userDetails, 'im'=>$userProfilePics, 'flag1' => true, 'flag2' => false));
        }

        }
        
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }
         
    


    public function submitExpensesAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $category = $request->get('type');
            $ex_date = $request->get('calDate');
            $amount = $request->get('value');
            $date = new \DateTime($ex_date);

            $em = $this->getDoctrine()->getEntityManager();
            $repo_ex_details = $em->getRepository('sepBundle:ExpenseDetails');
            $expense = $repo_ex_details->findOneBy(array('expenseTitle' => $category));

            $entry = new \sepBundle\Entity\OtherExpenses();
            $entry->setAmount((double) $amount);
            $entry->setDate($date);
            $entry->setExpenseType($expense);
            $entry->setPaymentStatus((int) 0);

            $em->persist($entry);
            $em->flush();

            return $this->redirect($this->generateUrl('otherExpenses', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    public function newExpensesAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $name = $request->get('name');
            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();
            $qur = $con->prepare("SELECT * FROM expense_details");
            $qur->execute();
            $result = $qur->fetchAll();
            $check = (int) 0;
            // return $this->render('sepBundle:Default:index.html.twig', array('rr'=>$result));
            for ($i = 0; $i < sizeof($result); $i++) {
                if ($result[$i]["expense_title"] == $name) {
                    $check = (int) 10;
                } else {
                    
                }
            }
            if ($check == 0) {
                $entry = new \sepBundle\Entity\ExpenseDetails();
                $entry->setExpenseTitle($name);
                $em->persist($entry);
                $em->flush();

                $qur = $con->prepare("SELECT * FROM expense_details");
                $qur->execute();
                $res = $qur->fetchAll();
                return new Response(json_encode($res));
            } else {
                $ans = "Empty";
                return new Response(json_encode($ans));
            }
        } else {
            
        }
    }

    public function cancelExpensesAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $repo_ex = $em->getRepository('sepBundle:OtherExpenses');
            $expenses = $repo_ex->findAll();
            $con = $em->getConnection();
            $chkRes = (int) 0;
            for ($i = 0; $i < sizeof($expenses); $i++) {
                if (isset($_POST[$expenses[$i]->getPaymentId()])) {
                    $id = $expenses[$i]->getPaymentId();
                    $qur = $con->prepare("DELETE FROM other_expenses WHERE payment_id = :id");
                    $qur->bindValue('id', $id);
                    $qur->execute();
                    $chkRes = (int) 10;
                }
            }
            return $this->redirect($this->generateUrl('otherExpenses', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    public function updatePaymentExpensesAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
            if ($request->getMethod() == "POST") {
            $em = $this->getDoctrine()->getEntityManager();
            $repo_ex = $em->getRepository('sepBundle:OtherExpenses');
            $expenses = $repo_ex->findAll();
            $con = $em->getConnection();
            $chkRes = (int) 0;
            for ($i = 0; $i < sizeof($expenses); $i++) {
                if (isset($_POST[$expenses[$i]->getPaymentId()])) {
                    $id = $expenses[$i]->getPaymentId();
                    $qur = $con->prepare("UPDATE other_expenses SET payment_status = '1' WHERE payment_id = :id");
                    $qur->bindValue('id', $id);
                    $qur->execute();
                    $chkRes = (int) 10;
                }
            }
            return $this->redirect($this->generateUrl('otherExpenses', array('url' => $url)));
        } 
        else {
            return $this->redirect($this->generateUrl('sep_homepage'));    
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }

    }

    public function adminLoginAction($url, Request $request) {
        
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
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
            $newRes = array();
            if ($res) {
                return $this->redirect($this->generateUrl('displayAdminPage', array('url' => $url)));
            } else {
                return $this->redirect($this->generateUrl('sep_homepage'));
            }
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
           return $this->redirect($this->generateUrl('sep_homepage')); 
        }
        
    }
    
    public function imageUploadAction($url, Request $request){
        $full_url = explode('.', $url);
        $userId = $full_url[1];
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
            
            $repo_user = $em->getRepository('sepBundle:UserLogin');
            $user = $repo_user->findOneby(array('userId' => $userId)); 
            
            $userLoginDetails = $repo_user->findAll();

            $repo_user = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $repo_user->findOneby(array('u' => $user));
            
            $supDetailRepo = $em->getRepository('sepBundle:SupplierDetails');
            $supDetails = $supDetailRepo->findAll();

            $userDRepo = $em->getRepository('sepBundle:UserDetails');
            $userD = $userDRepo->findAll();
            
            $con = $em->getConnection();
            $qur = $con->prepare("SELECT distinct customer_name, contact_num FROM sales");
            $qur->execute();
            $customerDetails = $qur->fetchAll();

            $con = $em->getConnection();
            $qur = $con->prepare("SELECT * FROM user_login WHERE status = '0'");
            $qur->execute();
            $res = $qur->fetchAll();
            
            $userDetailRepo = $em->getRepository('sepBundle:UserDetails');
            $userDetails = $userDetailRepo->findOneBy(array('u' => $user));
            $allUserDetails = $userDetailRepo->findAll();

        if($userProfilePics == []){
            return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag1' => false, 'flag2' => true,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 
            'ud' => $userD, 'im'=> $userProfilePics, 'cusd'=>$customerDetails, 'aud'=>$allUserDetails,
                'ulogin'=>$userLoginDetails)); 
        }
        
        else{
            return $this->render('sepBundle:Profile:userProfile.html.twig', array('flag1' => true, 'flag2' => false,
            'url' => $url, 'userDetails' => $userDetails, 'user' => $user, 'aceeptReq' => $res, 'sd' => $supDetails, 
            'ud' => $userD, 'im'=> $userProfilePics, 'cusd'=>$customerDetails, 'aud'=>$allUserDetails,
                'ulogin'=>$userLoginDetails)); 
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

    public function getOrderSup($type) {
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $mySuppliers = array();

        $queery = $con->prepare("SELECT sup_company_name FROM supplier_details WHERE sup_type = :type");
        $queery->bindValue('type', $type);
        $queery->execute();
        $mySuppliers = $queery->fetchAll();

        return $mySuppliers;
    }

    public function loadInbox() {

        $em = $this->getDoctrine()->getEntityManager();
        $repo_message = $em->getRepository('sepBundle:Messaging');
        $myMessages = array();

        $myMessages = $repo_message->findAll();
        return $myMessages;
    }

    public function getCompMessages($name) {
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $myMsgs = array();

        $queery = $con->prepare("SELECT user_id FROM supplier_details WHERE sup_company_name = :name");
        $queery->bindValue('name', $name);
        $queery->execute();
        $myMsgs = $queery->fetchAll();
        $compId = $myMsgs[0]["user_id"];

        $qur = $con->prepare("SELECT message_id FROM messaging WHERE supplier_id = :id AND message_by_company IS NULL");
        $qur->bindValue('id', $compId);
        $qur->execute();
        $res = $qur->fetchAll();

        return $res;
    }

    public function calcProfit($revenue, $cost, $expenses) {
        $porloss = (double) $revenue - (double) $cost - (double) $expenses;
        return $porloss;
    }

    public function calchasing($pass) {
        $newpass = md5($pass);
        return $newpass;
    }

    public function calcClossingbalance($opnBal, $stkUsed, $purchased) {
        $clBal = (double) $opnBal - (double) $stkUsed + (double) $purchased;
        return $clBal;
    }

    public function calcEncryption($string, $key) {
        $iv = mcrypt_create_iv(
                mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM
        );

        $encrypted = base64_encode(
                $iv .
                mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), $string, MCRYPT_MODE_CBC, $iv
                )
        );

        $data = base64_decode($encrypted);
        $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

        $decrypted = rtrim(
                mcrypt_decrypt(
                        MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)), MCRYPT_MODE_CBC, $iv
                ), "\0"
        );

        if ($string == $decrypted) {
            return 10;
        } else {
            return 0;
        }
    }
    
        //This Action has not been used but may be important if the days ballance need to be taken
    public function closingBalanceAction($url, Request $request) {
        if ($request->getMethod() == 'POST') {
            $rmType = $request->get('type');
            $usedDate = $request->get('calDate');
            $qty = $request->get('quantity');

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            if ($rmType == "Cement") {
                $querry = $con->prepare("UPDATE cement SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else if ($rmType == "Sand") {
                $querry = $con->prepare("UPDATE sand SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else if ($rmType == "Chemical") {
                $querry = $con->prepare("UPDATE chemical SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else if ($rmType == "Chips") {
                $querry = $con->prepare("UPDATE chips SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else if ($rmType == "Diesel") {
                $querry = $con->prepare("UPDATE diesel SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else if ($rmType == "M-Sand") {
                $querry = $con->prepare("UPDATE m_sand SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            } else {
                $querry = $con->prepare("UPDATE metal SET clossing_balance = :val1 WHERE date = :date");
                $querry->bindValue('val1', $qty);
                $querry->bindValue('date', $usedDate);
                $querry->execute();
            }

            return $this->redirect($this->generateUrl('purchaseRM', array('url' => $url)));
        } else {
            
        }
    }

}
