<?php
/**
 * Created by PhpStorm.
 * User: mhmdbackershehadi
 * Date: 7/3/18
 * Time: 11:08 PM
 */
namespace  vanyok\paypalyii;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use vanyok\paypalyii\models\Order;
use vanyok\paypalyii\models\PurchaseUnit;
use yii\helpers\Url;
use Yii;
class PayPalRestApi
{

    public $returnUrl;
    public $cancelUrl;
    public $items = [];
    public $currencyCode;
    public $debug;
    public $intent = 'CAPTURE';
    public $brand_name;
    public $store_orders = false;
    public $mode;

    public function __construct()
    {

    }

    /**
     * @param $item \vanyok\paypalyii\models\Item
     */
    public function addItem($item){
        $this->items[] = $item;
    }


    public function getRequest(){
        $request = new OrdersCreateRequest();
        $request->headers["prefer"] = "return=representation";
        return $request;
    }

    public function checkOut($items){
        $this->items = $items;
        // 3. Call PayPal to set up an authorization transaction
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->buildRequestBody();

        $client = PayPalClient::client();
        $response = $client->execute($request);
        if ($this->debug)
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
        if($this->store_orders){
            $order = new Order();
            $order->order_id = $response->result->id;
            $order->user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->getId();
            $order->status = $response->result->status;
            $order->save();
        }
        foreach($response->result->links as $link)
        {
            \Yii::info("\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n");
            if($link->rel == "approve"){
                return \Yii::$app->controller->redirect($link->href);
            }
        }
        return true;
    }

    private function buildRequestBody(){
        $purchaseUnit = new PurchaseUnit();
        $purchaseUnit->items = $this->items;
       // $purchaseUnit->referenceId = 'rfrnc123456';
        return array(
            'intent' => $this->intent,
            'application_context' =>
                array(
                    'return_url' => Url::to([$this->returnUrl],true),
                    'cancel_url' => Url::to([$this->cancelUrl],true)
                ),
            'purchase_units' =>
                array(
                    0 => $purchaseUnit->getForRequest()
                )
        );
    }

    public function getOrder($order_id){
        return GetOrder::getOrder($order_id);
    }
}