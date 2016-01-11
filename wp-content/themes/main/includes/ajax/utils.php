<?php
    function sendmailto($ahlu){

$to_email= get_userdata(1);
$to_email = $to_email->user_email;
$subject = "Email Contact by DENTAL ESSENCE PTE Ltd  Website";
$name = $ahlu->txtName;
$email = $ahlu->txtEmail;
$phone = $ahlu->txtPhone;
$comment = $ahlu->txtComment;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$email . "\r\n";
$message="
    <table>
        <tr>
            <td style=\"vertical-align:top;\">
                <table>
                    <tr style=\"padding-bottom:5px;\">
                        <td><span style=\"font-weight:bold;\">Subject</span>: ".$subject."</td> 
                    </tr>
                    <tr style=\"padding-bottom:5px;\">
                        <td><span style=\"font-weight:bold;\">Name</span>: ".$name."</td> 
                    </tr>
                    <tr style=\"padding-bottom:5px;\">
                        <td><span style=\"font-weight:bold;\">Email</span>: ".$email."</td>
                    </tr>
                    <tr style=\"padding-bottom:5px;\">
                        <td><span style=\"font-weight:bold;\">Phone number</span>: ".$phone."</td>
                    </tr>                                                                                       <tr style=\"padding-bottom:5px;\">
                        <td><span style=\"font-weight:bold;\">Comment</span>: ".$comment."</td>
                    </tr>                                                                           
                </table>
            </td>
        </tr>
    </table>
";
    die(mail($to_email, $subject, $message, $headers)); 
    }
?>