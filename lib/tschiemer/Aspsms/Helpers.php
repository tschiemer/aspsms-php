<?php

namespace tschiemer\Aspsms;

/**
 * Text helper functions
 * 
 * @version 1.1.0
 * @package aspsms
 * @license LGPL v3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright 2013 Philip Tschiemer, <tschiemer@filou.se>
 * @link https://github.com/tschiemer/aspsms-php
 */
class Strings {
 
    /**
     * Webservice statuscode strings
     * 
     * @param int|string $code
     * @return string|null
     */   
    public static function statusDescription($code)
    {
        switch(intval($code))
        {
            case 1: return 'OK';
            case 2: return 'Connect failed.';
            case 3: return 'Authorization failed.';
            case 4: return 'Binary file not found. Please check the location.';
            case 5: return 'Not enough credits available. Please recharge your account to proceed.';
            case 6: return 'Time out error.';
            case 7: return 'Transmission error. Please try it again.';
            case 8: return 'Invalid UserKey. Please check the spelling of the UserKey.';
            case 9: return 'Invalid Password.';
            case 10: return 'Invalid originator. A maximum of 11 characters is allowed for alphanumeric originators.';
            case 11: return 'Invalid message date. Please verify the data.';
            case 12: return 'Invalid binary data. Please verify the data.';
            case 13: return 'Invalid binary file. Please check the file type.';
            case 14: return 'Invalid MCC. Please check the number.';
            case 15: return 'Invalid MNC. Please check the number.';
            case 16: return 'Invalid XSer.';
            case 17: return 'Invalid URL buffered message notification string.';
            case 18: return 'Invalid URL delivery notification string.';
            case 19: return 'Invalid URL non delivery notification string.';
            case 20: return 'Missing a recipient. Please specify at least one recipient.';
            case 21: return 'Missing binary data. Please specify some data.';
            case 22: return 'Invalid deferred delivery time. Please check the format.';
            case 23: return 'Missing transaction reference number.';
            case 24: return 'Service temporarely not available.';
            case 25: return 'User access denied.';
            case 28: return 'No Originator Restrictions.';
            case 29: return 'Originator Authorization Pending.';
            case 30: return 'Originator Not Authorized.';
            case 31: return 'Originator already authorized';
            case 32: return 'No Notification Recipient Restrictions.';
            case 33: return 'Notification Recipient Authorization Pending.';
            case 34: return 'Notification Recipient Not Authorized.';
            case 35: return 'Notification Recipient Already Authorized.';
            case 36: return 'Security Token authorization pending';
            case 37: return 'Security Token not authorized';
            case 38: return 'Security Token not found';
            case 39: return 'Security Token already authorized';
                
            default: return NULL;
        }
    }
    
    
    /**
     * Code to description map of delivery statuses
     * 
     * @param int|string $code
     * @return string|null
     */
    public static function deliveryStatus($code)
    {
        switch (intval($code))
        {
            case -1 : return 'Not yet submitted or rejected';
            case 0  : return 'Delivered';
            case 1  : return 'Buffered';
            case 2  : return 'Not delivered';
                
            default: return NULL;
        }
    }
    
    /**
     * Code to description map of tracking reason codes
     * 
     * @see AbstractClient::getDeliveryStatus()
     * @param int|string $code
     * @return string|null
     */
    public static function reasonCode($code)
    {
        switch (intval($code))
        {
            case 0: return 'Unknown subscriber';
            case 1: 
            case 2: 
            case 3: 
            case 4: 
            case 5: 
            case 6: 
            case 7: 
            case 8: return 'Service temporary not available';
            case 9: return 'Illegal error code';
            case 10: return 'Network timeout';
            case 30: return 'Originator not authorized';
            case 31: return 'Originator already authorized';
            case 100: return 'Facility not supported';
            case 101: return 'Unknown subscriber';
            case 102: return 'Facility not provided ';
            case 103: return 'Call barred';
            case 104: return 'Operation barred';
            case 105: return 'SC congestion ';
            case 106: return 'Facility not supported ';
            case 107: return 'Absent subscriber';
            case 108: return 'Delivery fail';
            case 109: return 'SC congestion ';
            case 110: return 'Protocol error';
            case 111: return 'MS not equipped';
            case 112: return 'Unknown SC';
            case 113: return 'SC congestion';
            case 114: return 'Illegal MS';
            case 115: return 'MS not a subscriber';
            case 116: return 'Error in MS';
            case 117: return 'SMS lower layer not provisioned';
            case 118: return 'System fail';
            case 119: return 'PLMN system failure ';
            case 120: return 'HLR system failure';
            case 121: return 'VLR system failure';
            case 122: return 'Previous VLR system failure';
            case 123: return 'Controlling MSC system failure';
            case 124: return 'VMSC system failure';
            case 125: return 'EIR system failure';
            case 126: return 'System failure';
            case 127: return 'Unexpected data value';
            case 200: return 'Error in address service centre';
            case 201: return 'Invalid absolute Validity Period';
            case 202: return 'Short message exceeds maximum';
            case 203: return 'Unable to Unpack GSM message';
            case 204: return 'Unable to convert to IA5 ALPHABET';
            case 205: return 'Invalid validity period format';
            case 206: return 'Invalid destination address';
            case 207: return 'Duplicate message submit';
            case 208: return 'Invalid message type indicator';

            default: return NULL;
        }
    }
}
