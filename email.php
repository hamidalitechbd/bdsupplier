<?php
	require_once('PHPMailer/PHPMailerAutoload.php');
	date_default_timezone_set("Asia/Dhaka");
	$current_time=date("Y-m-d H:i:s");
	
	//  mail through by full approval
	function new_registrations($advEmail,$reqProject_name,$advName,$id,$reqRequisitionDate,$reqRequireDate,$advAmount,$AccStatus,$collectionDate)
	{
		
			$subject="Cash Advance Requisition";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Cash Advance Requisition";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Cash Requsition details for $reqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $reqRequisitionDate<br>require date: $reqRequireDate<br>Require Money $advAmount tk has been $AccStatus please Collect date $collectionDate from EzzyAccounts. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Processed.Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("advance-pending-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("advance-pending-requisition.php"); </script>';  
			}	
	}
	//  mail through for Partial approval
	function pnew_registrations($preqEmail,$preqProject_name,$preqName,$id,$preqRequisitionDate,$preqRequireDate,$advAmount,$padvAmount,$pAccStatus,$pcollectionDate)
	{
		
			$subject="Cash Advance Requisition";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$preqEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Cash Advance Requisition";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $preqName,<br><br><u>Cash Requsition details for $preqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $preqRequisitionDate<br>require date: $preqRequireDate<br>Partial Money $padvAmount tk Approved againest $advAmount tk has been $pAccStatus please Collect date $pcollectionDate from EzzyAccounts, Remaining amounts will be Delivered As soos as possible. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Processed.Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("advance-pending-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("advance-pending-requisition.php"); </script>';  
			}	
	}
	//  mail through for Partial approval
	function p1new_registrations($preqEmail,$preqProject_name,$preqName,$id,$preqRequisitionDate,$preqRequireDate,$advAmount,$padvAmount,$pAccStatus,$pcollectionDate)
	{
		
			$subject="Partial Cash Advance Requisition";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$preqEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Partial Cash Advance Requisition";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $preqName,<br><br><u>Partial Cash Requsition details for $preqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $preqRequisitionDate<br>require date: $preqRequireDate<br>Partial Money $padvAmount tk Approved againest $advAmount tk has been $pAccStatus please Collect date $pcollectionDate from EzzyAccounts, Remaining amounts will be Delivered As soos as possible. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Partial Cash Advance Requisition Processed.Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("Partial-approved-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Partial Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("Partial-approved-requisition.php"); </script>';  
			}	
	}
	//  money delivered
	function new_registrations_delivered($advEmail,$reqProject_name,$advName,$id,$reqRequisitionDate,$reqRequireDate,$advAmount)
	{
		
			$subject="Cash Advance Receive Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Cash Advance Receive Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Cash Requsition details for $reqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $reqRequisitionDate<br>require date: $reqRequireDate<br>Require Money $advAmount tk has been Successfully Delivered from EzzyAccounts. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Cash Advance Received & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("advance-approved-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("advance-approved-requisition.php"); </script>';  
			}	
	}
	//  money delivered
	function partial_new_registrations_delivered($advEmail,$reqProject_name,$advName,$id,$reqRequisitionDate,$reqRequireDate,$advAmount)
	{
		
			$subject="Cash Advance Receive Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Cash Advance Receive Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Cash Requsition details for $reqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $reqRequisitionDate<br>require date: $reqRequireDate<br>Require Money $advAmount tk has been Successfully Delivered from EzzyAccounts. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Cash Advance Received & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("partial-approved-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("partial-approved-requisition.php"); </script>';  
			}	
	}
	//  Debit voucher fully delivered ack by email
	function pd3_new_registrations_delivered($advEmail,$reqProject_name,$advName,$id,$reqRequisitionDate,$reqRequireDate,$advAmount)
	{
		
			$subject="Debit Voucher disbursed Receive Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Debit Voucher disbursed Receive Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Cash Requsition details for $reqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $reqRequisitionDate<br>require date: $reqRequireDate<br>Require Money $advAmount tk has been Successfully Delivered from EzzyAccounts. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Cash Advance Received & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}	
	}
	//  Full cash Advance delivered
	function full_new_delivered($preqEmailDeliver,$preqProject_nameDeliver,$preqNameDeliver,$id,$preqRequisitionDateDeliver,$p1reqRequisitionDate,$padvAmount)
	{
		
			$subject="Full Payment Cash Advance Receive Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$preqEmailDeliver");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Full Payment Cash Advance Receive Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $preqNameDeliver,<br><br><u>Full Payment Cash Requsition details for $preqProject_nameDeliver are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $preqRequisitionDateDeliver<br>require date: $p1reqRequisitionDate<br>Require Money $advAmount tk has been Successfully Delivered from EzzyAccounts. 
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Partial Cash Advance Received & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("partial-approved-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("Partial-approved-requisition.php"); </script>';  
			}	
	}
	//  Balance Adjustment
	function new_BalanceAdjustment($advEmail,$reqProject_name,$advName,$id,$reqRequisitionDate,$reqRequireDate,$reqAmounts,$Acc_refunds,$Acc_forwoards)
	{
		
			$subject="Balance Adjustment(Refund/Forward) Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Balance Adjustment(Refund/Forward) Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Cash Requsition details for $reqProject_name are as follows:</u><br><br>
							Your Requisition id : $id<br>requisition date : $reqRequisitionDate<br>require date: $reqRequireDate<br>Refund Money $Acc_refunds tk or Forwarded Money $Acc_forwoards tk againest  $reqAmounts tk has been Successfully done By EzzyAccounts.<br>
							And also Closed This requisition With Thank's
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Balance Adjustment Adjusted Successfully & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("month-requisition.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Cash Advance Requisition Denay !! ');</script>";
				echo '<script> location.replace("month-requisition.php"); </script>';  
			}	
	}
	//Debit voucher Adjustment
	function new_registrations_voucher($advEmail,$voucherproject_name,$advName,$id,$tlvoucherstatus,$Tlvouchercomments,$reqSubmiteDate,$voucherReqDate,$voucherpcn_no,$voucherpurpose,$advvoucherAmount)
	{
		
			$subject="Debit Voucher Payment(Confirmation) Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Debit Voucher Payment(Confirmation) Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Debit voucher details for $voucherproject_name & PCN ($voucherpcn_no) are as follows:</u><br><br>
							Your Debit Voucher id : $id<br>Submission date : $reqSubmiteDate<br>Require date: $voucherReqDate<br>Payment Money $advvoucherAmount tk has been Successfully $tlvoucherstatus for $Tlvouchercomments By EzzyAccounts.<br>
							And also Closed This requisition With Thank's
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Debit Voucher payment Successfully & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("pending-debit-voucher.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Debit voucher Requisition Denay !! ');</script>";
				echo '<script> location.replace("pending-debit-voucher.php"); </script>';  
			}	
	}
	//partial Debit voucher payment mail
	function pd_registrations_voucher($advEmail,$voucherproject_name,$advName,$id,$reqSubmiteDate,$voucherReqDate,$voucherpcn_no,$voucherpurpose,$advvoucherAmount,$padvvoucherAmount)
	{
		
			$subject="Partial Debit Voucher Payment(Confirmation) Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Partial Debit Voucher Payment(Confirmation) Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Debit voucher details for $voucherproject_name & PCN ($voucherpcn_no) are as follows:</u><br><br>
							Your Debit Voucher id : $id<br>Submission date : $reqSubmiteDate<br>Require date: $voucherReqDate<br>Partial Payment Money $padvvoucherAmount tk againest requisition $advvoucherAmount tk has been Successfully disbursed By EzzyAccounts.<br>
							
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Debit Voucher payment Successfully & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("pending-debit-voucher.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Debit voucher Requisition Denay !! ');</script>";
				echo '<script> location.replace("pending-debit-voucher.php"); </script>';  
			}	
	}
	//Partial Debit voucher payment mail
	function pd1_registrations_voucher($advEmail,$voucherproject_name,$advName,$id,$reqSubmiteDate,$voucherReqDate,$voucherpcn_no,$voucherpurpose,$advvoucherAmount,$padvvoucherAmount)
	{
		
			$subject="Partial Debit Voucher Payment(Confirmation) Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$advEmail");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Partial Debit Voucher Payment(Confirmation) Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $advName,<br><br><u>Debit voucher details for $voucherproject_name & PCN ($voucherpcn_no) are as follows:</u><br><br>
							Your Debit Voucher id : $id<br>Submission date : $reqSubmiteDate<br>Require date: $voucherReqDate<br>Partial Payment Money $padvvoucherAmount tk againest requisition $advvoucherAmount tk has been Successfully disbursed By EzzyAccounts.<br>
							
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Debit Voucher payment Successfully & Forward Email For Confirmation ');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Debit voucher Requisition Denay !! ');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}	
	}
	//Partial Debit voucher payment mail
	function pd2_new_registrations_delivered($preqEmailDeliver,$preqProject_nameDeliver,$preqNameDeliver,$preqid,$reqPcn_no,$preqRequisitionDateDeliver,$p1reqRequisitionDate,$advAmount,$padvAmount)
	{
		
			$subject="Partial Debit Voucher Payment(Delivered Confirmation) Mail";
			$img='<img src="../img/logo.png">';
			// Your message
			$mail = new PHPMailer;
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'no-reply@pricekoto.com';                 // SMTP username
			$mail->Password = 'Pricekoto1';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to
			$mail->setFrom('no-reply@ezzygroup.com', 'EzzyAccounts');
			$mail->addAddress("$preqEmailDeliver");     // Add a recipient
			$mail->addAddress('accounts@ezzygroup.com');  // Name is optional
			$mail->addReplyTo('no-reply@ezzygroup.com', 'EzzyGroup');
			$mail->isHTML(true);                                  // Set email format to HTML
			$mail->Subject ="Partial Debit Voucher Payment(Delivered Confirmation) Mail";
			$mail->Body="
			<!DOCTYPE html>
			<html>
				<head>
					<title>Welcome to EzzyGroup</title>
				</head>
				<body>
					<div style='width:100%;margin:0 auto;'>
						<div style='border: 1px solid #ccc;padding: 14px;background:#f5f5f5;'>
							<center><img src='http://ezzygroup.net/img/logo-large.jpg' style='margin:0 auto;'/>
							
								<address>Dhaka Office : House# 214, Road # 13, New DOHS, Mohakhali, Dhaka-1206.<br>
								Chittagong Office: Road #3,House# 33 (2'nd Floor), O.R Nizam Road,Chittagong.<br>
								Phone: 09606-334455, Mobile:01777-744340, Phone: 031-2552020<br>
								Email:info@ezzygroup.net , www.ezzygroup.net</address>
							</center>
										
						</div>
						<div style='border: 1px solid #ccc;padding: 14px;background-color:#FFF;margin-top:-1px;'><br>
							Dear $preqNameDeliver,<br><br><u>Debit voucher details for $preqProject_nameDeliver & PCN ($reqPcn_no) are as follows:</u><br><br>
							Your Debit Voucher id : $preqid<br>Submission date : $preqRequisitionDateDeliver<br>Require date: $p1reqRequisitionDate<br>Partial Payment Money $padvAmount tk againest requisition $advAmount tk has been Successfully disbursed By EzzyAccounts.<br>
							
							<br><br>
							Kindly click the link to login http://103.78.54.182:9139/ehrm/ <br><br>
							Make your presence known . For more information, 
							contact us.<br><br>
							Thanks & Regards<br>
							EzzyGroup Finance & Accounts<br>
							Tel: +88-028711879-80, Fax: +88- 02-8714623<br>
							Email: accounts@ezzygroup.net 
							<br><br>

							The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
							This information is the property of EzzyGroup and may be confidential. If you are not the intended addressed you 
							should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
							notify the sender immediately and delete this e-mail from your system
							<br><br>
						</div>
					</div>
				</body>
			</html>";
// send email
			if($mail->send()){
				echo "<script language= 'JavaScript'>alert('Partial Debit Voucher payment Successfully disbursed');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}
			else
			{
				echo "<script language= 'JavaScript'>alert('Debit voucher Requisition Denay !! ');</script>";
				echo '<script> location.replace("partial-approved-debit-voucher.php"); </script>';  
			}	
	}
	
?>