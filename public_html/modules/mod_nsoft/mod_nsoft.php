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
$app = JFactory::getApplication();
$dir = dirname(__FILE__);
//die;
require_once $dir . '/helper.php';


$session = &JFactory::getSession();

$user_credentials = $session->get("applicant_user_id");
if (empty($user_credentials)) {
  
    echo '{"result": "auth required"}';
    die;
}

JPluginHelper::importPlugin('nsoft');
//$api = new PlgSystemNsoft();
$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger('runApi');
$results = $results[0];

if ($app->input->get->get('type', FALSE) === 'get_api' && $app->input->get->get('controller', FALSE) === "jobs") {


    require_once dirname(__FILE__) . '/controllers/apiController.php';

    $obj = new apiController($results->usernameToken, $results->passwordToken);

    $params['fileId'] = $app->input->get->get('itemId', FALSE);
    

    $obj->{$app->input->get->get('action', FALSE)}($params);
}

if ($app->input->post->get('type', FALSE) === 'get_api') {

    if ($app->input->post->get('controller', FALSE) != "jobs") {
        return;
    }


    if (empty($user_credentials)) {
        echo '{"result": "auth required"}';
        die;
    }


    require_once dirname(__FILE__) . '/controllers/apiController.php';

    $obj = new apiController($results->usernameToken, $results->passwordToken);
    // $obj = new apiController("a", "b");

    if (isset($_POST['apply-job-dropbox-fileUrl']) || isset($_POST['apply-job-skydrive-fileUrl'])) {
        $params['fileFromComp'] = $_FILES;
        $params['apply-job-dropbox-fileName'] = $_POST['apply-job-dropbox-fileName'];
        $params['apply-job-dropbox-fileUrl'] = $_POST['apply-job-dropbox-fileUrl'];
        $params['apply-job-skydrive-fileName'] = $_POST['apply-job-skydrive-fileName'];
        $params['apply-job-skydrive-fileUrl'] = $_POST['apply-job-skydrive-fileUrl'];
        $params['jobCode'] = $_POST['jobCode'];
        if (isset($_POST['selectedFile'])) {
            $params['selectedFile'] = $_POST['selectedFile'];
        }
        if (isset($_POST['selectedCv'])) {
            $params['selectedCv'] = $_POST['selectedCv'];
        }
    }

    if (isset($_FILES['uploadFile'])) {

        $params['uploadFile'] = $_FILES['uploadFile'];
        $params['fileId'] = $_POST['itemId'];
        $params['isCV'] = $_POST['isCV'];
    }

    $params['fileId'] = $app->input->post->get('itemId', FALSE);
    $params['jobId'] = $app->input->post->get('jobId', FALSE);
    $params['jobId'] = $_POST['jobId'];
    $params['jobTitle'] = $app->input->post->get('jobTitle', '', 'STR');
    $params['cont_type'] = $app->input->post->get('cont_type', FALSE);
    $params['lastId'] = $app->input->post->get('lastId', FALSE);
    $params['countPerPage'] = $app->input->post->get('countPerPage', FALSE);
    $params['apply-job-upload-file-type'] = $app->input->post->get('apply-job-upload-file-type', FALSE);
    $params['apply-job-comment'] = $_POST['apply-job-comment'];

    $obj->{$app->input->post->get('action', FALSE)}($params);
} else {
    if (empty($user_credentials)) {
        return;
    }

    //  var_dump(rand());
    $data = modNSoftHelper::getAction($app->input->post->get('type', FALSE), $app->input->post->get('type', FALSE));
    require JModuleHelper::getLayoutPath('mod_nsoft');
}
