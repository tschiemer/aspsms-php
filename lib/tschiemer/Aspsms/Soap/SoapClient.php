<?php

namespace tschiemer\Aspsms\Soap;
use \tschiemer\Aspsms as Aspsms;

if ( ! class_exists('\SoapClient'))
{
    throw new \Exception('SOAP extension required for Aspsms\SoapClient');
}

/**
 * SOAP driver / interface.
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class SoapClient extends Aspsms\AbstractClient
{
    /**
     * Default WSDL source
     * 
     * @var string
     */
    var $wsdl = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL';
    
    /**
     * @var \SoapClient
     */
    var $soap;
    
    /**
     * List or default SOAP options
     * 
     * @var array
     */
    var $soapOpt = array(
//        'cache_wsdl'    => WSDL_CACHE_NONE,
        'user_agent'    => 'aspsms-php v1 soap:1'
    );
    
    /**
     * Request configuration
     * 
     * Foreach request:
     *      'service' := actual service name to use
     *      'param'   := list of fields and default settings to use
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
     * Constructor, initialize and configure SoapClient
     * Possible options (to be passed in assoc array):
     * 
     *  "wsdl"  optional    WSDL resource to rely upon
     *  "soap"  optional    SOAP options
     * 
     * @see SoapClient::$wsdl
     * @see SoapClient::$soapOpt
     * 
     * @param string $wsdl
     * @param array $options
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
            $wsdl = $this->wsdl;
        }
        
        $soapOpt = $this->soapOpt;
        
        if (isset($options['soap']))
        {
            array_merge($soapOpt,$options['soap']);
        }
        
        try {
            $this->soap = @new \SoapClient($wsdl, $soapOpt);
        }
        catch (\SoapFault $e) {
            $this->soap = NULL;
            throw new Aspsms\ServiceException('Could not retrieve WSDL or is invalid:'.$e->getMessage());
        }
    }
    
    /**
     * Send given request.
     * 
     * @param \Aspsms\Request $request
     * @throws AspsmsException
     * @see AbstractClient::getResponse()
     */
    public function send($request)
    {
        // Set internal request
        $this->request = $request;
        
        // friendly shortcut
        $requestName = $request->getRequestName();
        
        
        $cfg =& $this->requests[$requestName];
        
        // friendly shortcut
        $serviceName = $cfg['service'];
        
        // Initialize new response object
        $this->response = new Aspsms\Response($request);
        
        // Prepare parameters
        if (isset($cfg['param']))
        {
            $param = $request->extractObject($cfg['param']);
        }
        else
        {
            $param = NULL;
        }
            
        
        // attempt to perform request
        try {
            
            // returns stdClass
            $soapResponse = $this->soap->$serviceName($param);
            
            // get result field (name depends on service name (for some wierd reason)
            $this->response->result = $soapResponse->{$serviceName.'Result'};
            
        }
        catch (\SoapFault $e)
        {   
            throw new AspsmsServiceExceptionn('SoapFault: '.$e->getMessage());
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
    
    
    /**
     * Default Post-Processor (applied if no specific PP to be used)
     */
    public function post_default()
    {
        if (preg_match('/^StatusCode\:(\d+)$/',$this->response->result,$m))
        {
            $this->response->result = intval($m[1]) == Aspsms\Response::STAT_OK;
            $this->response->statusCode($m[1]);
        }
        else
        {
            $this->response->result = FALSE;
        }
    }
    
    /**
     * Post-Processing for CheckCredits
     */
    public function post_CheckCredits()
    {   
        if (preg_match('/^Credits:((?:\d|\.)+)$/',$this->response->result,$m))
        {
            $this->response->result = floatval($m[1]);
            $this->response->statusCode(Aspsms\Response::STAT_OK);
        }
        else
        {
            $this->post_default();
        }
    }
    
    
    /**
     * If invalid status code given, returns status code as description
     */
    public function post_GetStatusCodeDescription()
    {
        if ($this->request->get('StatusCode') != $this->response->result)
        {
            $this->response->statusCode(Aspsms\Response::STAT_OK);
        }
    }
    
    public function post_CheckOriginatorAuthorization()
    {
        $result_str = $this->response->result;
        
        switch($result_str)
        {
            case 'StatusCode:31':
                $this->response->result = TRUE;
                $this->response->statusCode(31);
                break;
            case 'StatusCode:30':
                $this->response->result = FALSE;
                $this->response->statusCode(30);
                break;
            default:
                $this->response->result = NULL;
                if (preg_match('/^StatusCode\:(\d+)$/',$result_str,$m))
                {
                    $this->response->statusCode($m[1]);
                }
                else
                {
                    $this->response->statusCode(0);
                }
        }
    }
    
    /**
     * Post-Processing for InquireDeliveryNotifications
     */
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
            $keys = range(0,6);
        }
        $nr = $keys[0];
        
        // Select only last result for each tracking number
        $index_by_nr = (boolean)$this->request->get('DeliveryStatusIndexing');
        
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
    
    /**
     * Post-Processing for VersionInfo
     * 
     * Creates an associative array with complete response, service version and build number.
     */
    public function post_VersionInfo()
    {
        $result_str = $this->response->result;
        
        if (preg_match('/^StatusCode\:(\d+)$/',$result_str,$m))
        {
            $this->response->result = FALSE;
            $this->response->statusCode($m[1]);
            return;
        }
        
        // api and version is found at beginning of response
        if (preg_match('/^([^ ]+)/',$result_str,$m))
        {
            $v = $m[1];
        }
        else
        {
            $v = '';
        }
        
        // build number follows 'build:'
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

