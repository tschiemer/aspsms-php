<?php

namespace Aspsms;

abstract class AbstractSimpleClient
{
    
    /**
     * @var Request
     * @access protected
     */
    var $lastRequest = NULL;
    
    /**
     * @var Response
     * @access protected
     */
    var $lastResponse = NULL;
    
    /**
     * @var string
     * @access protected
     */
    var $userkey = '';
    
    /**
     * @var string
     * @access protected
     */
    var $password = '';
    
    /**
     * @var string
     * @access protected
     */
    var $originator = '';
    
    /**
     * @var string
     * @access protected
     */
    var $affiliateId = '';
    
    /**
     * @var array
     * @access protected
     */
    var $urls = array(
        'URLDeliveryNotification'           => '',
        'URLNonDeliveryNotification'        => '',
        'URLBufferedMessageNotification'    => ''
    );
    
    
    /**
     * @var array
     * @access protected
     */
    var $message = NULL;
    
    
    
    public function __construct($options = array())
    {   
        foreach($options as $key => $value)
        {
            switch(strtolower($key))
            {
                case 'userkey':
                    $this->userkey = strval($value);
                    break;
                
                case 'password':
                    $this->password = strval($value);
                    break;
                
                case 'originator':
                    $this->originator = strval($value);
                    break;
                
                case 'affiliateid':
                    $this->affiliateId = strval($value);
                    break;
                    
                case 'urls':
                    foreach($values as $key => $default)
                    {
                        $this->setCallbackURL($key,$default);
                    }
                    break;
            }
        }
    }
    
    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }
    
    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    
    /**
     * @return boolean|string FALSE iff no last Response exists
     */
    public function getLastStatusCode()
    {
        if ($this->lastResponse === NULL)
        {
            return FALSE;
        }
        return $this->lastResponse->getStatusCode();
    }
    
    /**
     * Sets default authentication details.
     * 
     * @param string $userkey
     * @param string $password
     * @return \Aspsms\AbstractSimpleClient
     */
    public function setAuth($userkey,$password)
    {
        $this->userkey  = strval($userkey);
        $this->password = strval($password);
        
        return $this;
    }
    
    /**
     * Gets default originator.
     * 
     * @return string
     */
    public function getOriginator()
    {
        return $this->originator;
    }
    
    /**
     * Sets default originator.
     * 
     * @param string $originator
     * @return \Aspsms\AbstractSimpleClient
     */
    public function setOriginator($originator)
    {
        $this->originator = strval($originator);
        
        return $this;
    }
    
    /**
     * Gets default affiliate id.
     * 
     * @return string
     */
    public function getAffiliateId()
    {
        return $this->affiliateId;
    }
    
    /**
     * Sets default affiliate id.
     * 
     * @param string $affiliateId
     * @return \Aspsms\AbstractSimpleClient
     */
    public function setAffiliateId($affiliateId = '')
    {
        $this->affiliateId = strval($affiliateId);
        
        return $this;
    }
    
    /**
     * Get current default notification URL.
     * 
     * @param NULL|string $type
     * @return string|array Returns string/url IFF <$type> given, array with all urls otherwise.
     */
    public function getCallbackURL($type = NULL)
    {
        if (isset($this->urls[$type]))
        {
            return $this->urls[$type];
        }
        return $this->urls;
    }
    
    /**
     * Sets default callback urls
     * 
     * @param string $type
     * @param string $url
     * @return \Aspsms\AbstractSimpleClient
     */
    public function setCallbackURL($type, $url)
    {
        // map the simpler labels to the correct indices
        switch($type)
        {
            case 'success': $type = 'URLDeliveryNotification';
                break;
            
            case 'error': $type = 'URLNonDeliveryNotification'; 
                break;
            
            case 'buffered': $type = 'URLBufferedMessageNotification'; 
                break;
        }
        
        if (isset($this->urls[$type]))
        {
            $this->urls[$type] = strval($url);
        }
        return $this;
    }
    
    /**
     * Request: what's the current balance?
     * 
     * @return float
     */
    abstract public function getCreditBalance();
    
    
    /**
     * Request: Is (numeric) originator valid?
     * 
     * @param NULL|string $originator
     * @return boolean
     */
    abstract public function checkOriginator($originator = NULL);
    
    /**
     * Request: request a code to unlock numeric originator. An SMS with the code is sent
     * to the given (or set) originator.
     * 
     * @param string $originator Must be numeric
     * @return type
     */
    abstract public function requestOrignatorUnlockCode($originator = NULL);
    
    /**
     * Request: Attempt to unlock (numeric) originator with code.
     * 
     * @see requestOriginatorUnlockCode()
     * @param type $code
     * @param type $originator
     */
    abstract public function unlockOriginator($code, $originator = NULL);
    
    
    /**
     * Request: get delivery status of 
     * @param string|array $trackingNumbers
     */
    abstract public function getDeliveryStatus($trackingNumbers, $index_by_trackingNr = TRUE);
    
    /**
     * Use flashing SMS?
     * 
     * @param boolean $on_off
     * @return \Aspsms\AbstractSimpleClient
     */
    public function flash($on_off = TRUE)
    {
        return $this->set('FlashingSMS',$on_off);
    }
    
    /**
     * Delay delivery of SMS by <$seconds>
     * 
     * @param int $seconds
     * @param int|\DateTimeZone $timezone IFF int: offset to GMT
     * @return \Aspsms\AbstractSimpleClient
     */
    public function deferTime($seconds,$timezone=0)
    {
        return $this->set(array(
           'DeferredDeliveryTime'   => time() + $seconds,
           'TimeZone'               => $timezone
        ));
    }
    
    /**
     * Set approximate delivery of SMS to <$date>
     * 
     * @param string|\DateTime $date
     * @param int|\DateTimeZone $timezone IFF int: offset to GMT
     * @return \Aspsms\AbstractSimpleClient
     */
    public function deferUntil($date,$timezone=0)
    {
        return $this->set(array(
           'DeferredDeliveryTime'   => $date,
           'TimeZone'               => $timezone
        ));
    }
    
    /**
     * 
     * @param type $urls
     * @param type $to
     * @return \Aspsms\AbstractSimpleClient
     */
    public function callbacks($urls = array(), $to = NULL)
    {
        if ($urls === FALSE or is_string($urls) and $to === NULL)
        {
            $url = ! $urls ? strval($urls) : '';
            
            return $this->set(array(
                'URLDeliveryNotification'           => $url,
                'URLNonDeliveryNotification'        => $url,
                'URLBufferedMessageNotification'    => $url
            ));
        }
        
        if (is_string($urls) and is_string($to))
        {
            $urls = array($urls => $to);
        }
        
        $set = array();
        foreach($urls as $v)
        {
            switch($v)
            {
                case 'success':
                case 'URLDeliveryNotification':
                    $set['URLDeliveryNotification'] = $v;
                    break;

                case 'error':
                case 'URLNonDeliveryNotification':
                    $set['URLNonDeliveryNotification'] = $v;
                    break;

                case 'buffered':
                case 'URLBufferedMessageNotification':
                    $set['URLBufferedMessageNotification'] = $v;
                    break;
            }
        }
        
        return $this->set($set);
    }
    
    public function sendText($recipients,$text)
    {
        return $this->send(array(
            'Recipients'        => $recipients,
            'MessageText'       => $text
        ));
    }
    
    public function sendWapPush($recipients,$url,$description='')
    {
        return $this->send(array(
            'Recipients'        => $recipients,
            'WapDescription'    => $description,
            'WapURL'            => $url
        ));
    }
    
    
    /**
     * Clear any message settings set through <set()>
     * 
     * @see set()
     * @return \Aspsms\AbstractSimpleClient
     */
    public function clear()
    {
        $this->message = NULL;
        
        return $this;
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
            
            if (in_array($k, array('MessageText')))
            {
                $after = $this->defaultType['text'];
            }
            elseif (in_array($k,array('WapDescription','WapURL')))
            {
                $after = $this->defaultType['wap'];
            }
            elseif (in_array($k,array('MessageData','TokenReference','TokenValidity','TokenMask','VerificationCode','TokenCaseSensitive')))
            {                
                $after = $this->defaultType['wap'];
            }
            // Oh oh, we've come to contradictary conclusions!
            if ($before !== NULL and $before != $after and ! $type_is_predefined)
            {
                throw new AspsmsException('Automatic detection of message type lead to confusion because of contradictionary data, try to predefine type.');
            }
            $this->message['msgType'] = $after;
            
        }
        
        return $this;
    }
    
    abstract public function send($options = array());
}