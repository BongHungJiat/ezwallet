<?php

use yii\db\Migration;

/**
 * Class m230713_075332_transaction_alter
 */
class m230713_075332_transaction_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%transaction}}', 'status');
        $this->addColumn('{{%transaction}}', 'type', $this->string(20)->notNull());
        $this->addColumn('{{%transaction}}', 'deleted_at', $this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230713_075332_transaction_alter cannot be reverted.\n";

        return false;
    }

    // drop status
    // add type
    // add deleted_at


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230713_075332_transaction_alter cannot be reverted.\n";

        return false;
    }
    */
}
