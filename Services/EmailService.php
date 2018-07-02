<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.7.2018 г.
 * Time: 0:26
 */

namespace Employees\Services;

use Employees\Vendor\phpmailer\src\PHPMailer;
use Employees\Vendor\phpmailer\src\Exception;

class EmailService implements EmailServiceInterface
{

    public function sendEmail()
    {
        $mail = new PHPMailer();
        $message = "";
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'sbozhinow@gmail.com';                 // SMTP username
            $mail->Password = 'awdzs123';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('sbojinov@yahoo.com', 'Test test');     // Add a recipient
//            $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
//            $mail->addCC('cc@example.com');
//            $mail->addBCC('bcc@example.com');

            //Attachments
//            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            $message = 'Message has been sent';
        } catch (Exception $e) {
            $message = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
        }

        return $message;
    }

}