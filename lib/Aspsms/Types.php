<?php

namespace Aspsms;

class Response
{
    /**
     * 
     * @param int $code
     * @param str $msg
     */
    public function __construct($code,$msg)
    {
        $this->status_code = $code;
        $this->status_msg  = $msg;
    }
    
    public function success()
    {
        return ! preg_match('/^StatusCode\:(\d+)$/',$this->result(),$m);
    }
    
    public function getStatusCode()
    {
        if (preg_match('/^StatusCode:(\d+)$/',$this->result(), $m))
        {
            return $m[1];
        }
        else
        {
            return ServiceInterface::E_OK;
        }
    }
    
    /**
     * @var string
     */
//    protected $CheckCreditsResult;

    /**
     * @var string
     */
//    protected $CheckOriginatorAuthorizationResult;
    
    /**
     * @var string
     */
//    protected $GetStatusCodeDescriptionResult;
    
    /**
     * @var string
     */
//    protected $InquireDeliveryNotificationsResult;
    
    /**
     * @var string
     */
//    protected $SendOriginatorUnlockCodeResult;
    
    /**
     * @var string
     */
//    protected $SendSimpleTextSMSResult;
    
    /**
     * @var string
     */
//    protected $SendTextSMSResult;
    
    /**
     * @var string
     */
//    protected $SendTokenSMSResult;
    
    /**
     * @var string
     */
//    protected $SendUnicodeSMSResult;

    /**
     * @var string
     */
//    protected $SimpleWAPPushResult;

    /**
     * @var string
     */
//    protected $UnlockOriginatorResult;

    /**
     * @var string
     */
//    protected $VersionInfoResult;
    
    /**
     * @var string
     */
//    protected $VerifyTokenResult;
    
    /**
     * Get generic result
     * 
     * @return string
     * @throws Exception
     */
    public function result()
    {
        if ( preg_match('/([a-zA-Z_]+)Response$/',get_class($this),$m))
        {
            return $this->{$m[1] . 'Result'};
        }
        else
        {
            throw new AspsmsException('unknown response type: '.get_class($this));
        }
    }
}


abstract class Request
{
    /**
     * @see RequestMessage
     * @var string
     */
    var $Recipients;
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $Originator;
    
    
    /**
     * @see GetStatusCodeDescription
     * @var string StatusCode:N
     */
    var $StatusCode;
    
    
    /**
     * @see SendSimpleTextSMS,SendTextSMS
     * @var type 
     */
    var $MessageText;
    
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $DeferredDeliveryTime = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $FlashingSMS = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $TimeZone = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $URLBufferedMessageNotification = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $URLDeliveryNotification = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $URLNonDeliveryNotification = '';
    
    /**
     * @see RequestMessage
     * @var string
     */
    var $AffiliateId = '';
    
    
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
            $this->Recipients = implode(';',$tmp);
        }
        else
        {
            $this->Recipients = $recipients;
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
     * 
     * @param string $message
     */
    public function setMessageText($message = NULL)
    {
        $this->MessageText = $message === NULL ? '' : $message;
    }
    
    /**
     * @param string $affiliateId
     */
    public function setAffiliateId($affiliateId)
    {
        $this->AffiliateId = $affiliateId;
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
     */
    public function setDeferredDeliveryTime($datetime)
    {
        if (is_int($datetime))
        {
            $this->DeferredDeliveryTime = date('dmYHis',$datetime);
        }
        elseif ($datetime instanceof \DateTime)
        {
            $this->DeferredDeliveryTime = $datetime->format('dmYHis');
        }
        else
        {
            $this->DeferredDeliveryTime = strval($sec);
        }
    }
    
    /**
     * @param boolean $flash
     */
    public function setFlashingSMS($flash)
    {
        $this->FlashingSMS = $flash ? 'True' : '';
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
            $this->TimeZone = strval($timezone->getOffset());
        }
        else
        {
            $this->TimeZone = strval(intval($timezone));
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
        $this->URLDeliveryNotification = strval($url);
    }
    
    /**
     * @see setURLDeliveryNotification()
     * @param string $url
     */
    public function setURLNonDeliveryNotification($url)
    {
        $this->URLNonDeliveryNotification = strval($url);
    }
    
    /**
     * @see setURLDeliveryNotification()
     * @param string $url
     */
    public function setURLBufferedMessageNotification($url)
    {
        $this->URLBufferedMessageNotification = strval($url);
    }
}

/**
 * 
 */
class RequestAuth extends Request
{
    public function __construct($userkey,$password) {
        $this->UserKey  = $userkey;
        $this->Password = $password;
    }
    
    /**
     * @var string
     */
    var $UserKey;
    
    /**
     * @var string
     */
    var $Password;
    
}

/**
 * 
 */
abstract class RequestMessage extends RequestAuth
{
    public function __construct($userkey,$password,$originator)
    {
        parent::__construct($userkey, $password);
        $this->Originator   = $originator;
    }
}
/**
 * 
 */
class GetStatusCodeDescription extends Request
{
    var $StatusCode = '';
    
    /**
     * @param int|string $statusCode Either status code as int, or complete status msg as string
     */
    public function __construct($statusCode)
    {
        if (is_int($statusCode))
        {
            $this->StatusCode = 'StatusCode:'.$statusCode;
        }
        else
        {
            $this->StatusCode = $statusCode;
        }
    }
}

/**
 * 
 */
class CheckOriginatorAuthorization extends RequestMessage {}


/**
 * 
 */
class SendSimpleTextSMS extends RequestMessage
{   
    /**
     * As according to specification, but in fact the {PHONE:TRACK ; ..}+ format can
     * be used.
     * @param string|array $recipients
     */
    public function setRecipients($recipients)
    {
        if (is_array($recipients))
        {
            $this->Recipients = implode(';',$recipients);
        }
        else
        {
            $this->Recipients = $recipients;
        }
    }
}

/**
 * 
 */
class SendTextSMS extends RequestMessage {}

/**
 * 
 */
class SendUnicodeSMS extends RequestMessage {}


/**
 * 
 */
class InquireDeliveryNotifications extends RequestAuth {
    
    /**
     * @var string
     */
    var $TransactionReferenceNumbers;
    
    public function __construct($userkey, $password,$trackingnr) {
        parent::__construct($userkey, $password);
        
        if (is_array($trackingnr))
        {
            $this->TransactionReferenceNumbers = implode(';',$trackingnr);
        }
        else
        {
            $this->TransactionReferenceNumbers = strval($trackingnr);
        }
    }
    
}




class SimpleWAPPush extends RequestMessage
{
    var $WapDescription = '';
    var $WapURL = '';
    
    public function setWapDescription($desc)
    {
        $this->WapDescription = strval($desc);
    }
    
    public function setWapURL($url)
    {
        $this->WapURL = strval($url);
    }
}


/**
 * Official doc:
 * 
 * The verification code can either be generated by mask using TokenMask or
 * explicitly specified using VerificationCode.
 * 
 * @link http://aspsms.ch/asptoken/documentation/home.asp?lng=en
 */
class SendTokenSMS extends RequestMessage
{    
    /**
     * @var string
     */
    var $MessageData = '';
    
    /**
     * @var string
     */
    var $TokenReference = '';
    
    /**
     * @var string
     */
    var $TokenValidity = '';
    
    /**
     * @var string
     */
    var $TokenMask = '';
    
    /**
     * @var string
     */
    var $VerificationCode = '';
    
    /**
     * @var string
     */
    var $TokenCaseSensitive = '0';
    
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
        $this->MessageData = strval($data);
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
        $this->TokenReference = strval($ref);
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
        if ( ! empty($valid))
        {
            $this->TokenValidity = strval(intval($valid));
        }
        else
        {
            $this->TokenValidity = '';
        }
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
        $this->TokenMask = strval($mask);
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
        $this->VerificationCode = strval($code);
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
        $this->TokenCaseSensitive = $is_sensitive ? '1' : '0';
    }
}



//class CheckCredits extends Request {}


class SendOriginatorUnlockCode extends RequestAuth {
    
    var $Originator;
    
    public function __construct($userkey,$password,$originator)
    {
        parent::__construct($userkey, $password);
        $this->Originator = $originator;
    }
}


class UnlockOriginator extends Request
{
    var $Originator;
    var $OriginatorUnlockCode;
    var $AffiliateId;
}


class VerifyToken extends RequestAuth
{
    var $PhoneNumber;
    var $TokenReference;
    var $VerificationCode;
    
    public function __construct($userkey, $password, $phoneNr, $tokenRef, $verificationCode) {
        parent::__construct($userkey, $password);
        
        $this->PhoneNumber      = strval($phoneNr);
        $this->TokenReference   = strval($tokenRef);
        $this->VerificationCode = strval($verificationCode);
    }
}







/**
 * 
 */
class CheckCreditsResponse extends Response {}

/**
 * 
 */
class CheckOriginatorAuthorizationResponse extends Response
{   
    public function success()
    {
        return ServiceInterface::E_OK == $this->getStatusCode();
    }
}

/**
 * 
 */
class GetStatusCodeDescriptionResponse extends Response {}

class InquireDeliveryNotificationsResponse extends Response {}

class SendOriginatorUnlockCodeResponse extends Response {}

class SendSimpleTextSMSResponse extends Response
{
    public function success()
    {
        return ServiceInterface::E_OK == $this->getStatusCode();
    }
}

class SendTextSMSResponse extends Response {}

class SendTokenSMSResponse extends Response {}

class SendUnicodeSMSResponse extends Response {}

class SimpleWAPPushResponse extends Response {}

class UnlockOriginatorResponse extends Response {}


/**
 * 
 */
class VersionInfoResponse extends Response
{
    /**
     * Get web service version
     * 
     * @return string
     */
    public function version()
    {
        if (preg_match('/^([^ ]+)/',$this->VersionInfoResult,$m))
        {
            return $m[1];
        }
        return '';
    }
    
    /**
     * Get build number
     * 
     * @return string
     */
    public function build()
    {
        if (preg_match('/build:((?:\d|\.)+)/',$this->VersionInfoResult,$m))
        {
            return $m[1];
        }
        return '';
    }
}


class VerifyTokenResponse extends Response
{
    public function success()
    {
        return ServiceInterface::E_OK == $this->getStatusCode();
    }
}


class AspsmsException extends \Exception {}