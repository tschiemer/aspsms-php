<?php

namespace Aspsms\Xml;

class Client
{
    
    /**
     * List of satsfiable requests
     * 
     * @var string[]
     * @access protected
     * @see canPerform()
     */
    var $requests = array(
        'getCredits',
        
        'sendText',
        'sendWapPush',
        
        'sendToken',
        'verifyToken',
        
        'getDeliveryStatus',
        
        'checkOriginator',
        'sendOriginatorCode',
        'unlockOriginator',
        
        'sendPicture',
        'sendLogo',
        'sendGroupLogo',
        'sendRingtone',
        'sendVCard',
        'sendBinaryData'
    );
    
    public function __construct() {
        
    }
    
    public function __callService($name,$param = NULL)
    {
        
    }
    
    
    public function ShowCredits(); 
    
    public function SendTextSMS();
    public function SendWAPPushSMS();
    
    public function InquireDeliveryNotifications();
    
    public function SendOriginatorUnlockCode();
    public function UnlockOriginator();
    public function CheckOriginatorAuthorization();
    
    public function SendRandomLogo();
    public function SendPictureMessage();
    public function SendLogo();
    public function SendGroupLogo();
    public function SendRingtone();
    public function SendVCard();
    public function SendBinaryData();
}

