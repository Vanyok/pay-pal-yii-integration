Yii2 PayPal Api Extension (Under development)
=========================================
Yii2  PayPal Api extension  use to integrate simple PayPal payment in your website.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require vanyok/pay-pal-yii-integration:dev-master

```

or add

```
"vanyok/pay-pal-yii-integration": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

1. Create developer account in PayPal and then create an app.  [PayPal Developer Dashboard](https://developer.paypal.com/).
2. Copy and paste the client Id and client secret in params.php file that exists in app config directory:
```php
<?php

return [
    'adminEmail' => 'admin@example.com',
    'payPalClientId'=>'app client id here',
    'payPalClientSecret'=>'app client secret here'
];


```
3. Configure the extension  in components section in web.php file exists in app config directory: 

```php
<?php
'components'=> [
    ...
'PayPalRestApi'=>[
            'class'=>'vanyok\paypalyii\PayPalRestApi',
            'returnUrl'=>'/site/welcome', // Redirect Url after payment
            'cancelUrl'=>'/site/canceled',
            'currencyCode' => 'USD',
            'debug' => true,
            'mode' => 'sandbox',// 'live' or 'sandbox'
            'store_orders'=>true,
        ]
            ...
        ]

```
4. Controller example:
       call first the checkout action that will redirect you to the redirectUrl you mentioned in the previous step,
       in this example ("/site/make-payment")

```php
<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;

class SiteController extends Controller
{
   
    public function actionCheckout(){
        // Setup order information array with all items
        $items = array();
        $item = new Item;
               $item->load(['Item' => [
                   'name' => 'Item name',
                   'description' => 'Item Description',
                   'value'=>37
               ]]);
                $items[] = $item; 
               // In this action you will redirect to the PayPpal website to login with you buyer account and complete the payment
               Yii::$app->PayPalRestApi->checkOut($items);
    }

    public function actionWelcome(){
         // Setup order information array 
        $order_id = Yii::$app->request->get('token');
        $order = Yii::$app->PayPalRestApi->getOrder($order_id);
               if($order->status == Order::ORDER_COMPLETED || $order->status == Order::ORDER_APPROVED){
                   ...
        }

    }
}


```


