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
 - Error handling, in particular: differentiate between service fail and negative reply
 - CI Adapter: complete Tracking example
 