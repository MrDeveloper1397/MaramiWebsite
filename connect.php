<?php
 $txtyourname= $_POST['txtyourname'];
 $txtemail= $_POST['txtemail'];
 $txtcontactno= $_POST['txtcontactno'];
 $txtsubject= $_POST['txtsubject'];
 $txtenquiry= $_POST['txtenquiry'];

 
	$conn = new mysqli ('localhost','root','','marami');
	if($conn-> connection_error)
	{
		die("Connection failed: " . $conn->connect_error);

	}else{
		 $stmt= $conn-> prepare("Insert into enq(txtyourname,txtemail,txtcontactno,txtsubject,txtenquiry)values(?,?,?,?,?)");
		
		 $stmt->bind_param("ssiss",$txtyourname,$txtemail,$txtcontactno,$txtsubject,$txtenquiry);
		 $stmt->execute();
		 echo"Enquiry Submitted";
		 $stmt-> close();
		 $conn->close();

	}

 ?>
