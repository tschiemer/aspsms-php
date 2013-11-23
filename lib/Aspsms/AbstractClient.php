<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/Response.php';

/**
 * Interface for service drivers, can be used as standalone components.
 * 
 * @version 1
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
abstract class AbstractClient
{
    /**
     * List of satsfiable requests
     * 
     * @var string[]
     * @access protected
     * @see canPerform()
     */
    var $requests = array(
        'getVersion'                => NULL,
        'getCredits'                => NULL,
        'getStatusCodeDescription'  => NULL,
        
        'sendText'                  => NULL,
        'sendWapPush'               => NULL,
        
        'sendToken'                 => NULL,
        'verifyToken'               => NULL,
        
        'getDeliveryStatus'         => NULL,
        
        'checkOriginator'           => NULL,
        'sendOriginatorCode'        => NULL,
        'unlockOriginator'          => NULL,
        
        'sendPicture'               => NULL,
        'sendLogo'                  => NULL,
        'sendGroupLogo'             => NULL,
        'sendRingtone'              => NULL,
        'sendVCard'                 => NULL,
        'sendBinaryData'            => NULL
    );

    
    /**
     *
     * @var \Aspsms\Request
     */
    var $request = NULL;
    
    /**
     *
     * @var \Aspsms\Response
     */
    var $response = NULL;
    
//    public function __construct($options=array());
    
    /**
     * Can satisfy/perform the given request?
     * 
     * @param string|\Aspsms\Request $requestName
     * @return boolean
     */
    public function canProcess($request)
    {
        if ($request instanceof Request)
        {
            $request = $request->getRequestName();
        }
        return array_key_exists($request, $this->requests);
    }
    
    /**
     * @param array $request 
     * @return array
     * @throws AspsmsException
     */
    abstract public function send($request);
    
    /**
     * @return \Aspsms\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Reset internal request and response
     */
    public function clear()
    {
        $this->request = NULL;
        $this->response = NULL;
    }
}


class AspsmsException extends \Exception {}