aspsms-php
==========

Service clients for sending SMS through aspsms.com. CodeIgniter Adapter/demo included.

Features
----------
 - Interface abstractions for SOAP, HTTP and XML aspsms.com services
 - Simple Interface for the lazy (hurray!), either for single service or composite.
 - Library adapter for CodeIgniter (with extensive demo)
 - Clear-ish documentation of simple interfaces

Available aspsms.com Functionality
----------

 - Sending of Text SMS
 - Retrieval of delivery status
 - Originator (Sender) testing and unlocking
 - Token sending and validation (also see http://www.asptoken.com)
 - Binary SMS: VCard

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
 - Understand:
    Short Message Service Centre 4.0 EMI - UCP Interface Specification
    Short Message Service Centre (SMSC) External Machine Interface (EMI) Description, EmiSpec_44a.pdf


Documentation
----------
[PHP Apigen Documentation](http://tschiemer.github.io/aspsms-php)


Sample use
----------
Please refer to CodeIgniter Adapter for a more extended demo.

    use \tschiemer\Aspsms as Aspsms;

    require_once 'lib/tschiemer/autoload.php';
    
    $config = array(
        'userkey'       => 'my-key',
        'password'      => 'my-password',
        'originator'    => 'my-originator'
    );

    $aspsms = new Aspsms\SimpleClient($config);

    // Send trackable SMS
    try {
        $success = $aspsms->sendText(array(
            '100' => '0041xxxxxxxx',
            '101' => '0031xxxxxxxx'
            ), 'Hello pretty!');
    }
    catch (Aspsms\ServiceException $e){
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
    catch (Aspsms\ServiceException $e){
        die('ASPSMS service error: '.$e->getMessage());
    }

    print_r($status);
    
    echo Aspsms\Strings::deliveryStatus($status['100']['status']);

    echo Aspsms\Strings::reasonCode($status['100']['reason']);

    

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
    
    
Revision History
----------
1.1.0
- PSR-0 namespace compatibility and composer/packagist registration
- minor filename/class renaming


Official Documentation
----------
https://webservice.aspsms.com/aspsmsx2.asmx
http://www.aspsms.com/xml/doc/xmlsvr191.pdf
http://www.aspsms.de/vbscript_class/doc.htm
http://www.aspsms.de/newsletter/html/en/200905/



License: LGPL v3
----------
