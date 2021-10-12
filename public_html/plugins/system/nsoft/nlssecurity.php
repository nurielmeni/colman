<?php

defined('_JEXEC') or die;

class nlsSecurity {

    private $url;
    private $client;
    private $applicationId;
    private $domain;
    private $username;
    private $password;
    private $loginname;

    public function __construct() {
        $this->applicationId = nsoftConfig::$nsoftApplicationId;           #Prod: "D091F3DC-6B8B-47A7-A11A-F7DB89FEA3B3"
        $this->url = nsoftConfig::$nlsSecurityWsdlUrl; #	Prod: "https://xdirectoryservice.hunterhrms.com/securityservice.svc"
        $this->domain = nsoftConfig::$nlsSecurityDomain; #																Prod: "demopro"
        $this->username = nsoftConfig::$nlsSecurityUsername; #																Prod: "jobssite"
        $this->password = nsoftConfig::$nlsSecurityPassword; #															Prod: "pass2015"
        $this->loginname = "$this->domain\\$this->username";
        $this->client = new SoapClient($this->url, array(
            'trace' => nsoftConfig::$trace,
            'exceptions' => nsoftConfig::$exceptions,
            'cache_wsdl' => nsoftConfig::$cache_wsdl
        ));

        $soap_headers = array(new SoapHeader('_', 'NiloosoftCred0', $this->applicationId),
            new SoapHeader('_', 'NiloosoftCred1', $this->loginname),
            new SoapHeader('_', 'NiloosoftCred2', $this->password));
        $this->client->__setSoapHeaders($soap_headers);
    }

    public function Authenticate() {
        $transactionCode = guid::newGuid();
        try {
            $param = array('userName' => $this->loginname,
                'password' => $this->password,
                'transactionCode' => $transactionCode);

            $res = $this->client->Authenticate2($param);

            $auth = new stdClass();
            $auth->usernameToken = $res->UsernameToken;
            $auth->passwordToken = $res->PasswordToken;
            return $auth;
        } catch (Exception $ex) {
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

    public function AuthenticateByConsumerKeyAndSecretKey($userName) {
//   var_dump($userName);die;
        $transactionCode = guid::newGuid();
        try {
            $param = array(
                'userName' => $userName,
                "ConsumerKey" => nsoftConfig::$consumer_key, ////nsoftConfig::$nsoftConsumerSecret;
                "SecretKey" => nsoftConfig::$consumer_secret, //nsoftConfig::$nsoftConsumerKey;,
                "ipAddress" => "127.0.0.1", // $_SERVER['REMOTE_ADDR'],
                'transactionCode' => $transactionCode);

            $res = $this->client->AuthenticateByConsumerKeyAndSecretKey($param);
//           echo "Request " . $this->client->__getLastRequest();
//            echo "Response " . $this->client->__getLastResponse();
//            var_dump($res);
//            die;
            return $res;
        } catch (SoapFault $ex) {

            echo "Request " . $this->client->__getLastRequest();
            echo "Response " . $this->client->__getLastResponse();
            die;
        } catch (Exception $ex) {
            $ex->transactionCode = $transactionCode;
            throw $ex;
        }
    }

}
