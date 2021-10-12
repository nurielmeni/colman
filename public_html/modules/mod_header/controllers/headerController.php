<?php

/**
 * Description of headerController
 *
 * @author Armen
 */
defined('_JEXEC') or die;

class headerController{

    public function index(){
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base().'modules/mod_header/assets/css/style.css');

		

    }
}
