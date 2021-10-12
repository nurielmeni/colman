<?php

/**
 * Description of footerController
 *
 * @author Armen
 */
defined('_JEXEC') or die;

class footerController{

    public function index(){
    	$doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base().'modules/mod_footer/assets/css/style.css');


		

    }
}
