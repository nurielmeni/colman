<?php

/**
 * Hello World! Module Entry Point
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';


$session = &JFactory::getSession();

$user_credentials = $session->get("applicant_user_id");
if (empty($user_credentials)) {
  
    echo '{"result": "auth required"}';
    die;
}


JPluginHelper::importPlugin('nsoft');
$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger('runApi');
$results = $results[0];

if ($app->input->post->get('type', FALSE) === 'get_api') {

    if ($app->input->post->get('controller', FALSE) != "uploadResume") {
        return;
    }

    require_once dirname(__FILE__) . '/controllers/apiController.php';

    $obj = new apiController($results->usernameToken, $results->passwordToken);
    //$obj = new apiController("a", "b");
    $params["resume-modul-fullname"] = $_POST['resume-modul-fullname'];
    $params["resume-modul-idnumber"] = $_POST['resume-modul-idnumber'];
    $params['resume-modul-dropbox-fileName'] = $_POST['resume-modul-dropbox-fileName'];
    $params['resume-modul-dropbox-fileUrl'] = $_POST['resume-modul-dropbox-fileUrl'];
    $params['resume-modul-skydrive-fileName'] = $_POST['resume-modul-skydrive-fileName'];
    $params['resume-modul-skydrive-fileUrl'] = $_POST['resume-modul-skydrive-fileUrl'];
    $params['apply-job-upload-file-type'] = $_POST['apply-job-upload-file-type'];
    $params["resume-modul-localFile"] = $_FILES['resume-modul-localFile'];

    $obj->{$app->input->post->get('action', FALSE)}($params);
} else {
    $data = ModUploadResumeHelper::getAction($app->input->post->get('type', FALSE), $app->input->post->get('type', FALSE));
    require JModuleHelper::getLayoutPath('mod_upload_resume');
}
