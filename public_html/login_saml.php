
<style>


    .button {
        background-color: #008CBA; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;

    }
</style>
<?php

require_once('simplesamlphp/lib/_autoload.php');


$as = new SimpleSAML_Auth_Simple('colman-sp');

$returnTo = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . '/index.php?';

foreach ($_GET as $key => $value) {
    $returnTo .= $key . '=' . $value . '?&';
}
$returnTo = rtrim($returnTo, '?&');
$as->requireAuth(array('ReturnTo' => $returnTo, 'RelayState' => $returnTo));


$attributes = $as->getAttributes();

define('_JEXEC', 1);
define('JPATH_BASE', realpath(dirname(__FILE__)));
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );
require_once (JPATH_BASE . "/plugins/system/nsoft/nsoft.php");

jimport('cms.plugin.helper');


$mainframe = &JFactory::getApplication('site');
$mainframe->initialise();
JPluginHelper::importPlugin('system', 'nsoft');
$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger('runApi');
$results = $results[0];

$user_toke = $results->usernameToken;
$password_token = $results->passwordToken;

$nlsCards = new nlsCards($user_toke, $password_token);

$app_user_id = null;
try {

    $app_user_id = $nlsCards->getAppliantByUserName($attributes["applicantID"][0])->GetCardIdByUserName2Result;
    if (empty($app_user_id)) {
        throw new Exception("");
    }
} catch (Exception $exc) {
    echo '<div class="errorDIV" dir="rtl" style="background-color: rgba(255,0,0,0.1); text-align: center ; margin-top: 250px;font-family: arial;"><h1>תקלה: לא ניתן להציג נתונים למשתמש זה. אנא פנה/י ל-helpdesk בטלפון 03-6963434.</h1><a class="button" href="https://www.colman.ac.il/">לאתר המכללה</a></div>';
    die;
}


$session = &JFactory::getSession();

$session->set("applicant_user_id", $app_user_id);
$session->set('user_credentials', $attributes);

$rediret_Header = "Location:" . $returnTo;
header($rediret_Header);
?>




