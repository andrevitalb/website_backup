<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	date_default_timezone_set('America/Mexico_City');

	require_once('PHPMailer/src/Exception.php');
	require_once('PHPMailer/src/PHPMailer.php');
	require_once('PHPMailer/src/SMTP.php');
	
	require_once('./secrets.php');

	$bot = true;

	if(isset($_POST['g-recaptcha-response'])) $captcha = $_POST['g-recaptcha-response'];
	else $captcha = false;

	if(!$captcha) echo $captcha;
	else {
		$secretKey = '6Le3Kt4UAAAAAIk_FhWh2W0TTruv816z_De2M92L';
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
		$response = json_decode($response);
		if($response->success == true && $response->score > .5) $bot = false;
	}

	if(!$bot){
		$to = 'santiago_vital@outlook.com';

		$contactName = $_POST['contactName'];
		$contactEmail = $_POST['contactEmail'];
		$contactPhone = $_POST['contactPhone'];
		$contactSubject = $_POST['contactSubject'];
		$contactMessage = $_POST['contactMessage'];

		$body = "
		<html>
		<head>
		<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&display=swap' rel='stylesheet'>
		<title>$contactSubject</title>
		</head>
		<body>
		<table width='50%' border='0' align='center' cellpadding='0' cellspacing='0'>
		<tr>
		<td colspan='2' align='center' valign='top'><img style=' width: 100px; margin-top: 15px; ' src='https://www.andrevital.com/images/logo_negative.svg'></td>
		</tr>
		<tr>
		<td width='50%' align='right'>&nbsp;</td>
		<td align='left'>&nbsp;</td>
		</tr>
		<tr>
		<td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 5px 7px 0;'><strong>Nombre:</strong></td>
		<td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 0 7px 5px;'>$contactName</td>
		</tr>
		<tr>
		<td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 5px 7px 0;'><strong>Email:</strong></td>
		<td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 0 7px 5px;'>$contactEmail</td>
		</tr>
		<tr>
		<td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 5px 7px 0;'><strong>Teléfono:</strong></td>
		<td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 0 7px 5px;'>$contactPhone</td>
		</tr>
		<tr>
		<td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 5px 7px 0;'><strong>Mensaje:</strong></td>
		<td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 0 7px 5px;'>$contactMessage</td>
		</tr>
		<tr>
		<td colspan='2' align='center' valign='top' style='font-family: Source Sans Pro, sans-serif; font-size: 15px; color:#000; padding:7px 5px 7px 0;'><strong>Enviado el:</strong> ".date('d-m-Y H:i:s')."</td>
		</tr>
		<tr>
		<td width='50%' align='right'>&nbsp;</td>
		<td align='left'>&nbsp;</td>
		</tr>
		<tr>
		<td width='50%' align='right'>&nbsp;</td>
		<td align='left'>&nbsp;</td>
		</tr>
		</table>
		</body>
		</html>
		";

		$mail = new PHPMailer();

		$mail->isSMTP();
	 	$mail->isHTML(true);
	 	$mail->CharSet = 'UTF-8';
	 	$mail->Encoding = 'base64';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Username = $gmailMail;
		$mail->Password = $gmailPwd;

		$mail->SetFrom('s.andre.vital@gmail.com', 'Contacto Página Web');
		$mail->AddAddress($to);
		$mail->Subject = 'Contacto Página Web';
		$mail->Body = $body;
	 	$mail->AltBody = $body;

		if(!$mail->Send()) $error = 'Algo salíó mal :(. '.$mail->ErrorInfo;
		else $error = 'OK';

		echo $error;
	}
	else echo "Ocurrió un problema, intenta de nuevo más tarde";
?>