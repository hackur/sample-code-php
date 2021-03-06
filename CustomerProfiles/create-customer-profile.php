<?php
  require 'vendor/autoload.php';
  use net\authorize\api\contract\v1 as AnetAPI;
  use net\authorize\api\controller as AnetController;
  define("AUTHORIZENET_LOG_FILE", "phplog");
  // Common setup for API credentials
  $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
  $merchantAuthentication->setName("556KThWQ6vf2");
  $merchantAuthentication->setTransactionKey("9ac2932kQ7kN2Wzq");
  $refId = 'ref' . time();

    // Create the payment data for a credit card
  $creditCard = new AnetAPI\CreditCardType();
  $creditCard->setCardNumber( "4111111111111111" );
  $creditCard->setExpirationDate( "2038-12");
  $paymentCreditCard = new AnetAPI\PaymentType();
  $paymentCreditCard->setCreditCard($creditCard);

  // Create the Bill To info
  $billto = new AnetAPI\CustomerAddressType();
  $billto->setFirstName("Ellen");
  $billto->setLastName("Johnson");
  $billto->setCompany("Souveniropolis");
  $billto->setAddress("14 Main Street");
  $billto->setCity("Pecan Springs");
  $billto->setState("TX");
  $billto->setZip("44628");
  $billto->setCountry("USA");
  
 // Create a Customer Profile Request
 //  1. create a Payment Profile
 //  2. create a Customer Profile   
 //  3. Submit a CreateCustomerProfile Request
 //  4. Validate Profiiel ID returned

  $paymentprofile = new AnetAPI\CustomerPaymentProfileType();

  $paymentprofile->setCustomerType('individual');
  $paymentprofile->setBillTo($billto);
  $paymentprofile->setPayment($paymentCreditCard);
  $paymentprofiles[] = $paymentprofile;
  $customerprofile = new AnetAPI\CustomerProfileType();
  $customerprofile->setDescription("Customer 2 Test PHP");
  $merchantCustomerId = time().rand(1,150);
  $customerprofile->setMerchantCustomerId($merchantCustomerId);
  $customerprofile->setEmail("test2@domain.com");
  $customerprofile->setPaymentProfiles($paymentprofiles);

  $request = new AnetAPI\CreateCustomerProfileRequest();
  $request->setMerchantAuthentication($merchantAuthentication);
  $request->setRefId( $refId);
  $request->setProfile($customerprofile);
  $controller = new AnetController\CreateCustomerProfileController($request);
  $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
  if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
  {
      echo "SUCCESS: PROFILE ID : " . $response->getCustomerProfileId() . "\n";
      echo "SUCCESS: PAYMENT PROFILE ID : " . $response->getCustomerPaymentProfileIdList()[0] . "\n";
   }
  else
  {
      echo "ERROR :  Invalid response\n";
      echo "Response : " . $response->getMessages()->getMessage()[0]->getCode() . "  " .$response->getMessages()->getMessage()[0]->getText() . "\n";
      
  }
?>
