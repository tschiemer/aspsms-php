aspsms-php
==========

Service clients for sending SMS through aspsms.com. CodeIgniter Adapter/demo included.


Requirements
----------

 - PHP 5.3
 - CURL Extension (iff using HTTP or XML driver)
 - SOAP Extension (iff using SOAP driver)




Todos
----------

 - XML driver: alternating servers on failure
 - XML services: sendPicture, sendLogo, sendGroupLogo, sendRingtone, sendBinaryData
 - Error/Exception handling, in particular: differentiate between service fail and negative reply



Sample use
----------
Please refer to CodeIgniter Adapter for a more extended demo.


    require 'lib/Aspsms/SimpleClient.php';
    require 'lib/Aspsms/Helpers.php'; // contains only status code to description mappings
    
    $config = array(
        'userkey'       => 'my-key',
        'password'      => 'my-password',
        'originator'    => 'my-originator'
    );

    $aspsms = new \Aspsms\SimpleClient($config);

    // Send trackable SMS
    try {
        $success = $aspsms->sendText(array(
            '100' => '0041xxxxxxxx',
            '101' => '0031xxxxxxxx'
            ), 'Hello pretty!');
    }
    catch (\Aspsms\AspsmsException $e){
        die('ASPSMS service error: '.$e->getMessage());
    }

    if ( ! $success )
    {
        $statusCode = $aspsms->getLastStatusCode();

        $statusDescription = $aspsms->getStatusDescription($statusCode);
        // or alternatively
        $statusDescription = statusDescriptionString($statusCode);

        die('Unexpected server response status '.$statusCode. ': '.$statusDescription);
    }


    // Get delivery information
    try {
        $status = $aspsms->getDeliveryStatus(array('100','101'));
    }
    catch (\Aspsms\AspsmsException $e){
        die('ASPSMS service error: '.$e->getMessage());
    }

    print_r($status);
    
    echo deliveryStatusString($status['100']['status']);

    echo reasonCodeString($status['100']['reason']);

    

Outputs

    
    array(2) {
      [100]=>
      array(6) {
        [nr]=>"100",
        [status]=>"0",
        [submissionDate]=>"12022014123834",
        [deliveryDate]=>"12022014123840",
        [reason]=>"000",
        [other]=>""
      },
      [101]=>
      array(6) {
        [nr]=>"101",
        [status]=>"0",
        [submissionDate]=>"12022014123833",
        [deliveryDate]=>"12022014123838",
        [reason]=>"000",
        [other]=>""
      }
    }
    Delivered
    Unknown subscriber
    
    
