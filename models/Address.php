<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 3/12/19
 * Time: 1:19 PM
 */
namespace vanyok\paypalyii\models;


use yii\base\Model;

class Address extends Model
{
    public $address_line_1;
    public $address_line_2;
    public $admin_area_2;
    public $admin_area_1;
    public $postal_code;
    public $country_code;


}