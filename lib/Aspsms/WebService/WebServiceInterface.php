<?php

namespace Aspsms\Soap\v2;

interface ServiceInterface
{
    const E_SOAP            = 0;
    const E_OK              = 1;
    const E_WRONG_AUTH      = 3;
    const E_NO_CREDITS      = 5;
    const E_ORIGIN_TOO_LONG = 10;
    const E_ORIGIN_NOT_AUTH = 30;
    
    
    /**
     * @param \Aspsms\Soap\CheckCredits $parameters
     * @return \Aspsms\Soap\CheckCreditsResponse
     */
    public function CheckCredits($parameters);
    
    /**
     * @param \Aspsms\Soap\CheckOriginatorAuthorization $parameters
     * @return \Aspsms\Soap\CheckOriginatorAuthorizationResponse
     */
    public function CheckOriginatorAuthorization($parameters);
    
    /**
     * @param \Aspsms\Soap\GetStatusCodeDescription $parameters
     * @return \Aspsms\Soap\GetStatusCodeDescription
     */
    public function GetStatusCodeDescription($parameters);
    
    /**
     * @param \Aspsms\Soap\InquireDeliveryNotifications $parameters
     * @return \Aspsms\Soap\InquireDeliveryNotifications
     */
    public function InquireDeliveryNotifications($parameters);
    
    /**
     * @param \Aspsms\Soap\SendOriginatorUnlockCode $parameters
     * @return \Aspsms\Soap\SendOriginatorUnlockCodeResponse
     */
    public function SendOriginatorUnlockCode($parameters);
    
    /**
     * @param \Aspsms\Soap\SendSimpleTextSMS $parameters
     * @return \Aspsms\Soap\SendSimpleTextSMSResponse 
     */
    public function SendSimpleTextSMS($parameters);
    
    /**
     * @param \Aspsms\Soap\SendTextSMS $parameters
     * @return \Aspsms\Soap\SendTextSMSResponse
     */
    public function SendTextSMS($parameters);
         
    /**
     * @param \Aspsms\Soap\SendTokenSMS $parameters
     * @return \Aspsms\Soap\SendTokenSMSResponse
     */
    public function SendTokenSMS($parameters);
    
    /**
     * @param \Aspsms\Soap\SendUnicodeSMS $parameters
     * @return \Aspsms\Soap\SendUnicodeSMSResponse
     */
    public function SendUnicodeSMS($parameters);
    
    /**
     * @param \Aspsms\Soap\SimpleWAPPush $parameters
     * @return \Aspsms\Soap\SimpleWAPPushResponse
     */
    public function SimpleWAPPush($parameters);
    
    /**
     * @param \Aspsms\Soap\UnlockOriginator $parameters
     * @return \Aspsms\Soap\UnlockOriginatorResponse
     */
    public function UnlockOriginator($parameters);
    
    /**
     * @param \Aspsms\Soap\VerifyToken $parameters
     * @return \Aspsms\Soap\VerifyTokenResponse
     */
    public function VerifyToken($parameters);

    /**
     * @return \Aspsms\Soap\VersionInfo
     */
    public function VersionInfo();
    
}