<?php
    $mailto = 'info@nedvedik.eu';
    $subject = 'the subject';
    $message = 'the message';
    $from = 'system@example.net';
    $header = 'From:'.$from;

    if(mail($mailto,$subject,$message,$header)) {
        echo 'Email on the way';
    }
?>
