<?php

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'kondiamela@gmail.com');
define('SMTP_PASS', 'pvam bpke yzzo pviu');
define('FROM_EMAIL', 'supportticket@queuebankms.com');
define('FROM_NAME', 'QueueBank Support');


function send_email($to_email, $to_name, $subject, $body, $is_html = true) {
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';
    require_once 'PHPMailer/Exception.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    try {

        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        

        $mail->setFrom(SMTP_USER, FROM_NAME);
        $mail->addAddress($to_email, $to_name);
        $mail->addReplyTo(FROM_EMAIL, FROM_NAME);
        

        $mail->isHTML($is_html);
        $mail->Subject = $subject;
        $mail->Body = $body;
        

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
        return false;
    }
}


function send_email_simple($to_email, $subject, $body) {
    $headers = "From: " . FROM_EMAIL . "\r\n";
    $headers .= "Reply-To: " . FROM_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    return mail($to_email, $subject, $body, $headers);
}
?>
