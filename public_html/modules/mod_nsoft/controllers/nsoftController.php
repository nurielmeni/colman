<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nsoftController
 *
 * @author Armen
 */
defined('_JEXEC') or die;

class nsoftController {

    public function index() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base() . 'modules/mod_nsoft/assets/css/mod_nsoft.css');
        $doc->addScript(JUri::base() . 'modules/mod_nsoft/assets/js/mod_nsoft.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery.pagination.css');

        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/sumoselect.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/fileList/jquery.generateFile.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.sumoselect.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jobList/jquery.joblist.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.pagination.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/style.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/popup.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/fileList/jquery.fileList.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/magnific-popup.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.magnific-popup.js', "text/javascript", false, FALSE);
    }

}
