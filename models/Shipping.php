<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 1:18 PM
 */
namespace vanyok\paypalyii\models;

use yii\base\Model;

class Shipping extends Model
{
    public $method;

    /**
     * @var Address
     */
    public $address;

    public function rules()
    {
        [['address','method'],'required'];
    }

    /**
     *  creates prepared array for request call
     * @return array
     */
    public function getForRequest(){

        return array(
            'method' => $this->method,
            'address' => $this->address->attributes()
        );
    }
}