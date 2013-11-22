<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../Types.php';

class HttpClient extends AbstractClient
{
    var $method = 'POST';
    
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
        
    }
}