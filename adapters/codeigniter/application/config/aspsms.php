<?php

/**
 * Config file
 * 
 * @version 1
 * @package aspsms.adapter.codeigniter
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt 
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */

$config['soap']['wsdl'] = APPPATH . 'third_party/Aspsms/Soap/v2/aspsms-wsdl.xml';
//$config['soap']['wsdl'] = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL';

$config['soap']['options'] = array();

$config['userkey']    = '';
$config['password']   = '';
        
$config['defaultType'] = array(
    'text'  => 'SendUnicodeSMS',
    'wap'   => 'SimpleWAPPush',
    'token' => 'SendTokenSMS'
);

$config['url'] = array(
    'success'   => '',
    'error'     => '',
    'buffered'  => ''
);
        
