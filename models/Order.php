<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 7:08 PM
 */

namespace vanyok\paypalyii\models;


use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Order
 * @package vanyok\paypalyii\models
 *
 * @property integer $id
 * @property string $order_id
 * @property string $status
 * @property string $created_at
 * @property string $payer_name
 * @property string $payer_email
 * @property integer $user_id
 * @property string $updated_at
 *
 */

class Order extends ActiveRecord
{
    const ORDER_COMPLETED = 'COMPLETED';
    const ORDER_APPROVED = 'APPROVED';

    public static function tableName()
    {
        return isset(Yii::$app->PayPalRestApi->table) ? Yii::$app->PayPalRestApi->currencyCode : 'paypal_order';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

}