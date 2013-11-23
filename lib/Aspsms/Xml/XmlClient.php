<?php

namespace Aspsms;

/**
 * Driver for XML-based services.
 * 
 * @version 1
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class XmlClient extends AbstractClient
{
    const ENCODING = 'ISO-8859-1';
    
    /**
     * @var string
     */
    var $servers = array(
        'xml1.aspsms.com:5061/xmlsvr.asp',
        'xml1.aspsms.com:5098/xmlsvr.asp',
        'xml2.aspsms.com:5061/xmlsvr.asp',
        'xml2.aspsms.com:5098/xmlsvr.asp'
    );
    
    var $options = array(
        'encodingIn' => 'UTF-8',
        'encodingOut'=> 'UTF-8'
    );
    
    /**
     * @var \DOMDocument
     */
    var $requestDOM;
    
    /**
     * @var \DOMDocument
     */
    var $responseDOM;
    
    /**
     * @var string[]
     */
    var $entities = array();
    
    /**
     * List of satsfiable requests
     * 
     * @var string[]
     * @access protected
     * @see canPerform()
     */
    
    /**
     * Request configuration
     * 
     * @var array[]
     */
    var $requests = array(
        'getCredits'                => array(
                                        'action' => 'ShowCredits',
                                        'param'   => array(
                                            'UserKey'   => '',
                                            'Password'  => ''
                                       )),
        'getStatusCodeDescription'  => array(
                                        'action'   => 'GetStatusCodeDescription',
                                        'param'     => array(
                                        'StatusCode' => ''
                                        )),
        
        'sendText'                  => array(
                                        'action'   => 'SendTextSMS',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Recipients'=> '',
                                            'Originator'=> '',
                                            'MessageText' => '',
                                            'DeferredDeliveryTime' => '',
                                            'FlashingSMS'=> '',
                                            'TimeZone'  => '',
                                            'URLBufferedMessageNotification' => '',
                                            'URLDeliveryNotification' => '',
                                            'URLNonDeliveryNotification' => '',
                                            'AffiliateId' => ''
                                        )),
        'sendWapPush'               => array(
                                        'action'   => 'SimpleWAPPush',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Recipients'=> '',
                                            'Originator'=> '',
                                            'WapDescription' => '',
                                            'WapURL'    => '',
                                            'DeferredDeliveryTime' => '',
                                            'FlashingSMS'=> '',
                                            'TimeZone'  => '',
                                            'URLBufferedMessageNotification' => '',
                                            'URLDeliveryNotification' => '',
                                            'URLNonDeliveryNotification' => '',
                                            'AffiliateId' => ''
                                        )),
        'sendToken'                 => array(
                                        'action'   => 'SendTokenSMS',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Recipients'=> '',
                                            'Originator'=> '',
                                            'MessageData'=>'',
                                            'TokenReference'=>'',
                                            'TokenValidity'=>'5',
                                            'TokenMask' => '',
                                            'VerificationCode' => '',
                                            'TokenCaseSensitive' => '0',
                                            'URLBufferedMessageNotification' => '',
                                            'URLDeliveryNotification' => '',
                                            'URLNonDeliveryNotification' => '',
                                            'AffiliateId' => ''
                                        )),
        'verifyToken'               => array(
                                        'action'   => 'VerifyToken',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'PhoneNumber'=> '',
                                            'TokenReference'=>'',
                                            'VerificationCode' => '',
                                        )),
        
        'getDeliveryStatus'         => array(
                                        'action'   => 'InquireDeliveryNotifications',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'TransactionReferenceNumbers'=> ''
                                        )),
        
        'checkOriginator'           => array(
                                        'action'   => 'CheckOriginatorAuthorization',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> ''
                                        )),
        'sendOriginatorCode'        => array(
                                        'action'   => 'SendOriginatorUnlockCode',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> ''
                                        )),
        'unlockOriginator'          => array(
                                        'action'   => 'UnlockOriginator',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> '',
                                            'OriginatorUnlockCode'=>'',
                                            'AffiliateId'=> ''
                                        ))
    );
//    
//    var $requests = array(
//        'getCredits',
//        
//        'sendText',
//        'sendWapPush',
//        
//        'sendToken',
//        'verifyToken',
//        
//        'getDeliveryStatus',
//        
//        'checkOriginator',
//        'sendOriginatorCode',
//        'unlockOriginator',
//        
//        'sendPicture',
//        'sendLogo',
//        'sendGroupLogo',
//        'sendRingtone',
//        'sendVCard',
//        'sendBinaryData'
//    );
//    
    public function __construct() {
        
        $entities = array(
            chr(38) => '&amp;#38;',
            chr(60) => '&amp;#60;',
            chr(62) => '&amp;#62;'
        );
        for($i = 128; $i < 256; $i++)
        {
            $entities[chr($i)] = sprintf('&amp;#%03d;',$i);
        }
        $this->entities = array(
            'plain' => array_keys($entities),
            'xml'   => array_values($entities)
        );
        
        $test = mb_convert_encoding('ä',self::ENCODING,'UTF-8');
//        var_dump($this->entities);
        var_dump(str_replace($this->entities['plain'], $this->entities['xml'], $test));
    }
    
    public function canProcess($request) {
        return TRUE;
    }
    
    public function send($request) {
        
        // Set internal request
        $this->request = $request;
        
        // friendly shortcut
        $requestName = $request->getRequestName();
        
        
        $cfg =& $this->requests[$requestName];
        
        // friendly shortcut
        $actionName = $cfg['action'];
        
        // Initialize new response object
        $this->response = new Response($request);
        
        
        $this->requestDOM = new \DOMDocument('1.0','ISO-8859-1');
        $this->requestDOM->appendChild(new \DOMElement('aspsms'));
     
        if (isset($cfg['param']))
        {
            foreach($request->extractArray($cfg['param']) as $k => $v)
            {
                if (method_exists($this, 'pre_'.$k))
                {
                    $this->{'pre_'.$k}($v);
                }
                else
                {
                    $this->pre_default($k,$v);
                }
            }
        }
        
        $this->requestDOM->firstChild->appendChild(new \DOMElement('Action',$actionName));
        
//        $this->requestDOM->normalizeDocument();
        $xml = $this->requestDOM->saveXML();
//        
        $ch = curl_init($this->servers[0]);

//        curl_setopt($ch, CURLOPT_PORT, 5061);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_POST, TRUE);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // just always set this.
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        if ($result === FALSE or ! preg_match('/<\?xml version="((?:\d|\.)+)"\?>/',$result,$m))    
        {
            var_dump($result);
            die("failed curl request\n");
            exit("\n");
        }
        else
        {
            var_dump($result);
            
            $this->responseDOM = new \DOMDocument($m[1],self::ENCODING);
            $this->responseDOM->loadXML($result);
            
            $this->response->statusCode(
                    $this->responseDOM->getElementsByTagName('ErrorCode')->item(0)->textContent
            );
            
            $this->response->statusDescription(
                    $this->responseDOM->getElementsByTagName('ErrorDescription')->item(0)->textContent
            );
//            $this->response->result = $dom->textContent;
        }
//        var_dump($this->requestDOM);
    }
    
    /**
     * 
     * @param string $value
     * @return string
     * @access protected
     */
    public function encodeIn($value)
    {
        if ($this->options['encodingIn'] !== self::ENCODING)
        {
            $value = mb_convert_encoding($value, self::ENCODING, $this->options['encodingIn']);
        }
        $value = str_replace($this->entities['plain'], $this->entities['xml'], $value);
        var_dump($value);
        return $value;
    }
    
    /**
     * 
     * @param string $value
     * @return string
     * @access protected
     */
    public function encodeOut($value)
    {
        if ($this->options['encodingOut'] !== self::ENCODING)
        {
            return mb_convert_encoding($value, $this->options['encodingOut'], self::ENCODING);
        }
        return mb_encode_numericentity($value, array(0x80, 0xff, 0, 0xff), self::ENCODING);
    }
    
    public function pre_default($key,$value)
    {
//        if ( ! empty($value))
        {
            $node = new \DOMElement($key, $this->encodeIn($value));
    //        $node = new \DOMCharacterData();
    //        $node->appendData($this->encodeIn($value));
            $this->requestDOM->firstChild->appendChild($node);
        }
    }
    
    public function pre_UserKey($value)
    {
        $this->pre_default('Userkey', $value);
    }
    
    public function pre_Recipients($value)
    {
        if (strlen($value) == 0)
        {
            return;
        }
        
        $recipientList = explode(';',$value);
        foreach($recipientList as $recipient)
        {
            $phone_track = explode(':',$recipient);
            
            $recipientNode = new \DOMElement('Recipient');
            
            $this->requestDOM->firstChild->appendChild($recipientNode);
            
//            $a=new \DOMElement('PhoneNumber', $phone_track[0]);
            $a = new \DOMCharacterData();
            $a->data = $phone_track[0];
            $recipientNode->appendChild($a);
            
            if (count($phone_track) == 1)
            {
                $recipientNode->appendChild(new \DOMElement('TransRefNumber'));
            }
            else
            {
                $recipientNode->appendChild(new \DOMElement('TransRefNumber', $phone_track[1]));
            }
        }
    }
    
    
//    
//    public function ShowCredits(); 
//    
//    public function SendTextSMS();
//    public function SendWAPPushSMS();
//    
//    public function InquireDeliveryNotifications();
//    
//    public function SendOriginatorUnlockCode();
//    public function UnlockOriginator();
//    public function CheckOriginatorAuthorization();
//    
//    public function SendRandomLogo();
//    public function SendPictureMessage();
//    public function SendLogo();
//    public function SendGroupLogo();
//    public function SendRingtone();
//    public function SendVCard();
//    public function SendBinaryData();
}

