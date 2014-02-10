aspsms-php
==========

Service clients for sending SMS through aspsms.com. CodeIgniter Adapter/demo included.


Requirements
----------

 - PHP 5.3
 - CURL Extension (iff using HTTP or XML driver)
 - SOAP Extension (iff using SOAP driver)



WORK IN PROGRESS

Todos
----------

 - XML driver: alternating servers on failure
 - XML services: sendPicture, sendLogo, sendGroupLogo, sendRingtone, sendBinaryData
 - Error/Exception handling, in particular: differentiate between service fail and negative reply
 - CI Adapter: complete Tracking example


Sample use
----------
Please refer to CodeIgniter Adapter for a more extended demo.


   
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
    


