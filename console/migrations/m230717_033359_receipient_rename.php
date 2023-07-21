<?php

use yii\db\Migration;

/**
 * Class m230717_033359_receipient_rename
 */
class m230717_033359_receipient_rename extends Migration
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
        echo "m230717_033359_receipient_rename cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_033359_receipient_rename cannot be reverted.\n";

        return false;
    }
    */
}
