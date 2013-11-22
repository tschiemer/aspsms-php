<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../AbstractSimpleClient.php';
require_once dirname(__FILE__) . '/SoapClient.php';

class SoapSimpleClient extends AbstractSimpleClient
{
    /**
     * @var SoapClient
     */
    var $driver;
    
    public function __construct($options = array()) {
        parent::__construct($options);
        
        $this->driver = new SoapClient();
    }
    
    public function driver(&$request) {
        return $this->driver;
    }
    
    
    /**
     * 
     * For more details you can also access the last response and versin/build methods.
     * 
     * @see VersionInfoResponse lastResponse()
     * @return string
     */
    public function getVersion()
    {
        return $this->send(array(
            'RequestName' => 'getVersion'
        ));
    }
    
    
    /**
     * Request: Get description to given status code.
     * 
     * @param string|int $statusCode
     * @return string
     */
    public function getStatusDescription($statusCode)
    {   
        return $this->send(array(
            'RequestName' => 'getStatusCodeDescription',
            'StatusCode' => $statusCode
        ));
    }
    
    
    /**
     * 
     * @param string $phoneNr
     * @param string $reference
     * @param string $verificationCode
     * @param string $message
     * @param int $minutes
     * @param boolean $case_sensitive
     * @return boolean
     */
    public function sendMyToken($phoneNr,$reference,$verificationCode,$message='',$minutes=5, $case_sensitive=0)
    {
        return $this->send(array(
            'RequestName'       => 'sendToken',
            'Recipients'        => $phoneNr,
            'TokenReference'    => $reference,
            'VerificationCode'  => $verificationCode,
            'MessageData'       => $message,
            'TokenValidity'     => $minutes,
            'TokenCaseSensitive'=> $case_sensitive
        ));
    }
    
    /**
     * 
     * @param string $phoneNr
     * @param string $reference
     * @param string $mask
     * @param string $message
     * @param int $minutes
     * @param boolean $case_sensitive
     * @return boolean
     */
    public function sendGeneratedToken($phoneNr,$reference,$mask='',$message='',$minutes=5, $case_sensitive=0)
    {
        return $this->send(array(
            'RequestName'       => 'sendToken',
            'Recipients'        => $phoneNr,
            'TokenReference'    => $reference,
            'TokenMask'         => $mask,
            'MessageData'       => $message,
            'TokenValidity'     => $minutes,
            'TokenCaseSensitive'=> $case_sensitive
        ));
    }
    
    /**
     * 
     * @param string $phoneNr
     * @param string $reference
     * @param string $verificationCode
     * @return boolean
     */
    public function validateToken($phoneNr,$reference,$verificationCode)
    {
        return $this->send(array(
            'RequestName'       => 'verifyToken',
            'PhoneNumber'       => $phoneNr,
            'TokenReference'    => $reference,
            'VerificationCode'  => $verificationCode
        ));
    }
}