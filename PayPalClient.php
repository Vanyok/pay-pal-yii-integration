<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 10:35 AM
 */

namespace  vanyok\paypalyii;


use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use Yii;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use ProductionEnvironment.
     */
    public static function environment()
    {
        if(Yii::$app->PayPalRestApi->mode == 'live'){
            $clientId =  Yii::$app->params["PAYPAL-LIVE-CLIENT-ID"];
            $clientSecret =  Yii::$app->params["PAYPAL-LIVE-CLIENT-SECRET"];
        }else{
            $clientId =  Yii::$app->params["PAYPAL-SANDBOX-CLIENT-ID"];
            $clientSecret =  Yii::$app->params["PAYPAL-SANDBOX-CLIENT-SECRET"];
        }

        return new SandboxEnvironment($clientId, $clientSecret);
    }
}