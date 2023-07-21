<?php

use yii\db\Migration;

/**
 * Class m230717_090906_access_token_alter
 */
class m230717_090906_access_token_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{user}}','access_token',$this->string(200));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230717_090906_access_token_alter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_090906_access_token_alter cannot be reverted.\n";

        return false;
    }
    */
}
