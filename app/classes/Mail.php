<?php


namespace App\Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mail
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer;
        $this->setUp();
    }

    public function setUp()
    {
        $this->mail->isSMTP();
        $this->mail->Mailer = 'smtp';
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = 'tls';

        $this->mail->Host = getenv('SMTP_HOST');
        $this->mail->Port = getenv('SMTP_PORT');

        $environment = getenv('APP_ENV');
        if($environment === 'local'){
            $this->mail->SMTPDebug = 2;
        }

        //auth info
        $this->mail->Username = getenv('EMAIL_USERNAME');
        $this->mail->Password = getenv('EMAIL_PASSWORD');

        $this->mail->isHTML(true);

        //sender info
        $this->mail->From = getenv('ADMIN_EMAIL');
        $this->mail->FromName = getenv('APP_NAME');

//        $this->mail->addCustomHeader('MIME-Version: 1.0');
//        $this->mail->addCustomHeader('Content-Type: text/html; charset=ISO-8859-1');

        $this->mail->smtpConnect( array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                "allow_self_signed" => true
            )
        ));
    }

    public function send($data,$send_to)
    {

        $this->mail->addAddress($data['to']);
        $this->mail->Subject = $data['subject'];
//        $this->mail->Body = make($data['view'], array('data' => $data['body']));
        if($send_to ==='supervisor')
            $this->mail->Body = $this->supervisorBody($data);
        else
            $this->mail->Body = $this->employeeBody($data);
        $this->mail->send();
    }

    public function supervisorBody($data)
    {
        $url = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Request For Action</title>
                </head>
                <body>';
        $url.= '<p>Dear supervisor, employee ' . Session::get('user_email') .
            ' requested for some time off, starting on ' . $data['date_from'] .
            ' and ending on ' . $data['date_to'] . ' stating the reason: ' . $data['reason'] .
            '</p>
            <p>Click on one of the below links to approve or reject the application:
            </p></br><a class="button" href="' . getenv('APP_URL') . '/auth/evaluate/' . $data['procession_code'] . '/1' .'" >Approve</a></br><a class="button" href="' . getenv('APP_URL') . '/auth/evaluate/' . $data['procession_code'] . '/0' .'" >Reject</a></body></html>';


        return $url;
    }

    public function employeeBody($data)
    {
        $url = '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title></title>
                </head>
                <body>';
        $url.= '</h1><p>Dear employee, your supervisor has ' . $data['status'] . ' your application
                submitted on ' . $data['submitted_date'] . '</p></body></html>';

        return $url;
    }




}