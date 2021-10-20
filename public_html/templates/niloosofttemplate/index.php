<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php

header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

/*
$attributes = [
    'phone' => ['054-7641456'],
    'fullName' => ['כלכלה כלכלה'],
    'applicantID' => ['55555']
];
$app_user_id = '6a92814f-0723-48af-9d6a-8f8b865384ea';

// $attributes = [
//     'phone' => ['052-6409999'],
//     'fullName' => ['נוי מולגה'],
//     'applicantID' => ['205664543']
// ];
// $app_user_id = '8c53d926-28fd-4b73-b513-7c71737d1711';

$session = &JFactory::getSession();
$session->set("applicant_user_id", $app_user_id);
$session->set('user_credentials', $attributes);
*/
$user_credentials = $session->get("applicant_user_id");

if (empty($user_credentials)) {
    if ($_POST["type"] === 'get_api') {

        echo '{"result": "auth required"}';
        die;
    }
    header('Location: login_saml.php' . (isset($_GET['getJobById']) ? '?getJobById=' . $_GET['getJobById'] : ''));
    die;
}
JPluginHelper::importPlugin('nsoft');
$dispatcher = JDispatcher::getInstance();
$dispatcher->trigger('runApi');

include_once JPATH_PLUGINS . '/system/nsoft/config.php';
?>


<html xmlns="http://www.w3.org/1999/xhtml" 
      xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >

    <head>

        <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/almoni-dl-aaa.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/font-awesome.css" type="text/css" />
        <?php
        $doc = JFactory::getDocument();
        $doc->addScript(JUri::base().'/templates/'.$this->template.'/js/niloosoft.js');
        
        include_once JPATH_PLUGINS . '/system/nsoft/conf.php';
        ?>


        <style type="text/css">
            *:focus,*:visited{
                outline:none;
            }
            *, html, body {
                margin:0;
                padding:0;
            }
            ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
                color: #fff;
            }
            :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
                color: #fff;
                opacity: 1;
            }
            ::-moz-placeholder { /* Mozilla Firefox 19+ */
                color: #fff;
                opacity: 1;
            }
            :-ms-input-placeholder { /* Internet Explorer 10-11 */
                color: #fff;
            }
            body {
                font-family: "almoni_dl_aaaregular";
                font-size: 16px;
                position: relative;
                min-width: 1170px;
            }
            .search-title {
                height: 613px;
                width: 100%;
                display: inline-block;
            }
            .search-title .top {
                height: 551px;
                width: 100%;
                position: relative;
            }
            .search-title .bottom {
                height: 62px;
                background-color: #001F5B;
                width: 100%;
            }
            .breadcrumb {
                width: 1140px;
                font-size: 16px;
                position: absolute;
                top: 20px;
                left: 50%;
                margin-left: -570px;
            }
            .breadcrumb p {
                display: inline-block;
                margin-left: 10px;
            }
            .breadcrumb a {
                color: #fff;
                text-decoration: none;
                margin-right: 10px;
            }

            .breadcrumb .level:before {
                content: "\f104";
                font-family: FontAwesome;
                color: #fff;
            }
            .page-title-container {
                width: 1140px;
                position: absolute;
                left: 50%;
                margin-left: -570px;
                top: 210px;
            }
            .page-title {
                font-family: "almoni_dl_aaablack";
                text-align: center;
                font-size: 80px;
                color: #fff;
            }
            .page-title-container .horizontal-line {
                height: 2px;
                width: 67px;
                margin: 0 auto;
                background-color: #68CCE8;
            }
            .main-menu {
                height: 100%;
                margin: 0 auto;
                width: 1170px;
                box-sizing: border-box;
                padding: 0 15px;
                text-align: center;
            }
            .main-menu li {
                border-right: 1px solid #154277;
                display: inline-block;
                font-size: 18px;
                list-style-type: none;
                margin-top: 20px;
                padding: 2px 20px;
            }
            .main-menu li:first-child {
                border: none;
            }
            .main-menu a {
                text-decoration: none;
                color: #fff;
            }
            .video-bg {
                height: 100%;
                width: 100%;
                background: url("<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/header.jpg") no-repeat;
                background-size: cover;
                background-position: center top;
            }
            
        </style>
        <jdoc:include type="head" />

        <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="<?php echo nsoftConfig::$dropboxAppKey; ?>"></script>
        <script src="//js.live.net/v5.0/wl.js"></script>

        <script>
            var isLogin = <?= !empty($user_credentials) ? 1 : 0; ?>;

            jQuery(document).ajaxStart(function () {
                jQuery('body').addClass('wait');
            });
            jQuery(document).ajaxComplete(function (event, jqXHR, ajaxOptions) {
                jQuery('body').removeClass('wait');
            });
        </script>
        <script>
            // Analitics Code for Colman - Academy - Google Analytics – October 2015
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-1234780-10', 'auto');
            ga('send', 'pageview');
            if (isLogin){
                console.log("Login: ")
            }
        </script>  
        <script>
            // Crazy EGG code
            setTimeout(function(){var a=document.createElement("script");
            var b=document.getElementsByTagName("script")[0];
            a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0026/1134.js?"+Math.floor(new Date().getTime()/3600000);
            a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
        </script>
    </head>

    <body>
        <!-- Google Tag Manager -->
        <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WZDBWR"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WZDBWR');</script>
        <!-- End Google Tag Manager -->

        <jdoc:include type="modules" name="wedo_modul_header" />

        <section class="search-title" dir="rtl">

            <div class="top">
                <div class="video-bg">

                </div>

                <jdoc:include type="modules" name="wedo_modul_breadcrumb" />
                <jdoc:include type="modules" name="wedo_modul_search_basic" /> 

            </div>

            <div class="bottom">
                <ul class="main-menu">
                    <li><a href="#mod_category_section">בחירת תחום</a></li>
                    <li><a href="#mod_searcha_advanced_section" id="menu_mod_searcha_advanced_section">סוכן חכם</a></li>

                    <li><a href="<?php
                        if (!empty($user_credentials))
                            echo("#mod_nsoft_section");
                        else
                            echo "login_saml.php";
                        ?> ">הלוח שלי</a></li> 
                    <li><a href="#mod_resume_section">העלאת קורות חיים</a></li>
                </ul>
            </div>
        </section>

        <jdoc:include type="modules" name="wedo_modul_board" /> 
        <jdoc:include type="modules" name="wedo_modul_search_advanced" /> 
        <jdoc:include type="modules" name="wedo_modul_category" />
        <jdoc:include type="modules" name="wedo_modul_resume_send" /> 

        <jdoc:include type="modules" name="wedo_modul_footer" /> 
        <p style="text-align: center; font-size: 14px; padding: 6px;">
            <a href="https://niloosoft.com/he/" target="_blank" rel="external" style="text-decoration: none;">POWERED BY NILOOSOFT HUNTER EDGE</a>
        </p>
    </body>
</html>
