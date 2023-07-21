<?php

use yii\db\Migration;

/**
 * Class m230717_033446_receipient_rename2
 */
class m230717_033446_receipient_rename2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%transaction}}', 'receipient', 'sender');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230717_033446_receipient_rename2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_033446_receipient_rename2 cannot be reverted.\n";

        return false;
    }
    */
}
