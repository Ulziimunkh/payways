<?php
  echo '<textarea rows="10" cols="140">';      
$xmlmsg = '<?xml version="1.0" encoding="UTF-8"?>
<Message date="08/09/2016 06:47:20"><Version>1.0</Version><OrderID>ABCD:123456</OrderID><TransactionType>Purchase</TransactionType><PAN>949612XXXX4486</PAN><PurchaseAmount>100</PurchaseAmount><Currency>840</Currency><TranDateTime>08/09/2016 06:47:20</TranDateTime><ResponseCode>001</ResponseCode><Brand>VISA</Brand><OrderStatus>APPROVED</OrderStatus><ApprovalCode>539152 A</ApprovalCode><AcqFee>0</AcqFee><MerchantTranID>3134373332383438323939363430303030303030</MerchantTranID><OrderDescription>TEST12 (ABCD:123456)</OrderDescription><ApprovalCodeScr>539152</ApprovalCodeScr><PurchaseAmountScr>1.00</PurchaseAmountScr><CurrencyScr>USD</CurrencyScr><OrderStatusScr>APPROVED</OrderStatusScr><ShopOrderId>ABCD:123456</ShopOrderId><ThreeDSVerificaion>Y</ThreeDSVerificaion><ThreeDSStatus>Approved</ThreeDSStatus><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/><Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>MXNPEaS8f7+0pSjghcXbksYSKwg=</DigestValue></Reference></SignedInfo><SignatureValue>g1+4I5jTQWMUezyATWqUkdE6el4ewrx4WuRsDhXzlEM2hvZR3UoXBPCICfpyJOdlmvu9c3KIV3Pps59vajMCimCQpxm3r6+TOqTUNrWGrYeyZr1U5ayK0nNXQZ17c/+bbRwaL5r0i725R7bu5vUW+ZPrlfOkbvMXkJ5YWg5yVSzeuux9ih3JpYcKCsZA90oEjYNqcH0LnzEy/BC04p8HMMY1vrISXfHcy4ZmpLPhPMz5uf6rG+o4bIbhhWyDIiHVcaT1ejLCg1NKC/BDK+u2te9XhBSxXp6Rr59Jhu2gIh/JOEjBzaTGrNdW7P4CRUFxpRTPeOreTLfPryXV+dReuA==</SignatureValue><KeyInfo><KeyName>Public key of certificate</KeyName><KeyValue><RSAKeyValue><Modulus>pCJRXW/GSpIR5JjtHoLWZYcgjwBQCe5i1T96brGfAcGZkqKNaaUCwa1ABvKQXRzlztTXzMMI2r7GSciwuToo2txb4ALFOyA7TGkY8yrKLkH6TOXvzsmqLUrRVSSHMYSppF85bEiuH+Z293uipF24lLZIdl7s/zh6HzlRGwniDjDQrE014vfMVKBUQ9ZNQ0IIzI+OKoz7pBbphgVMkQXMdrTVh8498WxArcZErkDDuScDPM8hlY4PNZ9fMUpQ1duZuTnXVlPhprzqQyhkGxU4iywZEhhCy9Swo7h5SqGM3V+geCMDrOW7sPwmPInGDjXcTgKdvUdDxEKo1DZxqTNT6Q==</Modulus><Exponent>AQAB</Exponent></RSAKeyValue></KeyValue><X509Data><X509Certificate>MIIDmzCCAoOgAwIBAgIEKT3u2zANBgkqhkiG9w0BAQsFADB+MQswCQYDVQQGEwJNTjELMAkGA1UECBMCVUIxFDASBgNVBAcTC1VsYWFuYmFhdGFyMSMwIQYDVQQKExpUcmFkZSBhbmQgRGV2ZWxvcG1lbnQgQmFuazEZMBcGA1UECxMQSVQgU2VjdXJpdHkgVW5pdDEMMAoGA1UEAxMDVERCMB4XDTE2MDQyMjAwMzc0NFoXDTI2MDMwMTAwMzc0NFowfjELMAkGA1UEBhMCTU4xCzAJBgNVBAgTAlVCMRQwEgYDVQQHEwtVbGFhbmJhYXRhcjEjMCEGA1UEChMaVHJhZGUgYW5kIERldmVsb3BtZW50IEJhbmsxGTAXBgNVBAsTEElUIFNlY3VyaXR5IFVuaXQxDDAKBgNVBAMTA1REQjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKQiUV1vxkqSEeSY7R6C1mWHII8AUAnuYtU/em6xnwHBmZKijWmlAsGtQAbykF0c5c7U18zDCNq+xknIsLk6KNrcW+ACxTsgO0xpGPMqyi5B+kzl787Jqi1K0VUkhzGEqaRfOWxIrh/mdvd7oqRduJS2SHZe7P84eh85URsJ4g4w0KxNNeL3zFSgVEPWTUNCCMyPjiqM+6QW6YYFTJEFzHa01YfOPfFsQK3GRK5Aw7knAzzPIZWODzWfXzFKUNXbmbk511ZT4aa86kMoZBsVOIssGRIYQsvUsKO4eUqhjN1foHgjA6zlu7D8JjyJxg413E4Cnb1HQ8RCqNQ2cakzU+kCAwEAAaMhMB8wHQYDVR0OBBYEFCAA5VvG87U8ecc3LW+ZAhzEVtjtMA0GCSqGSIb3DQEBCwUAA4IBAQBG3Gi88zXaLhL01/vpb+NcPLr1AAEHo8MoZgMyZh3lK4rZDGkA0nVJyIeSkrKmhrQfMJB8zNd+5TMfPYgwuNpZrz17YB8xxEVhVS8d7qHiS/slkh1LSSbEJYzHQEYJtv9958hDYcmuncmbItpsaEtqVLJxt7pjL9kLvGeFcHehfw84xHyajYVqZABXAS5+v7vuUgRjcc47IFs/0ZVIunzuT1YJXvX/0IGoUSHbvu/fzzyCF+TFHzeie6oPojTcjn6MYO+0pSFNTg5clYMe74131FWbof8cX4WSu+VeqwsMRQOBN9MbKLGCBBYdHpowwKmhCD89iEz9rKGj1va1FQLd</X509Certificate></X509Data></KeyInfo></Signature></Message>';
echo $xmlmsg; 
  echo '</textarea> <br/>';                               
// suppress warnings so we can handle them ourselves
libxml_use_internal_errors(true);
$xmlmsg = str_replace("\\\"","\"",$xmlmsg);


$Node=simplexml_load_string($xmlmsg);
  
if ($Node === false) {
    // oh no
    $errors = libxml_get_errors();
    // do something with them
    print_r($errors);
    // really you'll want to loop over them and handle them as necessary for your needs
}
echo "Version: ".$Node->Version[0]."<br>"; 
  echo "OrderID: ".$Node->OrderID[0]."<br>";
  echo "TransactionType: ".$Node->TransactionType[0]."<br>";
  echo "PAN: ".$Node->PAN[0]."<br>";
  echo "PurchaseAmount: ".$Node->PurchaseAmount[0]."<br>";
  echo "Currency: ".$Node->Currency[0]."<br>";
  echo "TranDateTime: ".$Node->TranDateTime[0]."<br>";
  echo "ResponseCode: ".$Node->ResponseCode[0]."<br>";
  echo "OrderStatus: ".$Node->OrderStatus[0]."<br>";
  echo "ApprovalCode: ".$Node->ApprovalCode[0]."<br>";
  echo "MerchantTranID: ".$Node->MerchantTranID[0]."<br>";
  echo "OrderDescription: ".$Node->OrderDescription[0]."<br>";
  echo "ApprovalCodeScr: ".$Node->ApprovalCodeScr[0]."<br>";
  echo "PurchaseAmountScr: ".$Node->PurchaseAmountScr[0]."<br>";
  echo "CurrencyScr: ".$Node->CurrencyScr[0]."<br>";
  echo "OrderStatusScr: ".$Node->OrderStatusScr[0]."<br>";
  echo "ThreeDSVerificaion: ".$Node->ThreeDSVerificaion[0]."<br>";  
  echo "ShopOrderId: ".$Node->ShopOrderId[0]."<br>";
?>



