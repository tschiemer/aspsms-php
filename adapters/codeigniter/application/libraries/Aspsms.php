<?php

require_once APPPATH . 'third_party/Aspsms/SimpleClient.php';


/**
 * Adapter interface of aspsms-php for CodeIgniter
 * 
 * @version 1
 * @package aspsms.adapter.codeigniter
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class Aspsms extends Aspsms\SimpleClient
{
    /**
     * @var CI_Controller
     */
    var $CI;
    
    public function __construct($config = array())
    {
        $this->CI = get_instance();
        
        $config_base = $this->CI->load->config('aspsms',TRUE);
        
        // overwrite base configuration with any config values passed
        foreach($config as $k => $v)
        {
            $config_base[$k] = $v;
        }
        
        // initialize as usual
        parent::__construct($config_base);
    }
    
    
    /**
     * Request: Get description to given status code.
     * 
     * @param string|int $statusCode
     * @param boolean $force_query Do not rely on database of known status codes, force query.
     * @return string
     */
    public function getStatusDescription($statusCode, $force_query = FALSE)
    {   
        $param = array(
            'RequestName' => 'getStatusCodeDescription',
            'StatusCode'  => $statusCode,
            'Userkey'     => $this->userkey,
            'Password'    => $this->password
        );
        
        if ( ! $force_query)
        {
            $this->CI->load->language('aspsms','english');
            $status = $this->CI->lang('reason_'.int($statusCode));
            if ($status !== NULL)
            {
                // Simulate actual request in case any other request/response
                // processing would take place.
                $this->lastRequest = new Aspsms\Request($param);
                
                $this->lastResponse = new \Aspsms\Response($this->lastRequest);
                $this->lastResponse->statusCode(\Aspsms\Response::STAT_OK);
                $this->lastResponse->result = $status;
                
                return $this->lastResponse->result();
            }
        }
        
        
        return $this->send($param);
    }
}
