<?php

namespace tschiemer\Aspsms;

/**
 * Shared request object used for request abstraction.
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class Request
{   
    /**
     * Request name
     * @var string
     */
    var $requestName = NULL;
    
    /**
     * @var array
     */
    var $fields = array();
    
    public function __construct($data = array())
    {
        foreach($data as $k => $v)
        {
            $this->set($k, $v);
        }
    }
    
    public function setRequestName($name)
    {
//        if ( ! in_array($name, array(
//            'getVersion',
//            'getCredits',
//            'getStatusCodeDescription',
//
//            'sendText',
//            'sendWapPush',
//
//            'sendToken',
//            'verifyToken',
//
//            'getDeliveryStatus',
//
//            'checkOriginator',
//            'sendOriginatorCode',
//            'unlockOriginator',
//
//            'sendPicture',
//            'sendLogo',
//            'sendGroupLogo',
//            'sendRingtone',
//            'sendVCard',
//            'sendBinaryData'
//        )))
//        
        $this->requestName = $name;
    }
    
    /**
     * Getter
     * @return string
     */
    public function getRequestName()
    {
        return $this->requestName;
    }
    
    public function set($key,$value)
    {
        if (method_exists($this, 'set'.$key))
        {
            $this->{'set'.$key}($value);
        }
        else
        {
            $this->fields[$key] = $value;
        }
    }
    
    public function get($key)
    {
        if (isset($this->fields[$key]))
        {
            return $this->fields[$key];
        }
        return NULL;
    }
    
    public function getFieldsAsArray()
    {
        return $this->fields;
    }
    
    public function getFieldsAsObject()
    {
        $obj = new \stdClass();
        foreach($this->getFieldsAsArray() as $key => $value)
        {
            $obj->$key = $value;
        }
        
        return $obj;
    }
    
    public function extractArray($fieldList = array())
    {
        $filtered = array_intersect_key($this->fields, $fieldList);
        
        $r = array_merge($fieldList,$filtered);
        
        return $r;
    }
    
    public function extractObject($fieldList = array())
    {
        $obj = new \stdClass();
        foreach($this->extractArray($fieldList) as $key => $value)
        {
            $obj->$key = $value;
        }
        
        return $obj;
    }
    
    public function setStatusCode($statusCode)
    {
        if (is_int($statusCode))
        {
            $this->fields['StatusCode'] = 'StatusCode:'.$statusCode;
        }
        else
        {
            $this->fields['StatusCode'] = $statusCode;
        }
    }
    
    public function setTransactionReferenceNumbers($trackingnr)
    {
        if (is_array($trackingnr))
        {
            $this->fields['TransactionReferenceNumbers'] = implode(';',$trackingnr);
        }
        else
        {
            $this->fields['TransactionReferenceNumbers'] = strval($trackingnr);
        }
    }
    
    /**
     * Set recipients of message.
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
     * @param string|array $recipients
     */
    public function setRecipients($recipients)
    {
        if (is_array($recipients))
        {
            $tmp = array();
            foreach($recipients as $track => $nr)
            {
                $tmp[] = $nr.':'.$track;
            }
            $this->fields['Recipients'] = implode(';',$tmp);
        }
        else
        {
            $this->fields['Recipients'] = $recipients;
//            if (preg_match_all('/(?:([^;]+):([^;]+);?+)/',$recipients,$m))
//            {
//                $this->Recipients = $recipients;
//            }
//            elseif (preg_match_all('/([^;]+);?/',$recipients,$m))
//            {
////                $tmp = explode(';',$recipients);
////                $this->setRecipients($tmp);
//                $this->Recipients = $recipients;
//            }
        }
    }
    
    /**
     * Sets intended delivery time.
     * 
     * Formats:
     *  string      ddmmyyyyhhmmss
     *  int         unit timestamp
     *  DateTime    
     * 
     * @param int|string|\DateTime $datetime
     * @see \Aspsms\Request::setTimeZone()
     */
    public function setDeferredDeliveryTime($datetime)
    {
        if (is_int($datetime))
        {
            $this->fields['DeferredDeliveryTime'] = date('dmYHis',$datetime);
        }
        elseif ($datetime instanceof \DateTime)
        {
            $this->fields['DeferredDeliveryTime'] = $datetime->format('dmYHis');
        }
        else
        {
            $this->fields['DeferredDeliveryTime'] = strval($sec);
        }
    }
    
    /**
     * @param boolean $flash
     */
    public function setFlashingSMS($flash)
    {
        $this->fields['FlashingSMS'] = $flash ? 'True' : '';
    }
    
    /**
     * Sets timezone to use when sending a deferred sms
     * Offset to GMT
     * 
     * @see setDeferredDeliveryTime()
     * @param int|string|DateTimeZone $timezone
     */
    public function setTimeZone($timezone)
    {
        if ($timezone instanceof \DateTimeZone)
        {
            $this->fields['TimeZone'] = strval($timezone->getOffset());
        }
        else
        {
            $this->fields['TimeZone'] = strval(intval($timezone));
        }
    }
    
    /**
     * Two modes:
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
     * @param string $url
     */
    public function setURLDeliveryNotification($url)
    {
        $this->fields['URLDeliveryNotification'] = strval($url);
    }
    
    /**
     * @see setURLDeliveryNotification()
     * @param string $url
     */
    public function setURLNonDeliveryNotification($url)
    {
        $this->fields['URLNonDeliveryNotification'] = strval($url);
    }
    
    /**
     * @see setURLDeliveryNotification()
     * @param string $url
     */
    public function setURLBufferedMessageNotification($url)
    {
        $this->fields['URLBufferedMessageNotification'] = strval($url);
    }
    
    /**
     * Official doc:
     * 
     * If MessageData is set, the placeholder <VERIFICATIONCODE> will be
     * substituted with the verification code. If MessageData is not defined,
     * or if MessageData does not contain the placeholder <VERIFICATIONCODE>,
     * only the verification code is sent.
     * 
     * @param string $data
     */
    public function setMessageData($data)
    {
        $this->fields['MessageData'] = strval($data);
    }
    
    /**
     * Official doc:
     * 
     * Explicitly specifies the verification code to be sent to the user.
     * 
     * @param string $ref
     */
    public function setTokenReference($ref)
    {
        $this->fields['TokenReference'] = strval($ref);
    }
    
    /**
     * Official doc:
     * 
     * Specifies the validity period of a Token in minutes.
     * If not specified, the TokenValidity is 5 minutes by default.
     * 
     * @param int $minutes
     */
    public function setTokenValidity($minutes = 5)
    {
        $this->fields['TokenValidity'] = strval(intval($minutes));
    }
    
    /**
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
     * @param string $mask
     */
    public function setTokenMask($mask)
    {
        $this->fields['TokenMask'] = strval($mask);
    }
    
    /**
     * Official doc:
     * 
     * Explicitly specifies the verification code to be sent to the user.
     * 
     * @param string $code
     */
    public function setVerificationCode($code)
    {
        $this->fields['VerificationCode'] = strval($code);
    }
    
    
    /**
     * Official doc:
     * 
     * Specifies, if the verification code comparison is case sensitive:
     *  1 : case sensitive
     *  0 : not case sensitive
     *  If not specified, TokenCaseSensitive is 0 by default.
     *
     * @param boolean $is_sensitive
     */
    public function setTokenCaseSensitive($is_sensitive = FALSE)
    {
        $this->fields['TokenCaseSensitive'] = $is_sensitive ? '1' : '0';
    }
}