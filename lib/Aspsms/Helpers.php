<?php

namespace Aspsms;

if ( ! function_exists('deliveryStatus'))
{
    function deliveryStatus($code)
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
}

if ( ! function_exists('reasonCode'))
{
    function reasonCode($code)
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
