<?php

namespace Aspsms;

require_once dirname(__FILE__) . '/../Types.php';

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