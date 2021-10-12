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
//$app = JFactory::getApplication();
$dir = dirname(__FILE__);
require_once $dir . '/config.php';
require_once $dir . '/guid.php';
require_once $dir . '/nlssecurity.php';
require_once $dir . '/nlscards.php';

class PlgSystemNsoft extends JPlugin {

    private $nlsSecurityInstans;
    private $authInstans;

    public function runApi() {
        $params = $this->params;

        nsoftConfig::$fromMail = $params->get('from_mail');
        nsoftConfig::$toMail = $params->get('to_mail');
        nsoftConfig::$toMailCc = $params->get('to_mail_cc');
        nsoftConfig::$fromName = $params->get('from_name');
        nsoftConfig::$nsoftSiteId = $params->get('nsoft_site_id');
        nsoftConfig::$nsoftApplicationId = $params->get('nsoft_application_id');
        nsoftConfig::$categorySupplierId = $params->get('category_supplier_id');
        nsoftConfig::$searchSupplierId = $params->get('search_supplier_id');
        nsoftConfig::$employmentTypeSupplierId = $params->get('employment_type_supplier_id');
        nsoftConfig::$advancedSearchSupplierId = $params->get('advanced_search_supplier_id');
        nsoftConfig::$directoryServiceWsdlUrl = $params->get('directory_service_wsdl_url');
        nsoftConfig::$nlsCardsWsdlUrl = $params->get('nls_cards_wsdl_url');
        nsoftConfig::$nlsSecurityWsdlUrl = $params->get('nls_security_wsdl_url');
        nsoftConfig::$trace = $params->get('trace') == 1;
        nsoftConfig::$exceptions = $params->get('exceptions') == 1;
        nsoftConfig::$cache_wsdl = intval($params->get('cache_wsdl'));
        nsoftConfig::$nlsSecurityDomain = $params->get('nls_security_domain');
        nsoftConfig::$nlsSecurityUsername = $params->get('nls_security_username');
        nsoftConfig::$nlsSecurityPassword = $params->get('nls_security_password');
        nsoftConfig::$countPerPageCategory = $params->get('count_per_page_category');
        nsoftConfig::$countPerPageNSoft = $params->get('count_per_page_nsoft');
        nsoftConfig::$countPerPageSearch = $params->get('count_per_page_search');
        nsoftConfig::$onedriveClientId = $params->get('onedrive_client_id');
        nsoftConfig::$onedriveRedirectUri = $params->get('onedrive_redirect_uri');
        nsoftConfig::$dropboxAppKey = $params->get('dropbox_app_key');
        nsoftConfig::$consumer_key = $params->get('consumer_key');
        nsoftConfig::$consumer_secret = $params->get('consumer_secret');
        nsoftConfig::$saml_redirect_url = $params->get('saml_redirect_url');
        nsoftConfig::$searchServiceWsdlUrl = $params->get('searchServiceWsdlUrl');
        nsoftConfig::$user_domain = $params->get('user_domain');
        nsoftConfig::$youtube_url = $params->get('youtube_url');
        nsoftConfig::$hunter_user_for_non_loged_in_search = $params->get('hunter_user_for_non_loged_in_search');
        nsoftConfig::$language_code = $params->get('language_code');

        $session = JFactory::getSession();
        $auth_token = $session->get("api_auth_token");
        $api_auth_token_date = $session->get("api_auth_token_date");

        if (isset($auth_token) && !empty($api_auth_token_date)&&!$this->is_expired($api_auth_token_date)) {
            //  die('1');
            return $auth_token;
        } else {
            //   die('2');
            if (empty($this->nlsSecurityInstans)) {
                $this->nlsSecurityInstans = new nlsSecurity();
            }

            if (empty($this->authInstans)) {
                $this->authInstans = $this->nlsSecurityInstans->Authenticate();
            }

            $userToken = $this->authInstans->usernameToken;
            $indexOfSeparator = strrpos($userToken, "^^^^");

            $date = substr($userToken, $indexOfSeparator + 4);

            $session->set("api_auth_token_date", $date);
            $session->set("api_auth_token", $this->authInstans);

            return $this->authInstans;
        }
    }

    private function is_expired($date_input) {

        $date = DateTime::createFromFormat("d/m/Y H:i:s", $date_input);

        return ($date->getTimestamp() - time()) < 0;
    }

}
