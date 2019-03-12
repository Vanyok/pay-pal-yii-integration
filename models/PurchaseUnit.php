<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 12:56 PM
 */
namespace vanyok\paypalyii\models;


use Yii;
use yii\base\Model;

class PurchaseUnit extends Model
{
    public $referenceId;
    public $description;
    public $customId;
    public $soft_descriptor;
    public $currencyCode;
    public $item_value;
    /**
     * @var Shipping
     */
    public $shipping;
    public $shipping_value = 0;
    public $shipping_discount = 0;
    public $handling_value = 0;
    public $tax_total = 0;
    /**
     * @var Item []
     */
    public $items;

    public function getForRequest(){
        $total_value = 0;
        $tax_total = 0;
        $params = array();

        if(count($this->items)){
            foreach ($this->items as $item){
                $params['items'][] = $item->getForRequest();
                $total_value += $item->value*$item->quantity;
                $tax_total += $item->tax;
            }
        }

        $brakedown =  array(
            'item_total' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($total_value,2),
                ),
            'shipping' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($this->shipping_value,2),
                ),
            'handling' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($this->handling_value,2),
                ),
            'tax_total' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($tax_total),
                ),
            'shipping_discount' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($this->shipping_discount),
                ),
        );

        $value = $total_value + $tax_total + $this->shipping_value - $this->shipping_discount - $this->handling_value;
        $params ['amount'] =  array(
            'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
            'value' => number_format($value,2),
            'breakdown' => $brakedown
        );

        if($this->referenceId){
            $params['reference_id'] = $this->referenceId;
        }
        if($this->description){
            $params['description'] = $this->description;
        }
        if($this->customId){
            $params['custom_id'] = $this->customId;
        }
        if($this->soft_descriptor){
            $params['soft_descriptor'] = $this->soft_descriptor;
        }
        if($this->shipping){
            $params['shipping'] = $this->shipping->getForRequest();
        }

        return $params;
    }
}