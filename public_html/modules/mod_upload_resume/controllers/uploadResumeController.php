<?php

/**
 * Description of categoryController
 *
 * @author Armen
 */
defined('_JEXEC') or die;

class uploadResumeController{

    public function index(){
    	$doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base().'modules/mod_upload_resume/assets/css/style.css');
        $doc->addScript(JUri::base().'modules/mod_upload_resume/assets/js/mod_upload_resume.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base().'plugins/system/nsoft/assets/js/css/style.css');


    }
}
