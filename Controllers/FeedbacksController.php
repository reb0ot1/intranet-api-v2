<?php

namespace Employees\Controllers;

use Employees\Models\Binding\Feedback\FeedbackBindingModel;
use Employees\Core\DataReturnInterface;

class FeedbacksController
{

    private $dataReturn;

    public function __construct(DataReturnInterface $dataReturn)
    {
        $this->dataReturn = $dataReturn;
    }

    public function option()
    {

    }

    public function testGet()
    {
//        $from = "feedback@q1q1.eu";
//        $to = "svetoslav.bozhinov@cquest-rc.com";
//        $subject = "intranet testing phase";
//        $body = "Hi there";
//        $host = "mail.q1q1.eu";
//        $username = "feedback@q1q1.eu";
//        $password = "Awdzs123";
//        $headers = array('From'=>$from, 'To'=>$to, 'Subject'=>$subject);
//        $smtp = \Mail::factory('smtp', array('host'=>$host, 'auth'=>true, 'username'=> $username, 'password'=>$password));
//
//        $mail = $smtp->send($to, $headers, $body);
//
//        if (\PEAR::isError($mail)) {
//            echo("<p>" . $mail->getMessage() . "</p>");
//        } else {
//            echo("<p>Message successfully sent!</p>");
//        }
//
//            return $this->dataReturn->errorResponse(400,"The employee was not removed. Please try again");
////        }
        
    }

    public function sendfeedback(FeedbackBindingModel $feedback)
    {

        $from = "feedback@q1q1.eu";
        $to = "martin.vetov@gemseek.com";
//        $to = "sbojinov@yahoo.com";
        $subject = "intranet testing phase";
        $message = "<html><body>";
        $message .= '<p>Send by: '.$feedback->getEmail().'</p>';
        $message .= '<p>'.$feedback->getFeedbackString().'</p>';;
        $message .= "</body></html>";
//        $message = "Send by: ".$feedback->getEmail()."\r\n Feedback: \r\n".$feedback->getFeedbackString();
        $host = "mail.q1q1.eu";
        $username = "feedback@q1q1.eu";
        $password = "Awdzs123";
        $mime = "1.0";
        $port = "587";
        $content = "text/html; charset=utf-8\r\n\r\n";
//        $port = "25";
        $headers = array('From'=>$from, 'To'=>$to, 'Subject'=>$subject,'MIME-Version' => $mime, 'Content-type' => $content);
//        $headers = array('From'=>$from, 'To'=>$to, 'Subject'=>$subject);
        $smtp = \Mail::factory('smtp', array('host'=>$host, 'port' => $port, 'auth'=>true, 'username'=> $username, 'password'=>$password));

        $mail = $smtp->send($to, $headers, $message);

        if (\PEAR::isError($mail)) {
            return $this->dataReturn->errorResponse(400,"The feedback was not sent. Please try again");
        }

//        $header = "From: ".$feedback->getEmail()."\r\n";
//        $header .= "MIME-Version: 1.0\r\n";
//        $header .= "Content-Type: text/html; charset=UTF-8\r\n";
//
//        $sentresult = mail("m.vetov@gmail.com","Intranet feedback",$feedback->getFeedbackString(), $header);
//
//        if (!$sentresult) {
//            return $this->dataReturn->errorResponse(400,"The feedback was not sent. Please try again");
//        }

        return $this->dataReturn->successResponse(200,"The was sent successfully.");
    }
}