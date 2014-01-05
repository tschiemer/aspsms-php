Readme for aspsms-soap codeigniter adapter
==========================================

 1. Place adapter files according to their location (application/language is optional) 
 2. Place /lib/Aspsms folder as is in codeigniter/application/third_party
 3. Configure `application/config/aspsms.php` with your aspsms.com credentials.


*** Using language file

The English language file contains the official localization for the delivery statuses
as well as delivery success/fail reason codes. They are easily accessible as follows:


    // local for delivery status -1
    $lang['delivery_-1'] = 'Not yet submitted or rejected';
    
    // local for delivery fail reason 0
    $lang['reason_0'] = 'Unknown subscriber';


