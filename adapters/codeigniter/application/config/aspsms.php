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


/************************************************************
 * General setup
 */


/**
 * User credentials
 */
$config['userkey']    = '';
$config['password']   = '';

/**
 * Originator
 * 
 * As to appear as sender of message.
 * 
 * NOTE: if using a numeric sender, you must first unlock the originator using the given methods.
 */
$config['originator'] = '';


/**
 * Callback URLS
 * 
 *  1. simple:
 *      SMS.URLNonDeliveryNotification = "http://www.mysite.com/sms/notdelivered.asp?ID="
 *      When the TransactionReferenceNumber is e.g. 3152, the URL will be loaded like this: 
 *      http://www.mysite.com/sms/notdelivered.asp?ID=3152
 * 
 *  2. detailed:
 *      http://www.yourhost.com/Delivered.asp?SCTS=<SCTS>&DSCTS=<DSCTS>&RSN=<RSN>&DST=<DST>&TRN=<TRN>
 * 
 *      <RCPNT> (Recipient, Mobilenumber)
 *      <SCTS> (Servicecenter Timestamp, Submissiondate)
 *      <DSCTS> (Delivery Servicecenter Timestamp, Notificationdate)
 *      <RSN> (Reasoncode)
 *      <DST> (Deliverystatus)
 *      <TRN> (Transactionreferencenummer)
 * 
 */
//$config['url'] = array(
//    'success'   => '',
//    'error'     => '',
//    'buffered'  => ''
//);




/************************************************************
 * SimpleClient setup
 */


/**
 * Mapping of request to preferred driver
 * 
 * @var array
 */
//$config['requestMap'] = array(
//    'getVersion'                => 'soap',  // Soap|Http
//    'getCredits'                => 'xml',   // Xml|Soap|Http
//    'getStatusCodeDescription'  => 'soap',  // Soap|Http
//
//    'checkOriginator'           => 'xml',   // Xml|Soap|Http
//    'sendOriginatorCode'        => 'xml',   // Xml|Soap|Http
//    'unlockOriginator'          => 'soap',  // Xml|Soap|Http
//
//    'sendText'                  => 'xml',   // Xml|Soap|Http
//    'sendWapPush'               => 'soap',  // Xml|Soap|Http
//    'sendToken'                 => 'soap',  // Soap|Http
//    'verifyToken'               => 'soap',   // Soap|Http
//    'sendPicture'               => 'xml',   // Xml
//    'sendLogo'                  => 'xml',   // Xml
//    'sendGroupLogo'             => 'xml',   // Xml
//    'sendRingtone'              => 'xml',   // Xml
//    'sendVCard'                 => 'xml',   // Xml
//    'sendBinaryData'            => 'xml',   // Xml
//    'getDeliveryStatus'         => 'soap',  // Xml|Soap|Http
//);




/************************************************************
 * Driver Setup: SOAP
 */


/**
 * WSDL URL
 * 
 * @var string
 */
//$config['soapclient']['wsdl'] = 'https://webservice.aspsms.com/aspsmsx2.asmx?WSDL';


/**
 * Additional SoapClient options for driver to use.
 * @var array
 */
//$config['soapclient']['soap'] = array(
//    'cache_wsdl'    => WSDL_CACHE_NONE, // or whatever soap option of your choice
//);




/************************************************************
 * Driver Setup: HTTP
 */


/**
 * HTTP method to use.
 * 
 * @var string 'GET' | 'POST'
 */
//$config['httpclient']['method'] = 'GET';


/**
 * Base url of HTTP requests
 * 
 * @var string
 */
//$config['httpclient']['baseUrl'] = 'https://webservice.aspsms.com/aspsmsx2.asmx/';


/**
 * Additional CURL options for driver
 * 
 * @var array
 */
//$config['httpclient']['curl'] = array(
//    CURLOPT_SSL_VERIFYPEER  => FALSE // or whatever curl options of your choice
//);
  
