<?php
// PHPMailer Files einbinden
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// SMTP Logindaten einbinden
require_once HOME_DIR . '/config/biblewiki/smtp_mailserver.php';

// User DB Script einbinden
require_once SCRIPT_PATH . '/php/db_user.php';



/* Namespace alias. */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class mail
{
    var $name;

    // Standardwerte

    var $attachment = NULL;
    var $template = 'html_email_template.php';

    ######################################################
    # SET
    ######################################################

    function set_to_userID($toID)
    {
        $this->toID = $toID;

        $columns = array('user_firstname', 'user_lastname', 'user_email');

        $userData = GetData($this->toID, USER_DB, 'users', $columns);

        if (is_array($userData)) {
            $this->toEmail = $userData['user_email'];
            $this->toName = $userData['user_firstname'] . ' ' . $userData['user_lastname'];
        }
    }

    function set_to_email($toEmail)
    {
        $this->toEmail = $toEmail;
    }

    function set_to_name($toName)
    {
        $this->toName = $toName;
    }

    function set_from_email($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    function set_from_name($fromName)
    {
        $this->fromName = $fromName;
    }

    function set_subject($subject)
    {
        $this->subject = $subject;
    }

    function set_attachment($attachment)
    {
        $this->attachment = $attachment;
    }

    function set_title($title)
    {
        $this->title = $title;
    }
    function set_preheader($preheader)
    {
        $this->preheader = $preheader;
    }

    function set_heading($heading)
    {
        $this->heading = $heading;
    }

    function set_text($text)
    {
        $this->text = $text;
    }

    function set_button_text($button_text)
    {
        $this->button_text = $button_text;
    }

    function set_button_link($button_link)
    {
        $this->button_link = $button_link;
    }

    function set_end_text($end_text)
    {
        $this->end_text = $end_text;
    }



    function send_mail()
    {
        // HTML Email Template einbinden
        require_once SCRIPT_PATH . '/php/lib/'.$this->template;

        // Email senden
        return email($this->toEmail, $this->toName, $this->fromEmail, $this->fromName, $this->subject, $this->attachment, $html_template);
    }
}

function email($toEmail, $toName, $fromEmail, $fromName, $subject, $attachment, $body)
{
    if (!isset($fromName) || $fromName === ''){
        $fromName = FROM_NAME;
    }

    if (!isset($fromEmail) || $fromEmail === ''){
        $fromEmail = FROM_EMAIL;
    }

    /* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
    $mail = new PHPMailer(TRUE);

    /* Open the try/catch block. */
    try {

        $mail->CharSet = 'UTF-8';

        $mail->Encoding = 'base64';

        /* Set the mail sender. */
        $mail->setFrom($fromEmail, $fromName);

        /* Set a different reply-to address. */
        //$mail->addReplyTo('vader@empire.com', 'Lord Vader');

        /* Add a recipient. */
        $mail->addAddress($toEmail, $toName);

        /* Add CC and BCC recipients */
        //$mail->addCC('admiral@empire.com', 'Fleet Admiral');
        //$mail->addBCC('luke@rebels.com', 'Luke Skywalker');


        /* Set the subject. */
        $mail->Subject = $subject;

        $mail->isHTML(TRUE);
        $mail->Body = $body;
        // $mail->AltBody = $BodyNoHtml;

        if (isset($attachment)) {
            $mail->addAttachment($attachment);
        }


        /* SMTP parameters. */

        /* Tells PHPMailer to use SMTP. */
        $mail->isSMTP();

        /* SMTP server address. */
        $mail->Host = SMTP_HOST;

        /* Use SMTP authentication. */
        $mail->SMTPAuth = TRUE;

        /* Set the encryption system. */
        $mail->SMTPSecure = 'tls';

        /* SMTP authentication username. */
        $mail->Username = SMTP_USER;

        /* SMTP authentication password. */
        $mail->Password = SMTP_PW;

        /* Set the SMTP port. */
        $mail->Port = SMTP_PORT;



        /* Finally send the mail. */
        $mail->send();

        return ('success');
    } catch (Exception $e) {
        /* PHPMailer exception. */
        return $e->errorMessage();
    } catch (\Exception $e) {
        /* PHP exception (note the backslash to select the global namespace Exception class). */
        return $e->getMessage();
    }
}

