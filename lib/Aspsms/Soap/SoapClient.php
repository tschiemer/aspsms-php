<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../AbstractClient.php';

class SoapClient extends AbstractClient
{
    /**
     * @var \SoapClient
     */
    var $soap;
    
    /**
     *
     * @var array[]
     */
    var $requests = array(
        'getVersion'                => array(
                                        'service' => 'VersionInfo'
                                        ),
        'getCredits'                => array(
                                        'service' => 'CheckCredits',
                                        'param'   => array(
                                            'UserKey'   => '',
                                            'Password'  => ''
                                       )),
        'getStatusCodeDescription'  => array(
                                        'service'   => 'GetStatusCodeDescription',
                                        'param'     => array(
                                        'StatusCode' => ''
                                        )),
        
        'sendText'                  => array(
                                        'service'   => 'SendUnicodeSMS',
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
                                        'service'   => 'SimpleWAPPush',
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
                                        'service'   => 'SendTokenSMS',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Recipients'=> '',
                                            'Originator'=> '',
                                            'MessageData'=>'',
                                            'TokenReference'=>'',
                                            'TokenValidity'=>'',
                                            'TokenMask' => '',
                                            'VerificationCode' => '',
                                            'TokenCaseSensitive' => '',
                                            'URLBufferedMessageNotification' => '',
                                            'URLDeliveryNotification' => '',
                                            'URLNonDeliveryNotification' => '',
                                            'AffiliateId' => ''
                                        )),
        'verifyToken'               => array(
                                        'service'   => 'VerifyToken',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'PhoneNumber'=> '',
                                            'TokenReference'=>'',
                                            'VerificationCode' => '',
                                        )),
        
        'getDeliveryStatus'         => array(
                                        'service'   => 'InquireDeliveryNotifications',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'TransactionReferenceNumbers'=> ''
                                        )),
        
        'checkOriginator'           => array(
                                        'service'   => 'CheckOriginatorAuthorization',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> ''
                                        )),
        'sendOriginatorCode'        => array(
                                        'service'   => 'SendOriginatorUnlockCode',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> ''
                                        )),
        'unlockOriginator'          => array(
                                        'service'   => 'UnlockOriginator',
                                        'param'     => array(
                                            'UserKey'   => '',
                                            'Password'  => '',
                                            'Originator'=> '',
                                            'OriginatorUnlockCode'=>'',
                                            'AffiliateId'=> ''
                                        ))
    );
    
    /**
     * 
     * @param type $wsdl
     * @param type $options
     * @throws \SoapFault Most likely
     */
    public function __construct($options = array())
    {
        if (isset($options['wsdl']))
        {
            $wsdl = $options['wsdl'];
        }
        else
        {
            $wsdl = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL';
        }
        
        // Set default classmap iff not defined already
//        if ( ! isset($options['classmap']))
//        {
//            $options['classmap'] = array(
//                'CheckCreditsResponse'                  => '\Aspsms\Soap\v2\CheckCreditsResponse',
//                'CheckOriginatorAuthorizationResponse'  => '\Aspsms\Soap\v2\CheckOriginatorAuthorizationResponse',
//                'GetStatusCodeDescriptionResponse'      => '\Aspsms\Soap\v2\GetStatusCodeDescriptionResponse',
//                'InquireDeliveryNotificationsResponse'  => '\Aspsms\Soap\v2\InquireDeliveryNotificationsResponse',
//                'SendOriginatorUnlockCodeResponse'      => '\Aspsms\Soap\v2\SendOriginatorUnlockCodeResponse',
//                'SendSimpleTextSMSResponse'             => '\Aspsms\Soap\v2\SendSimpleTextSMSResponse',
//                'SendTextSMSResponse'                   => '\Aspsms\Soap\v2\SendTextSMSResponse',
//                'SendTokenSMSResponse'                  => '\Aspsms\Soap\v2\SendTokenSMSResponse',
//                'SendUnicodeSMSResponse'                => '\Aspsms\Soap\v2\SendUnicodeSMSResponse',
//                'SimpleWAPPushResponse'                 => '\Aspsms\Soap\v2\SimpleWAPPushResponse',
//                'UnlockOriginatorResponse'              => '\Aspsms\Soap\v2\UnlockOriginatorResponse',
//                'VersionInfoResponse'                   => '\Aspsms\Soap\v2\VersionInfoResponse',
//                'VerifyTokenResponse'                   => '\Aspsms\Soap\v2\VerifyTokenResponse'
//            );
//        }
        
        // Disable wsdl caching, if not set otherwise
        if ( ! isset($options['cache_wsdl']))
        {
            $options['cache_wsdl'] = WSDL_CACHE_NONE;
        }
        
        try {
            $this->soap = new \SoapClient($wsdl, $options);
        }
        catch (\SoapFault $e) {
            $this->soap = NULL;
            throw new AspsmsException('Could not retrieve WSDL or is invalid.');
        }
    }
    
    public function canProcess($request)
    {
        if ($request instanceof Request)
        {
            $request = $request->getRequestName();
        }
        return array_key_exists($request, $this->requests);
    }
    
    /**
     * 
     * @param Request $request
     */
    public function send($request)
    {
        $this->request = $request;
        
        $requestName = $request->getRequestName();
        
        
        $cfg =& $this->requests[$requestName];
        
        $serviceName = $cfg['service'];
        
        
        $this->response = new Response($request);
        
        try {
             if (isset($cfg['param']))
            {
                $param = $request->extractObject($cfg['param']);
            }
            else
            {
                $param = NULL;
            }
            
//            var_dump($param);
//            exit;
            
            // returns stdClass
            $soapResponse = $this->soap->$serviceName($param);
//            if (isset($cfg['param']))
//            {
//                $param = array($request->extractObject($cfg['param']));
//            }
//            else
//            {
//                $param = array();
//            }
//            
//            // returns stdClass
//            $soapResponse = $this->soap->__soapCall($serviceName, $param);
            
            // get result field
            $this->response->result = $soapResponse->{$serviceName.'Result'};
            
        }
        catch (\SoapFault $e)
        {   
            var_dump($e);
            var_dump($this->soap->__getLastRequest());
            exit("\n");
        }
        
        // Result post-processing
        if (method_exists($this, 'post_'.$serviceName))
        {
            $this->{'post_'.$serviceName}();
        }
        else
        {
            $this->post_default();
        }
    }
    
    public function post_default()
    {
        if (preg_match('/^StatusCode\:(\d+)$/',$this->response->result,$m))
        {
            $this->response->result = intval($m[1]) == Response::STAT_OK;
            $this->response->statusCode($m[1]);
        }
        else
        {
            $this->response->result = FALSE;
        }
    }
    
    public function post_CheckCredits()
    {   
        if (preg_match('/^Credits:((?:\d|\.)+)$/',$this->response->result,$m))
        {
            $this->response->result = floatval($m[1]);
            $this->response->statusCode(Response::STAT_OK);
        }
        else
        {
            $this->post_default();
        }
    }
    
//    public function post_CheckOriginatorAuthorization()
//    {
//        $this->post_default();
//    }
    
    
    /**
     * If invalid status code given, returns status code as description
     */
    public function post_GetStatusCodeDescription()
    {
        if ($this->request->get('StatusCode') != $this->response->result)
        {
            $this->response->statusCode(Response::STAT_OK);
        }
    }
    
    public function post_InquireDeliveryNotifications()
    {
        $result_str = $this->response->result;
        
        if (preg_match('/^StatusCode\:(\d+)$/',$result_str,$m))
        {
            $this->response->result = FALSE;
            $this->response->statusCode($m[1]);
            return;
        }
        
        if (strlen($result_str) == 0)
        {
            $this->response->result = array();
            return;
        }
        
        // Get keys to be used for status fields
        $keys = $this->request->get('DeliveryStatusFields');
        if (empty($keys))
        {
            $keys = range(1,7);
        }
        $nr = $keys[0];
        
        // Select only last result for each tracking number
        $index_by_nr = $this->request->get('DeliveryStatusSelect') == 'by_nr';
        
        // Create list of results
        $all_list = explode("\n",$result_str);

        $list = array();
        foreach($all_list as $one)
        {
            $tmp = array_combine($keys, explode(';',$one));

            if ($index_by_nr)
            {
                $list[$tmp[$nr]] = $tmp; 
            }
            else
            {
                $list[] = $tmp;
            }
        }
        
        $this->response->result = $list;
    }
    
//    public function post_SendOriginatorUnlockCode() {
//        $this->post_default();
//    }

//    public function post_SendSimpleTextSMS($parameters) {
//        $this->post_default();
//    }
    
//    public function post_SendTextSMS($parameters) {
//        $this->post_default();
//    }
    
//    public function post_SendTokenSMS($parameters) {
//        $this->post_default();
//    }
    
//    public function post_SendUnicodeSMS($parameters) {
//        $this->post_default();
//    }
    
//    public function post_SimpleWAPPush($parameters) {
//        $this->post_default();
//    }
    
//    public function post_UnlockOriginator($parameters) {
//        $this->post_default();
//    }
    
//    public function post_VerifyToken($parameters) {
//        $this->post_default();
//    }
    
    public function post_VersionInfo()
    {
        $result_str = $this->response->result;
        
        if (preg_match('/^StatusCode\:(\d+)$/',$result_str,$m))
        {
            $this->response->result = FALSE;
            $this->response->statusCode($m[1]);
            return;
        }
        
        
        if (preg_match('/^([^ ]+)/',$result_str,$m))
        {
            $v = $m[1];
        }
        else
        {
            $v = '';
        }
        
        if (preg_match('/build:((?:\d|\.)+)/',$result_str,$m))
        {
            $b = $m[1];
        }
        else
        {
            $b = '';
        }
        
        $this->response->result = array(
            'all'      => $result_str,
            'version'   => $v,
            'build'     => $b
        );
    }
}

