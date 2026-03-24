<?php

require APPPATH . 'ThirdParty/PHPMailer/src/Exception.php';
require APPPATH . 'ThirdParty/PHPMailer/src/PHPMailer.php';
require APPPATH . 'ThirdParty/PHPMailer/src/SMTP.php';

//use App\Libraries\PhpMailerLib;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function phpmail($from_name='CLSU MISO',$to_email,$to_name,$subject='',$body='', $cc='',$bcc='',$attach_filename='',$wordwrap=100) 
{

        $mail = new PHPMailer(true);


        //try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Change to your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Port       = 465; // 587;
            $mail->Username   = 'clsu.miso.mailer@gmail.com'; // Your email
            $mail->Password   = 'tlqpuwaxhgtrhqle';//'dmukqkqhvkwtvvra'; // Your email password
            $mail->SMTPSecure = 'ssl'; // tls

            
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            //$mail->SMTPDebug = 2; // Set to 3 or 4 for more details
            //$mail->Debugoutput = 'html';
        
            
            // Sender
            $mail->setFrom('clsu.miso.mailer@gmail.com', $from_name);
            // $mail->FromName = 'CLSU MISO';

            
            //  Recipient
            //$mail->addAddress('benj0143@yahoo.com', 'Recipient Name'); 
            if(is_array($to_email)){
                $mail->addAddress($to_email[0], $to_name[0]);
                if(@$to_email[1]!=''){
                    $mail->addAddress($to_email[1], $to_name[1]);
                }
                if(@$to_email[2]!=''){
                    $mail->addAddress($to_email[2], $to_name[2]);
                }
            } else {
                $mail->addAddress($to_email, $to_name);
            }
            
            
            if(@$cc!=''){
                $mail->addCC($cc);
            }
            if(@$bcc!=''){
                $mail->addBCC($bcc);
            }

            $mail->WordWrap = $wordwrap;    
            

            // Email Content
            $mail->isHTML(true);
            
            $mail->Subject = $subject; //'Test Email from PHPMailer';
            $mail->Body    = $body; //'<h3>Hello, this is a test email from CodeIgniter 4 using PHPMailer!</h3>';

            
            if($attach_filename!=''){
                $mail->addAttachment("".$attach_filename);
            }
            /*if(@$attach_filename2!=''){
                $mail->addAttachment("".$attach_filename2);
            }*/
        
        
            // Send Email
            //$mail->send();
            //echo 'Email sent successfully!';
            
            if(!$mail->send()){
                echo 'Message could not be sent.<br>';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else{
                return true;
            }
        
        //} catch (Exception $e) {
        //    echo "Email could not be sent.<br>Error: {$mail->ErrorInfo}";
        //}
        
    
}