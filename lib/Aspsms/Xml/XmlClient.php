<?php

namespace Aspsms\Xml;

class Client
{
    public function __construct() {
        
    }
    
    public function __callService($name,$param = NULL)
    {
        
    }
    
    public function SendRandomLogo();
    public function SendPictureMessage();
    public function SendLogo();
    public function SendGroupLogo();
    public function SendRingtone();
    public function SendVCard();
    public function SendBinaryData();
    
    
    public function SendTextSMS();
    public function InquireDeliveryNotifications();
    public function ShowCredits(); 
    public function SendWAPPushSMS();
    public function SendOriginatorUnlockCode();
    public function UnlockOriginator();
    public function CheckOriginatorAuthorization();
}

