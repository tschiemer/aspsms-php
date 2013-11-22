<?php

namespace Aspsms\WebService;

require_once __DIR__ . '/Client.php';
require_once __DIR__ . '../../AbstractSimpleClient.php';

class SimpleClient extends \Aspsms\AbstractSimpleClient
{
    /******
     * Internals
     */
    
    /**
     * @var Client
     * @access protected
     */
    var $driver = NULL;
    
    /**
     * @var array
     * @access protected
     */
    var $defaultType = array(
        'text'  => 'SendUnicodeSMS',
        'wap'   => 'SimpleWAPPush',
        'token' => 'SendTokenSMS'
    );
    
    var $field2Type = array(
        'MessageText'       => 'text',
        'WapDescription'    => 'wap',
        'WapURL'            => 'wap',
        'MessageData'       => 'token',
        'TokenReference'    => 'token',
        'TokenValidity'     => 'token',
        'TokenMask'         => 'token',
        'VerificationCode'  => 'token',
        'TokenCaseSensitive'=> 'token',
    );
    
    public function __construct($options = array())
    {
        parent::__construct($options);

        // set default driver type
        $driver = 'soap';
        
        foreach($options as $key => $value)
        {
            switch(strtolower($key))
            {    
                case 'defaulttype':
                    foreach($value as $type => $default)
                    {
                        if (isset($this->defaultType[$type]))
                        {
                            $this->defaultType[$type] = $default;
                        }
                    }
                    break;
                    
                case 'driver':
                    $driver = strtolower($value);
                    break;
            }
        }
        
        // Initialize driver
        switch($driver)
        {
            case 'soap':
                if (isset($options['wsdl']))
                {
                    $wsdl = $options['wsdl'];
                }
                else
                {
                    $wsdl = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL';
                }
                if (isset($options['soapOptions']))
                {
                    $soap_options = $options['soapOptions'];
                }
                else
                {
                    $soap_options = array();
                }
                $this->driver = new Client($wsdl, $soap_options);
                break;

            case 'http':
            
            default:
                throw new \Aspsms\Soap\v2\AspsmsException('No valid driver type: '.$driver);
        }
    }
    
    /**
     * Which default message type is to be used?
     * 
     * @see $defaultType
     * @param NULL|string $type
     * @return string|array Return all types if no <$type> given.
     */
    public function getDefaultType($type = NULL)
    {
        if (isset($this->defaultType[$type]))
        {
            return $this->defaultType[$type];
        }
        return $this->defaultType;
    }
    
    /**
     * Sets default message types
     * 
     * @param NULL|string $defaultType
     * @return \Aspsms\Soap\v2\SimpleClient
     */
    public function setDefaultType($type, $default)
    {
        if (isset($this->defaultType[$type]))
        {
            $this->defaultType[$type] = $default;
        }
        
        return $this;
    }
    
    /**
     * Request: Get description to given status code.
     * 
     * @param string|int $statusCode
     * @return string
     */
    public function getStatusDescription($statusCode)
    {   
        $this->lastRequest = new GetStatusCodeDescription($statusCode);
        
        $this->lastResponse = $this->driver->GetStatusCodeDescription(
            $this->lastRequest
        );
        
        return $this->lastResponse->result();
    }
    
    /**
     * Request: get version string of web service.
     * 
     * For more details you can also access the last response and versin/build methods.
     * 
     * WARNING: sets lastRequest to NULL
     * 
     * @see VersionInfoResponse lastResponse()
     * @return string
     */
    public function getVersion()
    {
        $this->lastRequest = NULL;
        
        $this->lastResponse = $this->driver->VersionInfo();
        
        return $this->lastResponse->result();
    }
    
    
    /**
     * Request: what's the current balance?
     * 
     * @return float
     */
    public function getCreditBalance()
    {
        $request = new RequestAuth($this->userkey, $this->password);
        $this->lastResponse = $this->driver->CheckCredits($request);
        
        if ($this->lastResponse->success())
        {
            return floatval($this->lastResponse->result());
        }
        else
        {
            return FALSE;
        }
    }
    
    
    /**
     * Request: Is (numeric) originator valid?
     * 
     * @param NULL|string $originator
     * @return boolean
     */
    public function checkOriginator($originator = NULL)
    {
        if ($originator === NULL)
        {
            $originator = $this->originator;
        }
        
        $request = new CheckOriginatorAuthorization($this->userkey,$this->password,$originator);
        $this->lastResponse = $this->driver->CheckOriginatorAuthorization($request);
        
        return $this->lastResponse->success();
    }
    
    /**
     * Request: request a code to unlock numeric originator. An SMS with the code is sent
     * to the given (or set) originator.
     * 
     * @param string $originator Must be numeric
     * @return type
     */
    public function requestOrignatorUnlockCode($originator = NULL)
    {
        if ($originator === NULL)
        {
            $originator = $this->originator;
        }
        
        $this->lastRequest = new SendOriginatorUnlockCode();
           
        $this->lastResponse = $this->driver->SendOriginatorUnlockCode($this->lastRequest);
        
        return $this->lastResponse;
    }
    
    /**
     * Request: Attempt to unlock (numeric) originator with code.
     * 
     * @see requestOriginatorUnlockCode()
     * @param type $code
     * @param type $originator
     */
    public function unlockOriginator($code, $originator = NULL)
    {
        if ($originator === NULL)
        {
            $originator = $this->originator;
        }   
        
        return $this->lastResponse;
    }
    
    
    /**
     * Request: get delivery status of 
     * @param string|array $trackingNumbers
     */
    public function getDeliveryStatus($trackingNumbers, $index_by_trackingNr = TRUE)
    {
        $this->lastRequest = new InquireDeliveryNotifications($this->userkey,$this->password,$trackingNumbers);
        
        $this->lastResponse = $this->driver->InquireDeliveryNotifications($this->lastRequest);
        
        if ($this->lastResponse->success())
        {
            $list = array();
            
            if (strlen($this->lastResponse->result()) > 0)
            {

                $keys = array(
                    'nr','status','submissionDate','deliveryDate','reason','other','more'
                );

                $all_list = explode("\n",$this->lastResponse->result());

                $list = array();
                foreach($all_list as $one)
                {
                    $tmp = array_combine($keys, explode(';',$one));

                    if ($index_by_trackingNr)
                    {
                        /**
                         *  Overwrite any previous result with same tracking number
                         * (here we assume that it's highly unlikely to use the same twice
                         * within a realistic time period)
                         */
                        $list[$tmp['nr']] = $tmp; 
                    }
                    else
                    {
                        $list[] = $tmp;
                    }
                }
            }
        }
        else
        {
            $list = $this->lastResponse->result();
        }
        
        return $list;
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
        $this->lastRequest = new VerifyToken(
                $this->userkey,
                $this->password,
                $phoneNr, $reference, $verificationCode
        );
        
        $this->lastResponse = $this->driver->VerifyToken($this->lastRequest);
        
        return $this->lastResponse->success();
    }
    
    public function invalidateToken($phoneNr,$reference,$verificationCode)
    {
        return $this->send(array(
            'Recipients'        => $phoneNr,
            'TokenReference'    => $reference,
            'VerificationCode'  => $verificationCode,
            'TokenValidity'     => -1
        ));
    }
    
    
    /**
     * Sets any SMS message options.
     * 
     * @param string|array $key_or_array
     * @param NULL|mixed $value
     * @return \Aspsms\Soap\v2\SimpleClient
     * @throws AspsmsException
     */
    public function set($key_or_array = NULL, $value = NULL)
    {
        // Initialize message data if not done yet.
        if ($this->message === NULL)
        {
            $this->message = array(
                'msgType' => NULL
            );
        }
        
        // If is string, bring into array form
        if (is_string($key_or_array))
        {
            $key_or_array = array($key_or_array => $value);
        }
        
        
        $type_is_predefined = isset($key_or_array['msgType']);
        if ($type_is_predefined)
        {
            if ( ! in_array($key_or_array['msgType'],$this->validTypes))
            {
                throw new AspsmsException('Invalid message type: '.$v);
            }
            
            $this->message['msgType'] = $key_or_array['msgType'];
            
            unset($key_or_array['msgType']);
        }
        
        foreach($key_or_array as $k => $v)
        {
            $this->message[$k] = $v;
            
            // Try to guess type by set options
            $before = $after = $this->message['msgType'];
            
            if (isset($this->field2Type[$k]))
            {
                $after = $this->field2Type[$k];
            }
//            if (in_array($k, array('MessageText')))
//            {
//                $after = $this->defaultType['text'];
//            }
//            elseif (in_array($k,array('WapDescription','WapURL')))
//            {
//                $after = $this->defaultType['wap'];
//            }
//            elseif (in_array($k,array('MessageData','TokenReference','TokenValidity','TokenMask','VerificationCode','TokenCaseSensitive')))
//            {                
//                $after = $this->defaultType['wap'];
//            }
            // Oh oh, we've come to contradictary conclusions!
            if ($before !== NULL and $before != $after and ! $type_is_predefined)
            {
                throw new AspsmsException('Automatic detection of message type lead to confusion because of contradictionary data, try to predefine type.');
            }
            $this->message['msgType'] = $after;
            
        }
        
        return $this;
    }
    
    /**
     * Sends SMS, allows shortcut access to <set()>
     * 
     * @see set()
     * @param array $options
     * @return boolean
     * @throws \Aspsms\Soap\v2\AspsmsException
     */
    public function send($options = array())
    {
        // guarantee correct message settings
        $this->set($options);
        
        // basic validation
        if ( $this->message['msgType'] === NULL)
        {
            throw new AspsmsException('Message type unknown, you do not seem to have provided enough options to submit a message.');
        }
        
        // Which is the actual message type?
        $msgType = $this->message['msgType'];
        
        // Also get the according class
        $msgClass = __NAMESPACE__ . '\\' . $msgType;
        
        // Initialize data packet with default data ..
        // ..that can yet be overriden! (see below)
        $this->lastRequest = new $msgClass($this->userkey,$this->password,$this->originator);
        
        // default affiliate id
        $this->lastRequest->setAffiliateId($this->affiliateId);
        
        // default notification urls
        if ($this->lastRequest instanceof RequestMessage)
        {
            $this->lastRequest->setURLDeliveryNotification($this->url['URLDeliveryNotification']);
            $this->lastRequest->setURLNonDeliveryNotification($this->url['URLNonDeliveryNotification']);
            $this->lastRequest->setURLBufferedMessageNotification($this->url['URLBufferedMessageNotification']);
        }
        
        
        // Set (and override_ any options for the message
        foreach($this->message as $k => $v)
        {
            if (method_exists($this->lastRequest, 'set'.$k))
            {
                $this->lastRequest->{'set'.$k}($v);
            }
        }
        
        // This is the actual request happening right here.
        $this->lastResponse = $this->driver->$msgType($this->lastRequest);
        
        // Clear any message settings
        $this->message = NULL;
        
        return $this->lastResponse->success();
    }
    
    
}