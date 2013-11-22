<?php

namespace Aspsms;

//require_once dirname(__FILE__) . '/ServiceInterface.php';
require_once dirname(__FILE__) . '/Types.php';

class SoapClient extends AbstractClient
{
    /**
     * @var \SoapClient
     */
    var $soap;
    
    /**
     * 
     * @param type $wsdl
     * @param type $options
     * @throws \SoapFault Most likely
     */
    public function __construct($wsdl = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL', $options = array())
    {
        // Set default classmap iff not defined already
        if ( ! isset($options['classmap']))
        {
            $options['classmap'] = array(
                'CheckCreditsResponse'                  => '\Aspsms\Soap\v2\CheckCreditsResponse',
                'CheckOriginatorAuthorizationResponse'  => '\Aspsms\Soap\v2\CheckOriginatorAuthorizationResponse',
                'GetStatusCodeDescriptionResponse'      => '\Aspsms\Soap\v2\GetStatusCodeDescriptionResponse',
                'InquireDeliveryNotificationsResponse'  => '\Aspsms\Soap\v2\InquireDeliveryNotificationsResponse',
                'SendOriginatorUnlockCodeResponse'      => '\Aspsms\Soap\v2\SendOriginatorUnlockCodeResponse',
                'SendSimpleTextSMSResponse'             => '\Aspsms\Soap\v2\SendSimpleTextSMSResponse',
                'SendTextSMSResponse'                   => '\Aspsms\Soap\v2\SendTextSMSResponse',
                'SendTokenSMSResponse'                  => '\Aspsms\Soap\v2\SendTokenSMSResponse',
                'SendUnicodeSMSResponse'                => '\Aspsms\Soap\v2\SendUnicodeSMSResponse',
                'SimpleWAPPushResponse'                 => '\Aspsms\Soap\v2\SimpleWAPPushResponse',
                'UnlockOriginatorResponse'              => '\Aspsms\Soap\v2\UnlockOriginatorResponse',
                'VersionInfoResponse'                   => '\Aspsms\Soap\v2\VersionInfoResponse',
                'VerifyTokenResponse'                   => '\Aspsms\Soap\v2\VerifyTokenResponse'
            );
        }
        
        // Disable wsdl caching, if not set otherwise
        if ( ! isset($options['cache_wsdl']))
        {
            $options['cache_wsdl'] = WSDL_CACHE_NONE;
        }
        
        try {
            $this->soap = new \SoapClient($wsdl, $options);
        }
        catch (\SoapFault $e) {
            $this->soap = NULL;
            throw new AspsmsException('Could not retrieve WSDL or is invalid.');
        }
    }
    
    /**
     * 
     * @param string $name
     * @param \Aspsms\Soap\V2\Request $parameters
     * @return \Aspsms\Soap\v2\rn
     * @access protected
     */
    public function __callService($name, $param=NULL) {
        $rn = __NAMESPACE__ . '\\' . $name . 'Response'; 
        if ($this->soap === NULL)
        {
            return $r = new $rn(ServiceInterface::E_SOAP, 'No valid soap interface');
        }
        
        try {
            $r = $this->soap->$name($param);
        }
        catch (\SoapFault $e){
            var_dump($e);
            if (class_exists($rn))
            {
                $r = new $rn(ServiceInterface::E_SOAP, $e->getMessage());
            }
            else
            {
                die('asdfasdf');
                var_dump($this->soap->__getLastResponse()); exit;
            }
        }
            var_dump($this->soap->__getLastRequest());
            var_dump($this->soap->__getLastResponse());
            var_dump($r);
        return $r;
    }
    
    /*****************
     * Implementation of the service interface
     * 
     * Note: not really needed, could be done through __call() magic method
     * but like this we get the nice code completion.
     */
    
    /**
     * @param RequestAuth $parameters
     * @return CheckCreditsResponse
     */
    public function CheckCredits($parameters)
    {
        return $this->__callService('CheckCredits', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\CheckOriginatorAuthorization $parameters
     * @return \Aspsms\Soap\CheckOriginatorAuthorizationResponse
     */
    public function CheckOriginatorAuthorization($parameters)
    {
        return $this->__callService('CheckOriginatorAuthorization', $parameters);
    }
    
    /**
     * @param GetStatusCodeDescription $parameters
     * @return GetStatusCodeDescriptionResponse
     */
    public function GetStatusCodeDescription($parameters)
    {
        return $this->__callService('GetStatusCodeDescription', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\InquireDeliveryNotifications $parameters
     * @return \Aspsms\Soap\InquireDeliveryNotifications
     */
    public function InquireDeliveryNotifications($parameters) {
        return $this->__callService('InquireDeliveryNotifications',$parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SendOriginatorUnlockCode $parameters
     * @return \Aspsms\Soap\SendOriginatorUnlockCodeResponse
     */
    public function SendOriginatorUnlockCode($parameters) {
        return $this->__callService('SendOriginatorUnlockCode', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SendSimpleTextSMS $parameters
     * @return \Aspsms\Soap\SendSimpleTextSMSResponse 
     */
    public function SendSimpleTextSMS($parameters) {
        return $this->__callService('SendSimpleTextSMS', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SendTextSMS $parameters
     * @return \Aspsms\Soap\SendTextSMSResponse
     */
    public function SendTextSMS($parameters) {
        return $this->__callService('SendTextSMS',$parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SendTokenSMS $parameters
     * @return \Aspsms\Soap\SendTokenSMSResponse
     */
    public function SendTokenSMS($parameters) {
        return $this->__callService('SendTokenSMS', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SendUnicodeSMS $parameters
     * @return \Aspsms\Soap\SendUnicodeSMSResponse
     */
    public function SendUnicodeSMS($parameters) {
        return $this->__callService('SendUnicodeSMS',$parameters);
    }
    
    /**
     * @param \Aspsms\Soap\SimpleWAPPush $parameters
     * @return \Aspsms\Soap\SimpleWAPPushResponse
     */
    public function SimpleWAPPush($parameters) {
        return $this->__callService('SimpleWAPPush',$parameters);
    }
    
    /**
     * @param \Aspsms\Soap\UnlockOriginator $parameters
     * @return \Aspsms\Soap\UnlockOriginatorResponse
     */
    public function UnlockOriginator($parameters) {
        return $this->__callService('UnlockOriginator', $parameters);
    }
    
    /**
     * @param \Aspsms\Soap\VerifyToken $parameters
     * @return \Aspsms\Soap\VerifyTokenResponse
     */
    public function VerifyToken($parameters) {
        return $this->__callService('VerifyToken', $parameters);
    }
    
    /**
     * @return VersionInfoResponse
     */
    public function VersionInfo()
    {
        return $this->__callService('VersionInfo');
    }
}

$s = new SoapClient();
