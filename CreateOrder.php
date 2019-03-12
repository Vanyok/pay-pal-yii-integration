<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 10:55 AM
 */

namespace vanyok\paypalyii;


use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

class Order
{

    private $items;


    // 2. Set up your server to receive a call from the client
    /**
     *This is the sample function to create an order. It uses the
     *JSON body returned by buildRequestBody() to create an new order.
     */
    public  function createOrder($debug=false)
    {

        // 3. Call PayPal to set up an authorization transaction
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->buildRequestBody();

        $client = PayPalClient::client();
        $response = $client->execute($request);
        if ($debug)
        {
            \Yii::info("Status Code: {$response->statusCode}\n 
            Status: {$response->result->status}\n Order ID: {$response->result->id}\n 
            Intent: {$response->result->intent}\n Links:\n");
            foreach($response->result->links as $link)
            {
                \Yii::info("\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n");
            }

            // To print the whole response body, uncomment the following line
            // echo json_encode($response->result, JSON_PRETTY_PRINT);
        }


        return $response;
    }

    /**
     *Setting up the JSON request body for creating the order. Set the intent in
     *the request body to "AUTHORIZE" for authorize intent flow.
     */
    private  function buildRequestBody()
    {
        return array(
            'intent' => 'AUTHORIZE',
            'application_context' =>
                array(
                    'return_url' => 'https://example.com/return',
                    'cancel_url' => 'https://example.com/cancel'
                ),
            'purchase_units' =>
                array(
                    0 =>
                        array(
                            'amount' =>
                                array(
                                    'currency_code' => 'USD',
                                    'value' => '220.00'
                                )
                        )
                )
        );
    }
}
