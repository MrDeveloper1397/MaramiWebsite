<?php
 use PHPMailer\PHPMailer\PHPMailer;

 if(isset($_POST['name']) && ($_POST['email']))
 {
     $name = $_POST['name'];
     $mail = $_POST['email'];
     $message = $_POST['message'];

     require_once "PHPMailer/PHPMailer.php";
     require_once "PHPMailer/SMTP.php";
     require_once "PHPMailer/Exception.php";

     $email = new PHPMailer();

     $email->isSMTP();
     $email->Host="smtp.gmail.com";
     $email->SMTPAuth = true;
     $email->Username="testingmarami@gmail.com";
     $email->Password="Testing@123";
     $email->Port=587;
     $email->SMTPSecure ="tls";

     //email settings

     $email->isHTML(true);
     $email->setFrom("testingmarami@gmail.com", $name);
     $email->addAddress($mail);
     //$email->message = (" $email ($message)");
     //$email->message = "hiiii";
     $email->Subject = "Marami Contact Enquiry";
	 $email->MsgHTML("Enquiry,".$name);
	 $email->IsHTML(true);

    if($email->send())
     {
        $status = "Success";
      $response="Thank You!, We have received your Query, we will get back to you soon."; 

     }
     else
     {
       $status ="Failed";
     $response="Something Went wrong:<br>". $email->ErrorInfo;

     }
     echo json_encode(array("response"=> $response));

 }

?>
