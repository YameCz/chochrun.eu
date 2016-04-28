<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$maintenance = file_get_contents("udrzba/status.txt");
$ip=$_SERVER["REMOTE_ADDR"];
$whitelist = file_get_contents("udrzba/whitelist.txt");
$whitelist = explode("\n", $whitelist);
if($maintenance == 1 && !in_array($ip,$whitelist)){
    header("Location: /udrzba/");
}
$root_url = file_get_contents("domain.txt");
?>
<!DOCTYPE htm>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Chochrun.eu</title>
    <link href="http://fonts.googleapis.com/css?family=Oswald:400,700,300" rel="stylesheet" type="text/css">
    <!-- Included CSS Files -->
    <link rel="stylesheet" href="stylesheets/main.css">
    <link rel="stylesheet" href="stylesheets/devices.css">
    <link rel="stylesheet" href="stylesheets/paralax_slider.css">
    <link rel="stylesheet" href="stylesheets/post.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="stylesheets/jquery.fancybox1c51.css?v=2.1.2" type="text/css"  media="screen">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <script src="http://code.jquery.com/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="javascript/ee.js"></script>
    <script type="text/javascript" src="javascript/getTweet.js"></script>
    <script type="text/javascript" src="javascript/jquery.bxSlider.min.js"></script>
    <script type="text/javascript" src="javascript/jquery.carouFredSel-6.1.0.js"></script>
    <script type="text/javascript" src="javascript/jquery.cslider.js"></script>
    <script type="text/javascript" src="javascript/jquery.fancybox62ba.js"></script>
    <script type="text/javascript" src="javascript/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="javascript/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="javascript/modernizr.custom.28468.js"></script>
    <script type="text/javascript" src="javascript/snow.js"></script>
</head>

<body>

<!--********************************************* Main wrapper Start *********************************************-->
<div id="footer_image">
    <div id="main_wrapper">

        <!--********************************************* Logo Start *********************************************-->
        <div id="logo"> <a href="<?= $root_url ?>"><img alt="alt_example" src="images/logo.png" ></a>
            <div id="social_ctn">

                <a class="social_t"><img alt="alt_example" src="images/social_tleft.png"></a>

                <a href="#" id="facebook"><img alt="alt_example" src="images/blank.gif" width="100%"/></a>
                <a href="#" id="google_plus"><img alt="alt_example" src="images/blank.gif" width="100%"/></a>
                <a href="#" id="you_tube"><img alt="alt_example" src="images/blank.gif" width="100%"></a>

                <a class="social_t" ><img alt="alt_example" src="images/social_tright.png"></a>

            </div>

        </div>
        <!--********************************************* Logo end *********************************************-->

        <!--********************************************* Main_in Start *********************************************-->
        <div id="main_in">

            <!--********************************************* Mainmenu Start *********************************************-->
            <div id="menu_wrapper">
                <div id="menu_left"></div>
                <ul id="menu">
                    <li><a href="<?= $root_url ?>"><i class="fa fa-home"></i>Domů</a></li>
                    <li><a href="<?= $root_url ?>/novinky"><i class="fa fa-newspaper-o"></i>Novinky</a></li>
                    <li><a href="<?= $root_url ?>/jak-se-pripojit"><i class="fa fa-plug"></i>Jak se připojit?</a></li>
                    <li><a href="<?= $root_url ?>/podminky-sluzby"><i class="fa fa-exclamation-triangle"></i>Podmínky služby</a></li>
                    <li><a href="<?= $root_url ?>/kontakt"><i class="fa fa-envelope"></i>Kontakt</a></li>
                    <li><a href="<?= $root_url ?>/o-nas"><i class="fa fa-pencil"></i>O nás</a></li>
                    <li><a href="<?= $root_url ?>/donate"><i class="fa fa-money"></i> Donate</a></li>
                </ul>
                <a href="#" id="pull">Menu</a>
                <div id="menu_right"></div>
                <div class="clear"></div>
            </div>

            <!--********************************************* Mainmenu end *********************************************-->


            <?php
            #remove the directory path we don't want
            $request  = str_replace("local.chochrun.eu/", "", $_SERVER['REQUEST_URI']);

            #split the path by '/'
            $params = explode("/", $request);
            $page = $params[1];
            $file = $page.".php";
            if(file_exists($file)){
                include $file;
            }else{
                include "domu.php";
            }

            ?>


        </div>
        <!--********************************************* Main_in end *********************************************-->

        <a id="cop_text" href="#">Webserver: <?= apache_get_version() ?> | Verze PHP: <?= phpversion(); ?></a>
    </div>
</div>
<!--********************************************* Main wrapper end *********************************************-->

<!--******* Javascript Code for the Hot news carousel *******-->
<script type="text/javascript">
    $(document).ready(function() {

        // Using default configuration
        $("#sd").carouFredSel();

        // Using custom configuration
        $("#hot_news_box").carouFredSel({
            items				: 3,
            direction			: "right",
            prev: '#prev',
            next: '#next',
            scroll : {
                items			: 1,
                height			: 250,
                easing			: "quadratic",
                duration		: 2000,
                pauseOnHover	: true
            }
        });
    })
</script>


<!--******* Javascript Code for the Main banner *******-->
<script type="text/javascript">
    $(function() {

        $('#da-slider').cslider({
            autoplay	: true,
            bgincrement	: 450,
            interval	: 7000
        });

    });
</script>

<!--******* Javascript Code for the image shadowbox *******-->
<script type="text/javascript">
    $(document).ready(function() {
        /*
         *  Simple image gallery. Uses default settings
         */

        $('.shadowbox').fancybox();
    });
</script>

<!--******* Javascript Code for the menu *******-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#menu li').bind('mouseover', openSubMenu);
        $('#menu > li').bind('mouseout', closeSubMenu);

        function openSubMenu() {
            $(this).find('ul').css('visibility', 'visible');
        };

        function closeSubMenu() {
            $(this).find('ul').css('visibility', 'hidden');
        };
    });
</script>

<script type="text/javascript">
    $(function() {
        var pull    = $('#pull');
        menu 		= $('ul#menu');

        $(pull).on('click', function(e) {
            e.preventDefault();
            menu.slideToggle();
        });

        $(window).resize(function(){
            var w = $(window).width();
            if(w > 767 && $('ul#menu').css('visibility', 'hidden')) {
                $('ul#menu').removeAttr('style');
            };
            var menu = $('#menu_wrapper').width();
            $('#pull').width(menu - 20);
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        var menu = $('#menu_wrapper').width();
        $('#pull').width(menu - 20);
    });
</script>
<audio id="buzzer" src="mp3/fetak.mp3"></audio>
<audio id="lol" src="mp3/arwi.mp3"></audio>
</body>
</html>
