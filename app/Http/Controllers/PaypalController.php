<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\PaymentExecution;

class PaypalController extends Controller
{
    public function index(Request $request){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AfiQHsNzGbkCiw5qXR-MpPcsyw7jYIb-CFtVM__N1q1VqycUeGg6qPJmwNezX_as7YGKaHvyi0Fd7sfA',     // ClientID
                'EDT52gjGSFCGC2g1cM4DWYXSgXFa_pOgrwjZL2mfoMhczWa3vKKujbd7lj3hPF936bPUedvdMV0cX3gM'      // ClientSecret
            )
        );
        // After Step 2
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal('150.00');
        $amount->setCurrency('USD');

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal_return'))
            ->setCancelUrl(route('paypal_cancel'));

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);
        // After Step 3
        try {
            $payment->create($apiContext);
            echo $payment;
            echo "\n\nRedirect user to approval_url: " . $payment->getApprovalLink() . "\n";
            return redirect($payment->getApprovalLink());
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            echo $ex->getData();
        }
    }
    public function paypalReturn(){
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AfiQHsNzGbkCiw5qXR-MpPcsyw7jYIb-CFtVM__N1q1VqycUeGg6qPJmwNezX_as7YGKaHvyi0Fd7sfA',     // ClientID
                'EDT52gjGSFCGC2g1cM4DWYXSgXFa_pOgrwjZL2mfoMhczWa3vKKujbd7lj3hPF936bPUedvdMV0cX3gM'      // ClientSecret
            )
        );
//        dd(\request()->all());
        // Get payment object by passing paymentId
        $paymentId = $_GET['paymentId'];
        $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
        $payerId = $_GET['PayerID'];

// Execute payment with payer ID
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            // Execute payment
            $result = $payment->execute($execution, $apiContext);
            dd($result);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        }
    }
    public function paypalCancel(){
        return "order canceled";
    }

}
