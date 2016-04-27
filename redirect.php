<?php
/**
 * Created by PhpStorm.
 * User: Dominik
 * Date: 2.4.2016
 * Time: 16:10
 */

$page = htmlspecialchars($_GET["page"]);

if($page == ""){
    header("Location: http://chochrun.ddns.net/");
}


?>

<!doctype html>
<html>
    <head>
        <title>Chochrun.eu - Redirect</title>
        <meta http-equiv="refresh" content="5;<?= $page ?>">
        <script src="https://code.jquery.com/jquery-1.12.2.min.js"></script>
        <script>
            $(function(){
                $time = 5;
                setInterval(function(){
                    $time--;
                    $("#time").html($time);
                    if($time == 0){
                        setTimeout(function(){$("#text").html("Přesměrování probíhá...")},1000);
                    }
                },1000)
            });
        </script>
    </head>
    <body>
        <h1>Redirect</h1>
        <p><a href="<?= $page ?>">Klikněte zde pro okamžité přesměrování</a></p>
        <p id="text">Budete přesměrováni za <span id="time">5</span> vteřin.</p>
        <img src="images/paralax_banner/1.gif">
    </body>
</html>
