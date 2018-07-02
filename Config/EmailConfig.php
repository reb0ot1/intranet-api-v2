<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 3.7.2018 г.
 * Time: 0:02
 */

namespace Employees\Config;


class EmailConfig
{

    const SMTPDebug = 0; //// 0 = off (for production use, No debug messages) // 1 = client messages // 2 = client and server messages
    const isSMTP = true; // Set mailer to use SMTP
    const emailHost = 'smtp.gmail.com'; // Specify main and backup SMTP servers
    const SMTPAuth = true; // Enable SMTP authentication
    const Username = 'msm.company.int';  // SMTP username
    const Password = 'Qwerty123@';  // SMTP password
    const SMTPSecure = 'tls';  // Enable TLS encryption, `ssl` also accepted
    const Port = 587;    // TCP port to connect to

    const sendTo = "sbozhinow@gmail.com";
    const setFrom = "msm.company.int@gmail.com";
    const replyTo = "msm.company.int@gmail.com";

}