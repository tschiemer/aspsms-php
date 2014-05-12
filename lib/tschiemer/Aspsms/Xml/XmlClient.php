<?php

namespace tschiemer\Aspsms\Xml;
use \tschiemer\Aspsms as Aspsms;

if ( ! function_exists('curl_init'))
{
    throw new \Exception('CURL extension required for Aspsms\HttpClient');
}

/**
 * Driver for XML-based services.
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class XmlClient extends Aspsms\AbstractClient
{
    const ENCODING = 'ISO-8859-1';
    
    /**
     * Available urls for xml interface
     * 
     * @var string[]
     */
    var $servers = array(
        'http://xml1.aspsms.com:5061/xmlsvr.asp',
        'http://xml1.aspsms.com:5098/xmlsvr.asp',
        'http://xml2.aspsms.com:5061/xmlsvr.asp',
        'http://xml2.aspsms.com:5098/xmlsvr.asp'
    );
    
    /**
     * internal options
     * 
     * @var string[]
     */
    var $options = array(
        'encodingIn' => 'UTF-8',
        'encodingOut'=> 'UTF-8'
    );
    
    /**
     * List of CURL options to use.
     * 
     * @var array
     */
    var $curlOpt = array(
        CURLOPT_USERAGENT       => 'aspsms-php v1 xml:1',
        CURLOPT_SSL_VERIFYPEER  => FALSE
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
                                        'action'   => 'SendWAPPushSMS',
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
                                        )),
        'sendPicture'               => array(),
        'sendLogo'                  => array(),
        'sendGroupLogo'             => array(),
        'sendRingtone'              => array(
                                        'action'    => 'SendRingtone',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> '',
                                            'AffiliateId'=> '',
                                            'Recipients'=> '',
                                            'URLBinaryFile' => ''
                                        )),
        'sendVCard'                 => array(
                                        'action'    => 'SendVCard',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> '',
                                            'AffiliateId'=> '',
                                            'Recipients'=> '',
                                            'VCard'     => array()
                                        )),
        'sendBinaryData'            => array()
    );
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

    public function __construct()
    {   
        if (isset($options['servers']))
        {
            $this->servers = $options['servers'];
        }
        
        if (isset($options['encodingIn']))
        {
            $this->options['encodingIn'] = $options['encodingIn'];
        }
        if (isset($options['encodingOut']))
        {
            $this->options['encodingOut'] = $options['encodingOut'];
        }
        
        if (isset($options['curl']))
        {
            foreach($options['curl'] as $k => $v)
            {
                $this->curlOpt[$k] = $v;
            }
        }
        
        $entities = array(
            chr(38) => '&#38;',
            chr(60) => '&#60;',
            chr(62) => '&#62;'
        );
        for($i = 128; $i < 256; $i++)
        {
            $entities[chr($i)] = sprintf('&#%03d;',$i);
        }
        $this->entities = array(
            'plain' => array_keys($entities),
            'xml'   => array_values($entities)
        );
    }
    
    /**
     * Send given request.
     * 
     * @param \Aspsms\Request $request
     * @throws AspsmsException
     * @see AbstractClient::getResponse()
     */
    public function send($request) {
        
        // Set internal request
        $this->request = $request;
        
        // friendly shortcut
        $requestName = $request->getRequestName();
        
        
        $cfg =& $this->requests[$requestName];
        
        // friendly shortcut
        $actionName = $cfg['action'];
        
        // Initialize new response object
        $this->response = new Aspsms\Response($request);
        
        
        $this->requestDOM = new \DOMDocument('1.0','ISO-8859-1');
        $this->requestDOM->appendChild(new \DOMElement('aspsms'));
     
        if (isset($cfg['param']))
        {
            foreach($request->extractArray($cfg['param']) as $k => $v)
            {
                if (method_exists($this, 'set_'.$k))
                {
                    $this->{'set_'.$k}($v);
                }
                else
                {
                    $this->set_default($k,$v);
                }
            }
        }
        
        $this->requestDOM->firstChild->appendChild(new \DOMElement('Action',$actionName));
        
//        $this->requestDOM->normalizeDocument();
        $xml = $this->requestDOM->saveXML();
        
        var_dump($xml);
//        exit;
//        
        $ch = curl_init($this->servers[0]);

        curl_setopt_array($ch, $this->curlOpt);
        
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => $xml
        ));
//        curl_setopt($ch, CURLOPT_PORT, 5061);
        
        $result = curl_exec($ch);
        
        curl_close($ch);
        
        if (is_string($result) and preg_match('/<\?xml version="((?:\d|\.)+)"\?>/',$result,$m))    
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
        else   
        {
            var_dump($result);
            die("failed curl request\n");
        }

        
        // Result post-processing
        if (method_exists($this, 'post_'.$actionName))
        {
            $this->{'post_'.$actionName}();
        }
        else
        {
            $this->post_default();
        }
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
        return str_replace($this->entities['plain'], $this->entities['xml'], $value);
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
            $value = mb_convert_encoding($value, $this->options['encodingOut'], self::ENCODING);
        }
        return $value;
    }
    
    public function set_default($key,$value)
    {
//        if ( ! empty($value))
        {
            $node = new \DOMElement($key, $this->encodeIn($value));
            $this->requestDOM->firstChild->appendChild($node);
        }
    }
    
    public function set_UserKey($value)
    {
        $this->set_default('Userkey', $value);
    }
    
    public function set_Recipients($value)
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
            
            $recipientNode->appendChild(new \DOMElement('PhoneNumber', $phone_track[0]));
            
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
    
    public function set_MessageText($value)
    {
        $this->set_default('MessageData', $value);
    }
    
    public function set_TransactionReferenceNumbers($value)
    {
        if (strlen($value) == 0)
        {
            return;
        }
        
        $list = explode(';',$value);
        foreach($list as $refNr)
        {
            $this->requestDOM->firstChild->appendChild(new \DOMElement('TransRefNumber', $refNr));
        }
    }
    
    public function set_VCard($value)
    {
        if (empty($value) or ! is_array($value))
        {
            return;
        }
        
        $vcard = new \DOMElement('VCard');
            
        $this->requestDOM->firstChild->appendChild($vcard);

        $vcard->appendChild(new \DOMElement('VName', $value['name']));
        $vcard->appendChild(new \DOMElement('VPhoneNumber', $value['phoneNr']));
    }
    
    /**
     * Default Post-Processor
     */
    public function post_default()
    {
        $this->response->result = $this->response->statusCode() == 1;
    }
    
    /**
     * Post-Processing for ShowCredits
     */
    public function post_ShowCredits()
    {
        $this->response->result = floatval($this->responseDOM->getElementsByTagName('Credits')->item(0)->textContent);
    }
}

