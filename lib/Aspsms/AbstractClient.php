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
        'getVersion',
        'getCredits',
        'getStatusCodeDescription',
        
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

    
    /**
     *
     * @var Request
     */
    var $request = NULL;
    
    /**
     *
     * @var Response
     */
    var $response = NULL;
    
//    public function __construct($options=array());
    
    /**
     * Can satisfy/perform the given request?
     * 
     * @param string $requestName
     * @return boolean
     */
    public function canProcess($request)
    {
        if ($request instanceof Request)
        {
            $request = $request->getRequestName();
        }
        return in_array($request,$this->requests);
    }
    
    /**
     * @param array $request 
     * @return array
     * @throws AspsmsException
     */
    abstract public function send($request);
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function clear()
    {
        $this->request = NULL;
        $this->response = NULL;
    }
}


class AspsmsException extends \Exception {}