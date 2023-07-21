<?php

use yii\db\Migration;

/**
 * Class m230717_104109_delete_access_token
 */
class m230717_104109_delete_access_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{user}}','access_token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230717_104109_delete_access_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_104109_delete_access_token cannot be reverted.\n";

        return false;
    }
    */
}
