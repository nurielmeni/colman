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

class searchController {

    public function index() {

        $doc = JFactory::getDocument();
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery-ui.min.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery-ui.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery-ui.theme.css');
        $doc->addStyleSheet(JUri::base() . 'modules/mod_search/assets/css/style.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/sumoselect.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.sumoselect.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'modules/mod_search/assets/js/mod_search.js');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.multiselect.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery.multiselect.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.multiselect.filter.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery.multiselect.filter.css');

        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery.pagination.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jobList/jquery.joblist.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.pagination.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/style.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/popup.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/fileList/jquery.fileList.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/magnific-popup.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.magnific-popup.js', "text/javascript", false, FALSE);
    }

}
