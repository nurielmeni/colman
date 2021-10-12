<?php

/**
 * Description of categoryController
 *
 * @author Armen
 */
defined('_JEXEC') or die;

class categoryController {

    public function index() {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base() . 'modules/mod_category/assets/css/style.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/sumoselect.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.sumoselect.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'modules/mod_category/assets/js/mod_category.js', "text/javascript", false, FALSE);

        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/style.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/popup.css');
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/jquery.pagination.css');

        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jobList/jquery.joblist.js', "text/javascript", false, FALSE);
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.pagination.js', "text/javascript", false, FALSE);
        $doc->addStyleSheet(JUri::base() . 'plugins/system/nsoft/assets/js/css/magnific-popup.css');
        $doc->addScript(JUri::base() . 'plugins/system/nsoft/assets/js/jquery.magnific-popup.js', "text/javascript", false, FALSE);
    }

}
