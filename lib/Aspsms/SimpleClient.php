<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/AbstractSimpleClient.php';


/**
 * Simple interface combining all possible drivers.
 * 
 * Has an internal mapping of request names to drivers, thus drivers are loaded
 * lazily as required.
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
     * Driver options to use when instantiating drivers
     * .
     * @var array
     * @see HttpClient, SoapClient, XmlClient
     */
    var $driverOptions = array(
        'soap'  => array(),
        'http'  => array(),
        'xml'   => array()
    );
    
    /**
     * Mapping of request names to driver names
     * 
     * @var string[]
     */
    var $requestMap = array(
        
        'getVersion'                => 'soap',  // Soap|Http
        'getCredits'                => 'xml',   // Xml|Soap|Http
        'getStatusCodeDescription'  => 'soap',  // Soap|Http
        
        'checkOriginator'           => 'xml',   // Xml|Soap|Http
        'sendOriginatorCode'        => 'xml',   // Xml|Soap|Http
        'unlockOriginator'          => 'soap',  // Xml|Soap|Http
        
        'sendText'                  => 'http',   // Xml|Soap|Http
        'sendWapPush'               => 'soap',  // Xml|Soap|Http
        'sendToken'                 => 'soap',  // Soap|Http
        'verifyToken'               => 'soap',   // Soap|Http
        'sendPicture'               => 'xml',   // Xml
        'sendLogo'                  => 'xml',   // Xml
        'sendGroupLogo'             => 'xml',   // Xml
        'sendRingtone'              => 'xml',   // Xml
        'sendVCard'                 => 'xml',   // Xml
        'sendBinaryData'            => 'xml',   // Xml
        'getDeliveryStatus'         => 'soap',  // Xml|Soap|Http
        
    );
    
    
    /**
     * Constructor
     * 
     * Sets up simple client and prepare for driver instantiation.
     * 
     * Driver options are passed as fields with names 'soapclient','httpclient','xmlclient' respectively.
     * 
     * The default request mapping can be changed when passing
     * 
     * @param array $options
     * 
     * @see AbstractSimpleClient::__construct()
     * @see SoapClient::__construct()
     * @see HttpClient::__construct()
     * @see XmlClient::__construct()
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        
        // Which are the valid drivers?
        $validDrivers = array_keys($this->driverOptions);
        
        // Set any driver options
        foreach($validDrivers as $d)
        {
            if (isset($options[$d.'client']))
            {
                foreach($options[$d.'client'] as $k => $v)
                {
                    $this->driverOptions[$d][$k] = $v;
                }
            }
        }
        
        // change the default request to driver mapping if so wanted
        if (isset($options['requestMap']))
        {
            foreach($options['requestMap'] as $k => $v)
            {
                if ( ! array_key_exists($k, $this->requestMap))
                {
                    throw new AspsmsException('Request not recognized in setup: '. $k);
                }
                
                if ( ! in_array($v,$validDrivers))
                {
                    throw new AspsmsException('Invalid driver passed: '.$v); 
                }
                
                $this->requestMap[$k] = $v;
            }
        }
        
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
        
        if ( ! isset($this->requestMap[$requestName]))
        {
            throw new AspsmsException('Request type not recognized: '.$requestName);
        }
        
        // Get driver name
        $obj_name = strtolower($this->requestMap[$requestName]);
        
        // If driver not loaded, well, load.
        if ( ! isset($this->drivers->obj_name))
        {
            $this->drivers->$obj_name = $this->loadDriver($obj_name, FALSE);
        }
        
        return $this->drivers->$obj_name;
    }
    
    /**
     * Instantiates driver with internal options.
     * 
     * @param string $obj_name Driver name
     * @return \Aspsms\class Instance of driver
     * @throws AspsmsException
     */
    public function loadDriver($obj_name)
    {
//        if ( ! isset($this->drivers->$obj_name))
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

            return new $class($options);
        }
    }
    
    
    /**
     * Request: Get Soap or Http Service version (depends on assigned driver to use)
     * 
     * @return array Associative array with fields 'all','version','build' and corresponding meaning.
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
     * Request: Send a token a predefined token to recipients.
     * 
     * Official doc:
     * 
     * If MessageData is set, the placeholder <VERIFICATIONCODE> will be
     * substituted with the verification code. If MessageData is not defined,
     * or if MessageData does not contain the placeholder <VERIFICATIONCODE>,
     * only the verification code is sent.
     * 
     * @param string $phoneNr           Recipient phone number
     * @param string $reference         Your reference number
     * @param string $verificationCode  Required verification code to send
     * @param string $message           Message to send code with.
     * @param int $minutes              Validity of token in minutes (default 5)
     * @param boolean $case_sensitive   Is given code case sensitive?
     * @return boolean                  Request success?
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
     * Request: Send a token as generated by ASPSMS.COM, optionally give token mask.
     * 
     * Official doc:
     * 
     * If MessageData is set, the placeholder <VERIFICATIONCODE> will be
     * substituted with the verification code. If MessageData is not defined,
     * or if MessageData does not contain the placeholder <VERIFICATIONCODE>,
     * only the verification code is sent.
     * 
     * Official doc:
     * 
     * Used to have the ASPSMS generate a verification code by mask. The mask can contain the following special characters:
     *
     *  # : a digit
     *  A : an alphabetic character
     *  N : an alphanumeric character
     *
     *  All other characters are taken literally. If not specified, the Mask is "NNNN" by default.
     *
     * 
     * @param string $phoneNr           Recipient phone number
     * @param string $reference         Your reference number
     * @param string $message           Message to send code with.
     * @param string $mask              Token code mask to use (# -> number, A -> Alphabetical)
     * @param int $minutes              Validity of token in minutes (default 5)
     * @param boolean $case_sensitive   Is given code case sensitive?
     * @return boolean                  Request success?
     */
    public function sendGeneratedToken($phoneNr,$reference,$message='',$mask='######',$minutes=5, $case_sensitive=0)
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
     * Request: attempt to validate token.
     * 
     * NOTE: If a token have been successfully validated, any future attempts (no matter the 
     * verification code use) succeed.
     * 
     * @param string $phoneNr           Recipient phone number
     * @param string $reference         Your reference number
     * @param string $verificationCode  Required verification code to validate
     * @return boolean                  Is given verification code for use valid?
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
    
    
    /**
     * Send VCARD (name + telephone nr) to designated recipients.
     * 
     * String Format:
     *  ("<RECIPIENT_NR>" + {":<TRACKING_NR>"} ";" .. )+ 
     * Eg:
     *   00417777777
     *   00417777777;00417777777;004177777777
     *   00417777777:84612004;00417777777:74183874783
     * 
     * Array Format:
     *  <TRACKING_NR> => <RECIPIENT_NR>
     * 
     * @param array,string $recipients
     * @param string $name Name of VCARD
     * @param string $phoneNr Phone number of VCARD
     * @return boolean Request submitted successfully? (not delivery)
     * @see \Aspsms\AbstractSimpleClient::getDeliveryStatus()
     */
    public function sendVCard($recipients, $name, $phoneNr)
    {
        return $this->send(array(
            'RequestName'       => 'sendVCard',
            'Recipients'        => $recipients,
            'VCard'             => array(
                'name' => $name,
                'phoneNr' => $phoneNr
            )
        ));
    }
    
    /**
     * 
     * @todo TESTING
     * 
     * @param type $recipients
     * @param type $url
     * @return type
     */
    public function sendRingtone($recipients,$url)
    {
        return $this->send(array(
            'RequestName'       => 'sendVCard',
            'Recipients'        => $recipients,
            'URLBinaryFile'     => $url
        ));
    }
}