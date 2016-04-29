<?php

namespace sepBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SupplierProfileController extends Controller {

    public function loginAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
        $full_details = explode(".", $url);
        $u_id = $full_details[1];

        $em = $this->getDoctrine()->getEntityManager();
        $repo_user = $em->getRepository('sepBundle:UserLogin');
        $user = $repo_user->findOneby(array('userId' => $u_id));
        $repo_details = $em->getRepository('sepBundle:SupplierDetails');
        $sup_details = $repo_details->findOneBy(array('user' => $user));
        
        $suName = $user->getUsername();
        
        $userProfilePics = $this->displayImage($url);
        //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $userProfilePics));
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
                'supplierUserName' => $suName, 'im'=> $userProfilePics, 'flag1' => false, 'flag2' => true));
           
        }
        
        else{
            return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
                'supplierUserName' => $suName, 'im'=> $userProfilePics, 'flag1' => true, 'flag2' => false));
        }
        
        
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        
    }
    
        public function supplierImageUploadAction($url, Request $request){
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

            $repo_user = $em->getRepository('sepBundle:SupplierDetails');
            $sup_details = $repo_user->findOneby(array('user' => $user));
            
            $suName = $user->getUsername();


            $con = $em->getConnection();
            $qur = $con->prepare("SELECT * FROM user_login WHERE status = '0'");
            $qur->execute();
            $res = $qur->fetchAll();

        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
                 'supplierUserName'=> $suName, 'im'=> $userProfilePics, 'flag1' => false, 'flag2' => true));
           
        }
        
        else{
            return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
               'supplierUserName' => $suName, 'im'=> $userProfilePics, 'flag1' => true, 'flag2' => false));
        }
                
        }
    }

    public function submitEditDetailsAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $sup_comp_name = $request->get('CompName');
            $sup_comp_type = $request->get('selectType');
            $sup_comp_address = $request->get('Address');
            $sup_comp_contact = $request->get('Contact');

            $em = $this->getDoctrine()->getEntityManager();
            $repo_details = $em->getRepository('sepBundle:UserLogin');

            $full_url = explode(".", $url);
            $u_id = $full_url[1];

            $supplier = $repo_details->findOneBy(array('userId' => $u_id));

            $emo = $this->getDoctrine()->getEntityManager();
            $connection = $emo->getConnection();
            $repo_user_login = $em->getRepository('sepBundle:UserLogin');
            $testUser = $repo_user_login->findOneBy(array('userId' => $u_id));
            $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            $testSupplier = $repo_details->findOneBy(array('user' => $testUser));

            if (!(is_null($sup_comp_name))) {
                $tempStatement = $connection->prepare("UPDATE supplier_details SET sup_company_name = :sup_comp_name WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_name', $sup_comp_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($sup_comp_name == "") {
                $sup_comp_name = $testSupplier->getSupCompanyName();
                $tempStatement = $connection->prepare("UPDATE supplier_details SET sup_company_name = :sup_comp_name WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_name', $sup_comp_name);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if (!(is_null($sup_comp_type))) {
                $tempStatement = $connection->prepare("UPDATE supplier_details SET sup_type = :sup_comp_type WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_type', $sup_comp_type);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($sup_comp_type == "") {
                $sup_comp_type = $testSupplier->getSupType();
                $tempStatement = $connection->prepare("UPDATE supplier_details SET sup_type = :sup_comp_type WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_type', $sup_comp_type);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if (!(is_null($sup_comp_address))) {
                $tempStatement = $connection->prepare("UPDATE supplier_details SET address = :sup_comp_address WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_address', $sup_comp_address);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($sup_comp_address == "") {
                $sup_comp_address = $testSupplier->getAddress();
                $tempStatement = $connection->prepare("UPDATE supplier_details SET address = :sup_comp_address WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_address', $sup_comp_address);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if (!(is_null($sup_comp_contact))) {
                $tempStatement = $connection->prepare("UPDATE supplier_details SET contact_number = :sup_comp_contact WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_contact', $sup_comp_contact);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }

            if ($sup_comp_contact == "") {
                $sup_comp_contact = $testSupplier->getContactNumber();
                $tempStatement = $connection->prepare("UPDATE supplier_details SET contact_number = :sup_comp_contact WHERE user_id = :u_id");
                $tempStatement->bindValue('sup_comp_contact', $sup_comp_contact);
                $tempStatement->bindValue('u_id', $u_id);
                $tempStatement->execute();
            }


            return $this->redirect($this->generateUrl('profile_home_supplier', array('url' => $url)));
        } else {
            
        }
    }

    public function changePasswordAction(Request $request, $url) {
        if ($request->getMethod() == "POST") {
            $current = $request->get('current_password');
            $new = $request->get('new_password');

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
            $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            $sup_details = $repo_details->findOneBy(array('user' => $user));
            $name = $user->getUsername();

            if ($db_pass == $current) {

                $querry = $connection->prepare("UPDATE user_login SET password = :n_pass WHERE user_id = :id");
                $querry->bindValue('n_pass', $new);
                $querry->bindValue('id', $u_id);
                $querry->execute();

                return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('flag' => false, 'flag1' => true, 'flag2' => false, 'flag3' => false, 'url' => $url, 'sup_details' => $sup_details, 'name' => $name));
            } else {
                return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('flag' => true, 'flag1' => false, 'flag2' => false, 'flag3' => false, 'url' => $url, 'sup_details' => $sup_details, 'name' => $name));
            }
        }
    }

    public function changeUsernameAction(Request $request, $url) {
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
            $repo_details = $em->getRepository('sepBundle:SupplierDetails');
            $sup_details = $repo_details->findOneBy(array('user' => $user));

            if ($db_u_name == $current) {

                $querry = $connection->prepare("UPDATE user_login SET username = :n_u_name WHERE user_id = :id");
                $querry->bindValue('n_u_name', $new);
                $querry->bindValue('id', $u_id);
                $querry->execute();

                $querry = $connection->prepare("SELECT username FROM user_login WHERE user_id = :id");
                $querry->bindValue('id', $u_id);
                $querry->execute();
                $res = $querry->fetchAll();

                $name = $res[0]['username'];

                return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => false, 'flag3' => true, 'url' => $url, 'sup_details' => $sup_details, 'name' => $name));
            } else {
                $querry = $connection->prepare("SELECT username FROM user_login WHERE user_id = :id");
                $querry->bindValue('id', $u_id);
                $querry->execute();
                $res = $querry->fetchAll();

                $name = $res[0]['username'];
                return $this->render('sepBundle:SupplierProfile:supplierProfile.html.twig', array('flag' => false, 'flag1' => false, 'flag2' => true, 'flag3' => false, 'url' => $url, 'sup_details' => $sup_details, 'name' => $name));
            }
        }
    }

    public function viewOrdersAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        if(isset($userid)){
     
        $full_details = explode(".", $url);
        $u_id = $full_details[1];
        
        
        $em = $this->getDoctrine()->getEntityManager();
        $repo_user = $em->getRepository('sepBundle:UserLogin');
        $user = $repo_user->findOneby(array('userId' => $u_id));
        
        $repo_details = $em->getRepository('sepBundle:SupplierDetails');
        $sup_details = $repo_details->findOneBy(array('user' => $user));
        
        $order = $this->findOrders($u_id);
        $todaysDate = date('Y-m-d H:i:s');
        
        $suName = $user->getUsername();
        
        $userProfilePics = $this->displayImage($url);
        //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $userProfilePics));

        
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:SupplierProfile:viewOrders.html.twig', array('url' => $url, 'Order' => $order, 'todaysDate' => $todaysDate,
                'sup_details' => $sup_details, 'supplierUserName' => $suName, 'im'=> $userProfilePics, 'flag1' => false, 'flag2' => true));
           
        }
        
        else{
            return $this->render('sepBundle:SupplierProfile:viewOrders.html.twig', array('url' => $url, 'Order' => $order, 'todaysDate' => $todaysDate,
                'sup_details' => $sup_details, 'supplierUserName' => $suName, 'im'=> $userProfilePics, 'flag1' => true, 'flag2' => false));
        }
        
      
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
            
        }
        
    }

    public function messageboxAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
            
        $full_details = explode(".", $url);
        $u_id = $full_details[1];

        $em = $this->getDoctrine()->getEntityManager();
        $repo_user = $em->getRepository('sepBundle:UserLogin');
        $user = $repo_user->findOneby(array('userId' => $u_id));
        $repo_details = $em->getRepository('sepBundle:SupplierDetails');
        $sup_details = $repo_details->findOneBy(array('user' => $user));
        
        $con = $em->getConnection();
        $qur = $con->prepare("SELECT message_id FROM messaging WHERE supplier_id = :id AND message_by_supplier IS NULL");
        $qur->bindValue('id', $u_id);
        $qur->execute();
        $res_id = $qur->fetchAll();
        
        $m_id = 0;
        $inboxMessages = $this->loadInbox($user, $u_id, $m_id);
        
        $suName = $user->getUsername();
        
        $userProfilePics = $this->displayImage($url);
        //return $this->render('sepBundle:Default:index.html.twig', array('rr' => $userProfilePics));
        
        if($userProfilePics[0]['image'] == null){
            return $this->render('sepBundle:SupplierProfile:supMessage.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
                'supplierUserName' => $suName, 'im'=> $userProfilePics, 
                'myMessage' => $inboxMessages, 'res_id' => $res_id, 'flag1' => false, 'flag2' => true));
           
        }
        
        else{
            return $this->render('sepBundle:SupplierProfile:supMessage.html.twig', array('url' => $url, 'sup_details' => $sup_details, 
                'supplierUserName' => $suName, 'im'=> $userProfilePics, 
                'myMessage' => $inboxMessages, 'res_id' => $res_id,
                'flag1' => true, 'flag2' => false));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
       
    }
    
        public function tempmessageboxAction($url, Request $request) {
        $session = $request->getSession();
        $userid=$session->get('id');
        
        if(isset($userid)){
        $full_url = explode('.', $url);
        $supId = $full_url[1];

        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $qur = $con->prepare("SELECT message_id FROM messaging WHERE supplier_id = :id AND message_by_supplier IS NULL");
        $qur->bindValue('id', $supId);
        $qur->execute();
        $res_id = $qur->fetchAll();
        $repo_login = $em->getRepository('sepBundle:UserLogin');

        $supplier = $repo_login->findOneBy(array('userId' => $supId));
        $m_id = 0;

        $inboxMessages = $this->loadInbox($supplier, $supId, $m_id);


        return $this->render('sepBundle:SupplierProfile:tempsupplierProfile.html.twig', array('url' => $url, 'myMessage' => $inboxMessages, 'res_id' => $res_id, 'flag' => false, 'flag1' => false));
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
       
    }
    
    public function sendReplyAction($url, Request $request){
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

            $em = $this->getDoctrine()->getEntityManager();
            
            $con = $em->getConnection();
            $qur = $con->prepare('UPDATE messaging SET message_header_by_supplier = :hdMsg , message_by_supplier = :bdyMsg , reply_time = :ddtt WHERE message_id = :id');
            $qur->bindValue('hdMsg', $subject);
            $qur->bindValue('bdyMsg', $body);
            $qur->bindValue('ddtt', $todayDate);
            $qur->bindValue('id', $msg);
            $qur->execute();
            
            return $this->redirect($this->generateUrl('sup_message', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }

    public function composeMessageAction($url, Request $request) {
        if ($request->getMethod() == "POST") {
            $full_url = explode('.', $url);
            $supId = $full_url[1];

            $em = $this->getDoctrine()->getEntityManager();
            $con = $em->getConnection();

            $subject = $request->get('subject');
            $body = $request->get('message');
            $todayDate = date('Y-m-d H:i:s');
            $sendDate = new \DateTime($todayDate);
            $messageId = $request->get('mes_id');
            $repo_login = $em->getRepository('sepBundle:UserLogin');
            $supplier = $repo_login->findOneBy(array('userId' => $supId));


            if ($messageId == "New Message") {
                $repStatus = 0;
                $comp_message = new \sepBundle\Entity\Messaging();

                $comp_message->setSupplier($supplier);
                $comp_message->setMessageHeaderBySupplier($subject);
                $comp_message->setMessageBySupplier($body);
                $comp_message->setSendTime($sendDate);
                $comp_message->setMessageStatus($repStatus);

                $em->persist($comp_message);
                $em->flush();
            } else {
                $qur = $con->prepare("UPDATE messaging SET message_header_by_supplier = :mhs , message_by_supplier = :ms , reply_time = :rt  WHERE message_id = :id");
                $qur->bindValue('mhs', $subject);
                $qur->bindValue('ms', $body);
                $qur->bindValue('rt', $todayDate);
                $qur->bindValue('id', $messageId);
                $qur->execute();
            }

            $qur = $con->prepare("SELECT message_id FROM messaging WHERE supplier_id = :id AND message_by_supplier IS NULL");
            $qur->bindValue('id', $supId);
            $qur->execute();
            $res_id = $qur->fetchAll();
            $supplier = $repo_login->findOneBy(array('userId' => $supId));
            $m_id = 0;
            $inboxMessages = $this->loadInbox($supplier, $supId, $m_id);

            return $this->render('sepBundle:SupplierProfile:supMessage.html.twig', array('url' => $url, 'myMessage' => $inboxMessages, 'res_id'=>$res_id, 'flag' => true, 'flag1' => false));
        } else {
            return $this->redirect($this->generateUrl('profile_home_supplier', array('url' => $url)));
        }
    }
    
    public function newMessageAction($url, Request $request){
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
            $repStatus = 0;

            $em = $this->getDoctrine()->getEntityManager();
            $repo_login = $em->getRepository('sepBundle:UserLogin');
            $supplier = $repo_login->findOneBy(array('userId' => $userId));

                $comp_message = new \sepBundle\Entity\Messaging();
                $comp_message->setSupplier($supplier);
                $comp_message->setMessageHeaderBySupplier($subject);
                $comp_message->setMessageBySupplier($body);
                $comp_message->setSendTime($sendDate);
                $comp_message->setMessageStatus($repStatus);

                $em->persist($comp_message);
                $em->flush();
           
            return $this->redirect($this->generateUrl('sup_message', array('url' => $url)));
        } else {
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
        }
        else{
            return $this->redirect($this->generateUrl('sep_homepage'));
        }
    }


    

    public function findOrders($sup) {
        $em = $this->getDoctrine()->getEntityManager();
        $order_repo = $em->getRepository('sepBundle:Orders');

        $NewOrders = array();
        $NewOrders = $order_repo->findBy(array('sup' => $sup));

        return $NewOrders;
    }

    public function loadInbox($sup, $id, $m_id) {

        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $qur = $con->prepare("SELECT * FROM messaging WHERE supplier_id = :id ORDER BY message_id DESC LIMIT 25");
        $qur->bindValue('id', $id);
        $qur->execute();
        $res = $qur->fetchAll();
        return $res;
    }
    
    public function saveImage($name, $image, $userId){
        
        $em = $this->getDoctrine()->getEntityManager();
        $con = $em->getConnection();
        $queery = $con->prepare("update supplier_details set name = :nm, image = :im where user_id = :uid");
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
        $queery = $con->prepare("select image from supplier_details where user_id = :uid");
        $queery->bindValue('uid', $userId);        
        $queery->execute();
        $res = $queery->fetchAll();        
 
        return $res;
        
    }

}
