<?php
/**
 * a helper to make paypal express checkout calls.
 * @author duf
 */
class PaypalHelper{
	var $paypal_transaction_id;
	var $campaign_id;
	var $transaction_id;
	var $amount;
	public function checkout($account,$details,$return_url,$cancel_url){
		//pr($details);
		return $this->setExpressCheckout($account,$details,$return_url,$cancel_url);
	}
	private function setExpressCheckout($account,$details,$return_url,$cancel_url){
		
		global $CONFIG,$ENGINE_PATH,$PAYPAL;
		$path = $ENGINE_PATH.'Utility/paypal/lib';
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		
		require_once($path.'/services/PayPalAPIInterfaceService/PayPalAPIInterfaceServiceService.php');
		require_once('PPLoggingManager.php');
		$logger = new PPLoggingManager('SetExpressCheckout');
		
		$returnUrl = $return_url;
		$cancelUrl = $cancel_url;
		
		
		$_itemAmount = $details['total_price'];
		$_itemQuantity = 1;
		
		$currencyCode = "USD";
		$shippingTotal = new BasicAmountType($currencyCode, 0);
		$handlingTotal = new BasicAmountType($currencyCode, 0);
		$insuranceTotal = new BasicAmountType($currencyCode, 0);
		
		$itemAmount = new BasicAmountType($currencyCode, $_itemAmount);
		$itemTotalValue = $_itemAmount * $_itemQuantity;
		$taxTotalValue = 0;
		
		$orderTotalValue = $shippingTotal->value + $handlingTotal->value + 
							$insuranceTotal->value +
							$itemTotalValue + $taxTotalValue;
							
		$itemDetails = new PaymentDetailsItemType();
		$itemDetails->Name = "{$details['transaction_id']} - Topic 30 Days Subscription";
		$itemDetails->Amount = $itemAmount;
		$itemDetails->Quantity = $itemQuantity;
		$itemDetails->ItemCategory = "Digital";
		$itemDetails->Tax = new BasicAmountType($currencyCode, 0);
		
		$address = new AddressType();
		$address->CityName = "";
		$address->Name = "#{$account->id} - {$account->first_name} {$account->last_name}";
		$address->Street1 = "";
		$address->StateOrProvince = "";
		$address->PostalCode = "";
		$address->Country = "";
		$address->Phone = "";
		
		$PaymentDetails = new PaymentDetailsType();
		$PaymentDetails->PaymentDetailsItem[0] = $itemDetails;
		$PaymentDetails->ShipToAddress = $address;
		$PaymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
		$PaymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);
		$PaymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
		$PaymentDetails->PaymentAction = 'Sale';
		$PaymentDetails->InvoiceID = "{$details['transaction_id']}";
		
		$setECReqDetails = new SetExpressCheckoutRequestDetailsType();
		$setECReqDetails->PaymentDetails[0] = $PaymentDetails;
		$setECReqDetails->CancelURL = $cancelUrl;
		$setECReqDetails->ReturnURL = $returnUrl;
		
		// Shipping details
		$setECReqDetails->NoShipping = 1;
		$setECReqDetails->AddressOverride = 0;
		$setECReqDetails->ReqConfirmShipping = 0;
		
		// Billing agreement
		$billingAgreementDetails = new BillingAgreementDetailsType('None');
		$billingAgreementDetails->BillingAgreementDescription = '';
		$setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);
		$setECReqDetails->AllowNote = 0;
		
		
		$setECReqType = new SetExpressCheckoutRequestType();
		$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
		$setECReq = new SetExpressCheckoutReq();
		$setECReq->SetExpressCheckoutRequest = $setECReqType;
		
		$paypalService = new PayPalAPIInterfaceServiceService();
		try {
			/* wrap API method calls on the service object with a try catch */
			$setECResponse = $paypalService->SetExpressCheckout($setECReq);
		} catch (Exception $ex) {
			
		}
		if(isset($setECResponse)) {
			
			if($setECResponse->Ack =='Success') {
				$token = $setECResponse->Token;
				// Redirect to paypal.com here
				$payPalURL = $PAYPAL['redirection_url'].'&token=' . $token;
				return $payPalURL;
			}
		}
	}
	public function finalizeTransaction($billing){
		global $CONFIG,$ENGINE_PATH,$PAYPAL;
		$path = $ENGINE_PATH.'Utility/paypal/lib';
		set_include_path(get_include_path() . PATH_SEPARATOR . $path);
		require_once('services/PayPalAPIInterfaceService/PayPalAPIInterfaceServiceService.php');
		require_once('PPLoggingManager.php');
		
		$logger = new PPLoggingManager('GetExpressCheckout');
		
		$token = $_REQUEST['token'];
		
		$getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($token);
		
		$getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
		$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
		
		$paypalService = new PayPalAPIInterfaceServiceService();
		try {
			/* wrap API method calls on the service object with a try catch */
			$getECResponse = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
		} catch (Exception $ex) {
			return false;
		}
		if(isset($getECResponse)) {
			if($getECResponse->Ack!="Success"){return false;}
			$transaction_id = $getECResponse->GetExpressCheckoutDetailsResponseDetails->InvoiceID;
			$this->transaction_id = $transaction_id;
			$order = $billing->getOrderByTransactionId($transaction_id);
			$orderTotal = new BasicAmountType();
			$orderTotal->currencyID = 'USD';
			$orderTotal->value = $order['total_price'];
			$this->amount = $order['total_price'];
			
			$PaymentDetails= new PaymentDetailsType();
			$PaymentDetails->OrderTotal = $orderTotal;
			
			$DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
			$DoECRequestDetails->PayerID = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PayerInfo->PayerID;
			$DoECRequestDetails->Token = $token;
			$DoECRequestDetails->PaymentAction = "Sale";
			$DoECRequestDetails->PaymentDetails[0] = $PaymentDetails;
			
			$DoECRequest = new DoExpressCheckoutPaymentRequestType();
			$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;

			$DoECReq = new DoExpressCheckoutPaymentReq();
			$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;
			
			try {
				/* wrap API method calls on the service object with a try catch */
				$DoECResponse = $paypalService->DoExpressCheckoutPayment($DoECReq);
			} catch (Exception $ex) {
				return false;
			}
			if(isset($DoECResponse)) {
				if($DoECResponse->Ack=="Success"){
					$paypal_transaction_id = $DoECResponse->DoExpressCheckoutPaymentResponseDetails->PaymentInfo->TransactionID;
					$this->paypal_transaction_id = $paypal_transaction_id;
					$this->campaign_id = $order['campaign_id'];
					return $billing->order_completed($transaction_id,$paypal_transaction_id);
				}
			}	
		}
	}
}
?>