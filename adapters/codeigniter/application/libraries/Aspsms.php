<?php

require_once APPPATH . 'third_party/Aspsms/Soap/v2/SimpleClient.php';


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
        
        foreach($config as $k => $v)
        {
            /**
             * @todo..
             */
        }
    }
}
