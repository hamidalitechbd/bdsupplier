<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'pricekoto.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'hr@pricekoto.com';                 // SMTP username
$mail->Password = 'Pricekoto1';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->setFrom('hr@pricekoto.com', 'Mailer');
$mail->addAddress('tahminausa90@gmail.com', 'Tahmina Akhtar');     // Add a recipient
//$mail->addAddress('salman2152@gmail.com');               // Name is optional
$mail->addReplyTo('hr@pricekoto.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = "<html><body><img src='https://pricekoto.com/img/logo.png' width='100px' style='float: right;'/><br><br>Dear Supplier,<br><br>
			A new indent has been raised by <b><u>Tahmina</u></b> with the product details as follows:-<br><br>
			<table cellpadding='6' >
			<tr style='background: #FF4D4D; border-bottom:1px solid #C0C0C0; color:white'><th>Product Name</th><th>Quantity </th></tr>
			<tr><td align='left'>test</td><td align='center'>1.00 rim</td></tr>
			</table><br><br>
			Please click the link below to view and quote on the indent.<br>
			https://pricekoto.com/supplier/ <br><br>
													
			Make your presence known - Promote your products and organisation at Pricekoto. For more information, contact us.<br><br>

			Thank You<br>
			Pricekoto Team<br><br>

			The information contained in this e-mail is intended solely for the person or persons to whom it is addressed. 
			This information is the property of Pricekoto and may be confidential. If you are not the intended addressed you 
			should not distribute, copy or otherwise disclose this e-mail.  If you received this e-mail by mistake, please 
			notify the sender immediately and delete this e-mail from your system<br><br>
			Click <a href='https://pricekoto.com/supplier/index.php?message=unsubscribe'>Unsubscribe</a> from future service email						
</body></html>";
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>