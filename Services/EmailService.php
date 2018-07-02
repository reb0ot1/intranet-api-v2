<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 2.7.2018 г.
 * Time: 0:26
 */

namespace Employees\Services;

use Employees\Models\DB\Email;
use Employees\Models\DB\Employee;
use Employees\Config\EmailConfig;
use Employees\Vendor\phpmailer\src\PHPMailer;
use Employees\Vendor\phpmailer\src\Exception;

class EmailService implements EmailServiceInterface
{

    /**
     * @param \Employees\Models\DB\Email $email
     * @return string
     */
    public function sendEmail($email)
    {
        $mail = new PHPMailer();
        $message = "";
        try {
            //Server settings
            $mail->SMTPDebug = EmailConfig::SMTPDebug;
            if (EmailConfig::isSMTP) {
                $mail->isSMTP();
            }

            $mail->Host = EmailConfig::emailHost;
            $mail->SMTPAuth = EmailConfig::SMTPAuth;
            $mail->Username = EmailConfig::Username;
            $mail->Password = EmailConfig::Password;
            $mail->SMTPSecure = EmailConfig::SMTPSecure;
            $mail->Port = EmailConfig::Port;

            //Recipients
            $mail->setFrom(EmailConfig::setFrom);
            $mail->addAddress(EmailConfig::sendTo);
            $mail->addReplyTo(EmailConfig::replyTo);

            //Content
            $mail->isHTML($email->getisHTML());
            $mail->Subject = $email->getSubject();
            $mail->Body = $email->getBody();
            $mail->AltBody = $email->getAltBody();
//            $mail->isHTML(true);                                  // Set email format to HTML
//            $mail->Subject = 'Here is the subject';
//            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();

            $message = 'Message has been sent';

        } catch (Exception $e) {
            $message = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
        }

        return $message;

    }

}