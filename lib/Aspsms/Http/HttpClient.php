<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../AbstractClient.php';

if ( ! function_exists('curl_init'))
{
    throw new AspsmsException('CURL extension required for Aspsms\HttpClient');
}

/**
 * Driver for HTTP based services.
 * 
 * @version 1
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class HttpClient extends AbstractClient
{
    /**
     * Request base url
     * 
     * @var string
     */
    var $baseUrl = 'https://webservice.aspsms.com/aspsmsx2.asmx/';
    
    /**
     * HTTP method to use
     * 
     * @var string
     */
    var $method = 'POST';
    
    /**
     * List of CURL options to use.
     * 
     * @var array
     */
    var $curlOpt = array(
        CURLOPT_USERAGENT       => 'aspsms-php v1 http:1',
        CURLOPT_SSL_VERIFYPEER  => FALSE
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
     * Instantiate and configure HttpClient.
     * Possible options (to be passed in assoc array):
     * 
     *  "method"    optional, default "GET"      "GET" | "POST" 
     *  "baseUrl"   optional, default as defined service base URL to use for requests
     *  "curl"      optional                     associative array with options to pass to CURL
     * 
     * @see HttpClient::$method
     * @see HttpClient::$baseUrl
     * @see HttpClient::$curlOpt
     * 
     * @param array $options Associative array
     * @throws AspsmsException
     */
    public function __construct($options = array()) {
        
        if (isset($options['method']))
        {
            $method = strtoupper($options['method']);
            switch($method)
            {
                case 'GET':
                case 'POST':
                    $this->method = $method;
                    break;
                
                default:
                    throw new AspsmsException('Invalid method type for HttpClient: '.$method);
            }
        }
        
        if (isset($options['baseUrl']))
        {
            $this->baseUrl = $options['baseUrl'];
        }
        
        if (isset($options['curl']))
        {
            foreach($options['curl'] as $k => $v)
            {
                $this->curlOpt[$k] = $v;
            }
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
        $this->response = new Response($request);
        
        // Prepare parameters
        if (isset($cfg['param']))
        {
            $param_pre = $request->extractArray($cfg['param']);
        }
        else
        {
            $param_pre = array();
        }
        
        $param = array();
        foreach($param_pre as $k => $v)
        {
            // @todo key dependent encoding?
            $param[] = urlencode($k) . '=' . urlencode($v);
        }
        $param = implode('&',$param);
        
        // Set Service URL
        $url = $this->baseUrl . $serviceName .  '?';
        
        // Get a copy of curl options
        $curlOpt = $this->curlOpt;
        
        // Adapt settings according to used method
        if ($this->method == 'GET')
        {
             $url .=  $param;
        }
        else
        {
            $curlOpt[CURLOPT_POSTFIELDS] = $param;
        }
        
        
        // attempt to perform request
        $ch = curl_init($url);

        curl_setopt_array($ch, $curlOpt);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // just always set this.
        $result = curl_exec($ch);
        
        if (curl_errno($ch) != 0)
        {
            $errstr = curl_error($ch);
            throw new AspsmsException('CURL request failed: '. $errstr);
        }
        
        curl_close($ch);
        
        if (is_string($result) and preg_match('/<\?xml version="((?:\d|\.)+)" encoding="([a-zA-Z0-9_-]+)"\?>/',$result,$m))    
        {
            $dom = new \DOMDocument($m[1],$m[2]);
            $dom->loadXML($result);
            $this->response->result = $dom->textContent;
        }
        else
        {
            throw new AspsmsException('Invalid non-XML response given.');
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
            $this->response->result = intval($m[1]) == Response::STAT_OK;
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
            $this->response->statusCode(Response::STAT_OK);
        }
        else
        {
            $this->post_default();
        }
    }
    
    
    /**
     * Post-Processing for GetStatusCodeDescription
     * 
     * If invalid status code given, returns status code as description
     */
    public function post_GetStatusCodeDescription()
    {
        if ($this->request->get('StatusCode') != $this->response->result)
        {
            $this->response->statusCode(Response::STAT_OK);
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