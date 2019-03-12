<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%paypal_order}}`.
 */
class m190312_190212_create_paypal_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%paypal_order}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->string(64),
            'status' => $this->string(24),
            'created_at' => $this->integer(),
            'payer_name' => $this->string(),
            'payer_email' => $this->string(),
            'user_id'=>$this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%paypal_order}}');
    }
}
