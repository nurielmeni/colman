<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModNSoftHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */ 
    public static function getAction($controller,$action,$next=NULL){
        if(!$controller)
            $controller='nsoft';
        if(!$action)
            $action = 'index';
        $controller= $controller.'Controller';
        require_once dirname(__FILE__).'/controllers/'.$controller.'.php';
        if (empty($next) || !isset($next)){
            $obj = new $controller();
            return $obj->$action();
        }
        
        $next($controller,$action);
    }
    public static function getHello($params)
    {
        //return 'Hello, World!';
    }
}