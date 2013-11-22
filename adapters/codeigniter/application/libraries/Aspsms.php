<?php

require_once APPPATH . 'third_party/Aspsms/Soap/v2/SimpleClient.php';

class Aspsms extends Aspsms\Soap\v2\SimpleClient
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
