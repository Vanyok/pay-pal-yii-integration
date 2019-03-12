<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 10:56 AM
 */

namespace  vanyok\paypalyii;


use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use vanyok\paypalyii\models\Order;
use Yii;

class GetOrder
{

    // 2. Set up your server to receive a call from the client
    /**
     *You can use this function to retrieve an order by passing order ID as an argument.
     */
    public static function getOrder($orderId)
    {

        // 3. Call PayPal to get the transaction details
        $client = PayPalClient::client();
        $response = $client->execute(new OrdersGetRequest($orderId));

        if(Yii::$app->PayPalRestApi->debug){
            Yii::info("Status Code: {$response->statusCode}\n
            Status: {$response->result->status}\n
            Order ID: {$response->result->id}\n 
            Intent: {$response->result->intent}\n");
        }
        if(Yii::$app->PayPalRestApi->store_orders){
            $order = Order::find()->where(['order_id'=>$response->result->id])->one();
            if($order){
                $order->status = $response->result->status;
                $order->payer_name = implode(" ",$response->result->payer->name) ;
                $order->payer_email = $response->result->payer->email_address;
                $order->save();
            }
        }
        return $response->result;
    }
}