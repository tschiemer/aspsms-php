<?php

namespace tschiemer\Aspsms;

/**
 * Simple interface for aspsms maintaining common data and states
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
abstract class AbstractSimpleClient
{
    /**
     * @var Request
     * @access protected
     */
    var $currentRequest = NULL;
    
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
     * Contructor
     * 
     * Possible settings:
     * 
     *      'userkey'   => usekey (required)
     *      'password'  => password (required)
     *      'originator'=> originator (required)
     *      'affiliateid'=> affiliate id (optional)
     *      'urls'      => callback urls, assoc array of urls (a.s. setCallbackURL())
     * 
     * @see AbstractSimpleClient::setCallbackURL()
     * 
     * @param array $options Associative array of generic settings
     */
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
    
    public function getLastStatusCode()
    {
        if ( empty($this->lastResponse))
        {
            return NULL;
        }
        return $this->lastResponse->statusCode();
    }
    
    /***********************************************
     * Set default/common settings
     */
    
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
     *  1. simple:
     *      SMS.URLNonDeliveryNotification = "http://www.mysite.com/sms/notdelivered.asp?ID="
     *      When the TransactionReferenceNumber is e.g. 3152, the URL will be loaded like this: 
     *      http://www.mysite.com/sms/notdelivered.asp?ID=3152
     * 
     *  2. detailed:
     *      http://www.yourhost.com/Delivered.asp?SCTS=<SCTS>&DSCTS=<DSCTS>&RSN=<RSN>&DST=<DST>&TRN=<TRN>
     * 
     *      <RCPNT> (Recipient, Mobilenumber)
     *      <SCTS> (Servicecenter Timestamp, Submissiondate)
     *      <DSCTS> (Delivery Servicecenter Timestamp, Notificationdate)
     *      <RSN> (Reasoncode)
     *      <DST> (Deliverystatus)
     *      <TRN> (Transactionreferencenummer)
     * 
     * @param string $type 'success'/'URLDeliveryNotification', 'error'/'URLNonDeliveryNotification', 'buffered'/'URLBufferedMessageNotification'
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
    
    
    /********************************************************
     * Requests
     */
    
    /**
     * Request: what's the current balance?
     * 
     * @return float
     */
    public function getCreditBalance()
    {
        return $this->send(array(
            'RequestName' => 'getCredits'
        ));
    }
    
    
    /**
     * Request: Is (numeric) originator valid, can it be used respectively?
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
        
        return $this->send(array(
            'RequestName' => 'checkOriginator',
            'Originator'  => $originator
        ));
    }
    
    /**
     * Request: request a code to unlock numeric originator. An SMS with the code is sent
     * to the given (or set) originator.
     * 
     * @param string $originator Must be numeric
     * @return type
     */
    public function requestOriginatorUnlockCode($originator = NULL)
    {
        if ($originator === NULL)
        {
            $originator = $this->originator;
        }
        
        return $this->send(array(
            'RequestName' => 'sendOriginatorCode',
            'Originator'  => $originator
        ));
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
        
        return $this->send(array(
            'RequestName' => 'unlockOriginator',
            'Originator'  => $originator
        ));
    }
    
    
    /**
     * Request: get delivery status of  tracking
     * 
     * Returns array of delivery statuses.
     * 
     * If $index_by_nr == TRUE formatted as (only last the last result foreach ref-nr):
     *  array(
     *    'REF-NR-1' => array($keys[0] => 'REF-NR-1', $keys[1] => '..', .. , $keys[6] => '..'),
     *    'REF-NR-2' => array($keys[0] => 'REF-NR-2', $keys[1] => '..', .. , $keys[6] => '..'),
     *    'REF-NR-3' => array($keys[0] => 'REF-NR-3', $keys[1] => '..', .. , $keys[6] => '..')
     *      ..
     *  )
     * 
     * Else (with possible ref-nr duplicates):
     * 
     *  array(
     *    array($keys[0] => 'REF-NR-1', $keys[1] => '..', .. , $keys[6] => '..'),
     *    array($keys[0] => 'REF-NR-1', $keys[1] => '..', .. , $keys[6] => '..'),
     *    array($keys[0] => 'REF-NR-2', $keys[1] => '..', .. , $keys[6] => '..')
     *      ..
     *  )
     * 
     * 
     * @param string|array $trackingNumbers
     * @param boolean $index_by_nr Index result set by reference number (TRUE)? or just return complete list of results (FALSE)?
     * @param array $keys   Delivery Status field names to use
     */
    public function getDeliveryStatus($trackingNumbers, $index_by_nr=TRUE, $keys=array())
    {
        if (is_array($keys) and count($keys) != 7)
        {
            $keys = array(
                'nr','status','submissionDate','deliveryDate','reason','other','more'
            );
        }
        return $this->send(array(
            'RequestName' => 'getDeliveryStatus',
            'TransactionReferenceNumbers' => $trackingNumbers,
            'DeliveryStatusIndexing' => $index_by_nr,
            'DeliveryStatusFields' => $keys
        ));
    }
    
    /**
     * Request: send Text sms
     * 
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
     * @param string $text
     * @return boolean  Request success? (not delivery success)
     * @see \Aspsms\AbstractSimpleClient::getDeliveryStatus()
     */
    public function sendText($recipients,$text)
    {
        return $this->send(array(
            'RequestName'       => 'sendText',
            'Recipients'        => $recipients,
            'MessageText'       => $text
        ));
    }
    
    /**
     * Request: send WAP push
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
     * @param array, string $recipients
     * @param string $url
     * @param string $description
     * @return string request success? (not delivery success))
     * @see \Aspsms\AbstractSimpleClient::getDeliveryStatus()
     */
    public function sendWapPush($recipients,$url,$description='')
    {
        return $this->send(array(
            'RequestName'       => 'sendWapPush',
            'Recipients'        => $recipients,
            'WapDescription'    => $description,
            'WapURL'            => $url
        ));
    }
    
    /*******************************************
     * Request option helpers
     */
    
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
     * @param int|\DateTimeZone $timezone, if int: offset to GMT
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
     * Sets callback URLS.
     * 
     * Example calls:
     * 
     *  $client->callbacks(array('success'=>'http://...','error'=>'http://..'));
     * 
     *  $client->callbacks('success' => 'http://..');
     * 
     * @param array,string $urls
     * @param NULL,string $to
     * @return \Aspsms\AbstractSimpleClient
     * @see AbstractSimpleClient::setCallbackURL()
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
    
    
    /******************************************
     * Core request handling
     */
    
    
    /**
     * Sets any request option for current/next request.
     * 
     * @param string|array $key_or_array
     * @param NULL|mixed $value
     * @return \Aspsms\Soap\v2\SimpleClient
     * @throws ServiceException
     */
    public function set($key_or_array = NULL, $value = NULL)
    {
        // Initialize message data if not done yet.
        if ($this->currentRequest === NULL)
        {
            // set default request parameters (typically used).
            $this->currentRequest = new Request(array(
                'UserKey'       => $this->userkey,
                'Password'      => $this->password,
                'Originator'    => $this->originator,
                'AffiliateId'   => $this->affiliateId,
                'URLDeliveryNotification' => $this->urls['URLDeliveryNotification'],
                'URLNonDeliveryNotification' => $this->urls['URLNonDeliveryNotification'],
                'URLBufferedMessageNotification' => $this->urls['URLBufferedMessageNotification']
            ));
        }
        
        // If is string, bring into array form
        if (is_string($key_or_array))
        {
            $key_or_array = array($key_or_array => $value);
        }
        
        // Now set all fields
        foreach($key_or_array as $k => $v)
        {
            $this->currentRequest->set($k, $v);
        }
        
        return $this;
    }
    
    /**
     * Clear any message settings set through <set()> for current/next request.
     * 
     * @see set()
     * @return \Aspsms\AbstractSimpleClient
     */
    public function clear()
    {
        $this->currentRequest = NULL;
        
        return $this;
    }
    
    
    /**
     * Get driver to actually submit request.
     * 
     * @return AbstractClient Description
     * @access protected
     */
    abstract public function driver(&$request);
    
    /**
     * 
     * @param array $options
     * @return mixed
     * @throws ServiceException
     * @see \Aspsms\AbstractClient
     */
    public function send($options = array())
    {
        $this->set($options);
        
        if ($this->currentRequest->getRequestName() == NULL)
        {
            throw new ServiceException('RequestName of request not defined, please use a given method or define properly yourself.');
        }
        
        $request = $this->lastRequest = $this->currentRequest;
        
        // get driver to use
        $driver = $this->driver($request);
        
        // Sanity check
        if ( ! $driver->canProcess($request))
        {
            throw new ServiceException('Driver can not process request '.$request->getRequestName());
        }
        
        // send request
        $driver->send($request);
        
        // retrieve response
        $this->lastResponse = $driver->getResponse();
        
        // clear current request
        $this->currentRequest = NULL;
        
        
        return $this->lastResponse->result();
    }
}