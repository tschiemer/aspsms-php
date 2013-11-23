<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/AbstractSimpleClient.php';


/**
 * Simple interface combining all possible drivers
 * 
 * @version 1
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class SimpleClient extends AbstractSimpleClient
{
    /**
     * Loaded drivers
     * 
     * @var AbstractClient[]
     */
    var $drivers = NULL;
    
    /**
     * Mapping of request names to driver names
     * 
     * @var string[]
     */
    var $request2driver = array(
        'sendText'                  => 'xml', // Xml|Soap|Http
        
        'getCredits'                => 'xml', // Xml|Soap|Http
        'checkOriginator'           => 'xml', // Xml|Soap|Http
        'sendOriginatorCode'        => 'xml', // Xml|Soap|Http
        'unlockOriginator'          => 'soap', // Xml|Soap|Http
        'getDeliveryStatus'         => 'soap', // Xml|Soap|Http
        
        'getVersion'                => 'soap', // Soap|Http
        'getStatusCodeDescription'  => 'soap', // Soap|Http
        'sendToken'                 => 'soap', // Soap|Http
        'verifyToken'               => 'soap'  // Soap|Http
    );
    
    public function __construct($options = array()) {
        parent::__construct($options);
        
        $this->drivers = new \stdClass();
    }
    
    /**
     * Loads and returns the correct driver for the assigned request type.
     * 
     * @param Request $requestType
     * @return AbstractClient
     * @throws AspsmsException
     */
    public function driver(&$request)
    {
        $requestName = $request->getRequestName();
        
        if ( ! isset($this->request2driver[$requestName]))
        {
            throw new AspsmsException('Request type not recognized: '.$requestName);
        }
        
        // Get driver name
        $obj_name = strtolower($this->request2driver[$requestName]);
        
        // If driver not loaded, well, load.
        $this->loadDriver($obj_name, FALSE);
        
        return $this->drivers->$obj_name;
    }
    
    public function loadDriver($obj_name, $return = FALSE)
    {
        if ( ! isset($this->drivers->$obj_name) or $return)
        {
            // Look for class XyzClient in file Xyz/XyzClient.php
            $class =ucfirst($obj_name) . 'Client';
            $path = dirname(__FILE__) . '/' . ucfirst($obj_name) . '/'.$class.'.php';

            if ( ! file_exists($path))
            {
                throw new AspsmsException('Could not load driver file '.$path.' for driver '.$d);
            }

            // Load file
            require_once $path;

            // Are there any options
            if (isset($this->options[$obj_name]))
            {
                $options = $this->options[$obj_name];
            }
            else
            {
                $options = array();
            }
            
            $class =  __NAMESPACE__ . '\\' . $class;

            if ($return)
            {
                return new $class($options);
            }
            else
            {
                $this->drivers->$obj_name = new $class($options);
            }
        }
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