<?php

use yii\db\Migration;

/**
 * Class m230721_042711_stripe_transaction
 */
class m230721_042711_stripe_transaction extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stripe_transaction}}', [
            'id' => $this->primaryKey(),
            'intentId' => $this->string(100)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull(),
        ]);

        // Add foreign key constraint to link 'user_id' to 'user' table
        $this->addForeignKey(
            'fk-stripe_transaction-user_id',
            '{{%stripe_transaction}}',
            'user_id',
            '{{%user}}', // Replace 'user' with the actual name of your user table
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230721_042711_stripe_transaction cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230721_042711_stripe_transaction cannot be reverted.\n";

        return false;
    }
    */
}
