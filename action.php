<?php
if(file_exists("config/configuration.php")){
	include_once('config/configuration.php');
}
else{
echo 'Configuration file is not found. please put it in the location "admin/config"';
exit;	
}
$date=date('Y-m-d H:i:s');
$date1=date('Y-m-d');
$ipaddress = $_SERVER['REMOTE_ADDR'];



/****************Enquiry Form********************/
if(isset($_POST['btnenquiry'])){
	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';	*/
	//exit;			
	$obj->setSucmsgToEmpty();
	$obj->setErrorToEmpty();	
	$obj->doEmptySession('capchaerr');
	
	/*if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['txtvfc'])) != $_SESSION['captcha']){
		$error = "Invalid verification code entered";
		$obj->setError($error);
		if($obj->sV('ajaxs')== 'yes'){echo $error;exit;}
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit;
	}*/
	
	$yourname = $obj->sV('txtyourname');
	$email = $obj->sV('txtemail');
	$contactno = $obj->sV('txtcontactno');
	$subject = $obj->sV('txtsubject');	
	$enquiry = $obj->sV('txtenquiry');	
	
	
	$from = "enquiry@marami.biz";
	$to ="info@marami.biz";
		
	
	
	
	$subtomail = "Enquiry $enquirypage";
	
	$emailmsg = '';
	$emailmsg .= '<div style="width:550px; margin:50px; font:Verdana, Geneva, sans-serif; font-size:13px;border:1px solid #CCCCCC;">';
	
	$emailmsg .= '<div style="margin:0px auto 0px auto; padding:5px;">';
	$emailmsg .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
	$emailmsg .= '<tr><td colspan="3" align="center"><h2>Enquiry</h2></td></tr>';
	
	$emailmsg .= '<tr><td width="36%" align="left" valign="top" style="font-weight:900">Your Name</td>';
	$emailmsg .= '<td width="3%" align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td width=61%" align="left" valign="top">'.$yourname.'</td></tr>';
	
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Email ID</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$email.'</td></tr>';
	
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Contact No</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$contactno.'</td></tr>';
	
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Subject</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$subject.'</td></tr>';
		
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Your Enquiry</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$enquiry.'</td></tr>';
		
	
	$emailmsg .='</table></div></div>';
	/*echo $emailmsg;
	exit;*/
	
	
	$m->From($from);
	$m->To($to);
	$m->Subject($subtomail);
	$m->ReplyTo($email);
	
	
	$m->Body($emailmsg);
	//$m->Bcc("jagdishsuna@gmail.com");
	$m->Content_type("text/html");
	$m->Priority(3);
	
	$v = $m->Send();
	//echo $m->Get();exit;
	
	$thanky_msg = 'Thank you, we have received your Enquiry, we will get back to you soon.';
	$obj->setSucmsg($thanky_msg);
	if($obj->sV('ajaxs')== 'yes'){echo $thanku_msg;exit;}
	
	$thanku = 'thankyou.html';//$_SERVER['HTTP_REFERER'];
	header('Location: '.$thanku);
	exit;		
	
}
/****************Enquiry Form********************/
/****************Contact Form********************/
if(isset($_POST['btncontact'])){
	/*echo '<pre>';
	print_r($_POST);
	echo '</pre>';	*/
	//exit;			
	$obj->setSucmsgToEmpty();
	$obj->setErrorToEmpty();	
	$obj->doEmptySession('capchaerr');
	
	/*if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['txtvfc'])) != $_SESSION['captcha']){
		$error = "Invalid verification code entered";
		$obj->setError($error);
		if($obj->sV('ajaxs')== 'yes'){echo $error;exit;}
		header('Location: '.$_SERVER['HTTP_REFERER']);
		exit;
	}*/
	
	$name = $obj->sV('txtname');
	$email = $obj->sV('txtemail');
	$message = $obj->sV('txtmessage');
	
	
	$from = "enquiry@marami.biz";
	$to ="info@marami.biz";
		
	
	
	
	$subtomail = "Contact $enquirypage";
	
	$emailmsg = '';
	$emailmsg .= '<div style="width:550px; margin:50px; font:Verdana, Geneva, sans-serif; font-size:13px;border:1px solid #CCCCCC;">';
	
	$emailmsg .= '<div style="margin:0px auto 0px auto; padding:5px;">';
	$emailmsg .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
	$emailmsg .= '<tr><td colspan="3" align="center"><h2>Contact Form</h2></td></tr>';
	
	$emailmsg .= '<tr><td width="36%" align="left" valign="top" style="font-weight:900">Name</td>';
	$emailmsg .= '<td width="3%" align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td width=61%" align="left" valign="top">'.$name.'</td></tr>';
	
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Email ID</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$email.'</td></tr>';
	
	$emailmsg .= '<tr><td  align="left" valign="top" style="font-weight:900">Message</td>';
	$emailmsg .= '<td  align="left" valign="top" style="font-weight:900">:</td>';
	$emailmsg .= '<td  align="left" valign="top">'.$message.'</td></tr>';
		
	
	$emailmsg .='</table></div></div>';
	/*echo $emailmsg;
	exit;*/
	
	
	$m->From($from);
	$m->To($to);
	$m->Subject($subtomail);
	$m->ReplyTo($email);
	
	
	$m->Body($emailmsg);
	//$m->Bcc("jagdishsuna@gmail.com");
	$m->Content_type("text/html");
	$m->Priority(3);
	
	$v = $m->Send();
	//echo $m->Get();exit;
	
	$thanky_msg = 'Thank you, we have received your Enquiry, we will get back to you soon.';
	$obj->setSucmsg($thanky_msg);
	if($obj->sV('ajaxs')== 'yes'){echo $thanky_msg;exit;}
	
	$thanku = 'thankyou.html';//$_SERVER['HTTP_REFERER'];
	header('Location: '.$thanku);
	exit;		
	
}
/****************Contact Form********************/





?>