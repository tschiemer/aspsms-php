    [0] => struct SimpleWAPPush {
 string UserKey;
 string Password;
 string Recipients;
 string Originator;
 string WapDescription;
 string WapURL;
 string DeferredDeliveryTime;
 string FlashingSMS;
 string TimeZone;
 string URLBufferedMessageNotification;
 string URLDeliveryNotification;
 string URLNonDeliveryNotification;
 string AffiliateId;
}
    [1] => struct SimpleWAPPushResponse {
 string SimpleWAPPushResult;
}
    [2] => struct SendSimpleTextSMS {
 string UserKey;
 string Password;
 string Recipients;
 string Originator;
 string MessageText;
}
    [3] => struct SendSimpleTextSMSResponse {
 string SendSimpleTextSMSResult;
}
    [4] => struct SendTextSMS {
 string UserKey;
 string Password;
 string Recipients;
 string Originator;
 string MessageText;
 string DeferredDeliveryTime;
 string FlashingSMS;
 string TimeZone;
 string URLBufferedMessageNotification;
 string URLDeliveryNotification;
 string URLNonDeliveryNotification;
 string AffiliateId;
}
    [5] => struct SendTextSMSResponse {
 string SendTextSMSResult;
}
    [6] => struct SendUnicodeSMS {
 string UserKey;
 string Password;
 string Recipients;
 string Originator;
 string MessageText;
 string DeferredDeliveryTime;
 string FlashingSMS;
 string TimeZone;
 string URLBufferedMessageNotification;
 string URLDeliveryNotification;
 string URLNonDeliveryNotification;
 string AffiliateId;
}
    [7] => struct SendUnicodeSMSResponse {
 string SendUnicodeSMSResult;
}



    [8] => struct CheckCredits {
 string UserKey;
 string Password;
}
    [9] => struct CheckCreditsResponse {
 string CheckCreditsResult;
}
    [10] => struct SendOriginatorUnlockCode {
 string UserKey;
 string Password;
 string Originator;
}
    [11] => struct SendOriginatorUnlockCodeResponse {
 string SendOriginatorUnlockCodeResult;
}
    [12] => struct UnlockOriginator {
 string UserKey;
 string Password;
 string Originator;
 string OriginatorUnlockCode;
 string AffiliateId;
}
    [13] => struct UnlockOriginatorResponse {
 string UnlockOriginatorResult;
}
    [14] => struct CheckOriginatorAuthorization {
 string UserKey;
 string Password;
 string Originator;
}
    [15] => struct CheckOriginatorAuthorizationResponse {
 string CheckOriginatorAuthorizationResult;
}
    [16] => struct VerifyToken {
 string UserKey;
 string Password;
 string PhoneNumber;
 string TokenReference;
 string VerificationCode;
}
    [17] => struct VerifyTokenResponse {
 string VerifyTokenResult;
}
    [18] => struct SendTokenSMS {
 string UserKey;
 string Password;
 string Originator;
 string Recipients;
 string MessageData;
 string TokenReference;
 string TokenValidity;
 string TokenMask;
 string VerificationCode;
 string TokenCaseSensitive;
 string URLBufferedMessageNotification;
 string URLDeliveryNotification;
 string URLNonDeliveryNotification;
 string AffiliateId;
}
    [19] => struct SendTokenSMSResponse {
 string SendTokenSMSResult;
}
    [20] => struct InquireDeliveryNotifications {
 string UserKey;
 string Password;
 string TransactionReferenceNumbers;
}
    [21] => struct InquireDeliveryNotificationsResponse {
 string InquireDeliveryNotificationsResult;
}
    [22] => struct GetStatusCodeDescription {
 string StatusCode;
}
    [23] => struct GetStatusCodeDescriptionResponse {
 string GetStatusCodeDescriptionResult;
}
    [24] => struct VersionInfo {
}
    [25] => struct VersionInfoResponse {
 string VersionInfoResult;
}
)
Array
(
    [0] => SimpleWAPPushResponse SimpleWAPPush(SimpleWAPPush $parameters)
    [1] => SendSimpleTextSMSResponse SendSimpleTextSMS(SendSimpleTextSMS $parameters)
    [2] => SendTextSMSResponse SendTextSMS(SendTextSMS $parameters)
    [3] => SendUnicodeSMSResponse SendUnicodeSMS(SendUnicodeSMS $parameters)
    [4] => CheckCreditsResponse CheckCredits(CheckCredits $parameters)
    [5] => SendOriginatorUnlockCodeResponse SendOriginatorUnlockCode(SendOriginatorUnlockCode $parameters)
    [6] => UnlockOriginatorResponse UnlockOriginator(UnlockOriginator $parameters)
    [7] => CheckOriginatorAuthorizationResponse CheckOriginatorAuthorization(CheckOriginatorAuthorization $parameters)
    [8] => VerifyTokenResponse VerifyToken(VerifyToken $parameters)
    [9] => SendTokenSMSResponse SendTokenSMS(SendTokenSMS $parameters)
    [10] => InquireDeliveryNotificationsResponse InquireDeliveryNotifications(InquireDeliveryNotifications $parameters)
    [11] => GetStatusCodeDescriptionResponse GetStatusCodeDescription(GetStatusCodeDescription $parameters)
    [12] => VersionInfoResponse VersionInfo(VersionInfo $parameters)
    [13] => SimpleWAPPushResponse SimpleWAPPush(SimpleWAPPush $parameters)
    [14] => SendSimpleTextSMSResponse SendSimpleTextSMS(SendSimpleTextSMS $parameters)
    [15] => SendTextSMSResponse SendTextSMS(SendTextSMS $parameters)
    [16] => SendUnicodeSMSResponse SendUnicodeSMS(SendUnicodeSMS $parameters)
    [17] => CheckCreditsResponse CheckCredits(CheckCredits $parameters)
    [18] => SendOriginatorUnlockCodeResponse SendOriginatorUnlockCode(SendOriginatorUnlockCode $parameters)
    [19] => UnlockOriginatorResponse UnlockOriginator(UnlockOriginator $parameters)
    [20] => CheckOriginatorAuthorizationResponse CheckOriginatorAuthorization(CheckOriginatorAuthorization $parameters)
    [21] => VerifyTokenResponse VerifyToken(VerifyToken $parameters)
    [22] => SendTokenSMSResponse SendTokenSMS(SendTokenSMS $parameters)
    [23] => InquireDeliveryNotificationsResponse InquireDeliveryNotifications(InquireDeliveryNotifications $parameters)
    [24] => GetStatusCodeDescriptionResponse GetStatusCodeDescription(GetStatusCodeDescription $parameters)
    [25] => VersionInfoResponse VersionInfo(VersionInfo $parameters)
)
