<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 1:01 PM
 */
namespace vanyok\paypalyii\models;

use Yii;
use yii\base\Model;

class Item extends Model
{
    public $name;
    public $description;
    public $sku;
    public $value = 0;
    public $category;
    public $quantity = 1;
    public $tax = 0;

    public function rules()
    {
        return [
            [['name'],'required'],
            [['name','description','sku','value','category','quantity','tax'],'safe']
        ];
    }

    /**
     *  Makes prepared array for request
     * @return array
     */
    public function getForRequest(){
        $params = array(
            'name' => $this->name,
            'unit_amount' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($this->value,2),
                ),
            'tax' =>
                array(
                    'currency_code' => Yii::$app->PayPalRestApi->currencyCode,
                    'value' => number_format($this->tax,2),
                ),
            'quantity' => $this->quantity,
        );;

        if($this->description){
            $params['description'] = $this->description;
        }
        if($this->sku){
            $params['sku'] = $this->sku;
        }
        if($this->category){
            $params['category'] = $this->category;
        }

        return $params;
    }
}