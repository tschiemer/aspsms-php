<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../AbstractSimpleClient.php';
require_once dirname(__FILE__) . '/SoapClient.php';

class SoapSimpleClient extends AbstractSimpleClient
{
    /**
     * @var SoapClient
     */
    var $driver;
    
    public function __construct($options = array()) {
        parent::__construct($options);
        
        $this->driver = new SoapClient();
    }
    
    public function driver(&$request) {
        return $this->driver;
    }
    
    
    /**
     * 
     * For more details you can also access the last response and versin/build methods.
     * 
     * @see VersionInfoResponse lastResponse()
     * @return string
     */
    public function getVersion()
    {
        return $this->send(array(
            'RequestName' => 'getVersion'
        ));
    }
    
    
    /**
     * Request: Get description to given status code.
     * 
     * @param string|int $statusCode
     * @return string
     */
    public function getStatusDescription($statusCode)
    {   
        return $this->send(array(
            'RequestName' => 'getStatusCodeDescription',
            'StatusCode' => $statusCode
        ));
    }
}