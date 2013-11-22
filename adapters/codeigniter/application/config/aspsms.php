<?php

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
        
