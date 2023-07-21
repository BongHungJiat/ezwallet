<?php

use yii\db\Migration;

/**
 * Class m230717_032715_receipient_alter
 */
class m230717_032715_receipient_alter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%transaction}}', 'receipient', $this->integer());
    
        // Add foreign key constraint
        $this->addForeignKey(
            'fk-transaction-receipient',
            '{{%transaction}}',
            'receipient',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230717_032715_receipient_alter cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230717_032715_receipient_alter cannot be reverted.\n";

        return false;
    }
    */
}
