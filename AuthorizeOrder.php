<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 10:59 AM
 */

namespace  vanyok\paypalyii;


use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;

class AuthorizeOrder
{
    // 2. Set up your server to receive a call from the client
    /**
     *Use this function to perform authorization on the approved order
     *Pass a valid, approved order ID as an argument.
     */
    public static function authorizeOrder($orderId, $debug=false)
    {
        $request = new OrdersAuthorizeRequest($orderId);
        $request->body = self::buildRequestBody();
        // 3. Call PayPal to authorize an order
        $client = PayPalClient::client();
        $response = $client->execute($request);
        // 4. Save the authorization ID to your database. Implement logic to save authorization to your database for future reference.
        if ($debug)
        {
            print "Status Code: {$response->statusCode}\n";
            print "Status: {$response->result->status}\n";
            print "Order ID: {$response->result->id}\n";
            print "Authorization ID: {$response->result->purchase_units[0]->payments->authorizations[0]->id}\n";
            print "Links:\n";
            foreach($response->result->links as $link)
            {
                print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            }
            print "Authorization Links:\n";
            foreach($response->result->purchase_units[0]->payments->authorizations[0]->links as $link)
            {
                print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
            }
            // To toggle printing the whole response body comment/uncomment the following line
            echo json_encode($response->result, JSON_PRETTY_PRINT), "\n";
        }
        return $response;
    }

    /**
     *Setting up request body for authorization.
     *Refer to API reference for details.
     */
    public static function buildRequestBody()
    {
        return "{}";
    }
}