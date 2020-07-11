<?php

use yii\db\Migration;

/**
 * Class m200711_124624_transactions
 */
class m200711_124624_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'from_user_id' => $this->integer()->notNull()->comment('From User ID'),
            'to_user_id' => $this->integer()->notNull()->comment('To User ID'),
            'sum' => $this->double()->notNull()->comment('Sum'),
            'description' => $this->string()->notNull()->comment('Description'),
            'created_at' => $this->integer()->notNull()->comment('Created'),
        ]);

        $this->addForeignKey('fk-from_user_id-to_user', '{{%transactions}}', 'from_user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-to_user_id-to_user', '{{%transactions}}', 'to_user_id', '{{%user}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-to_user_id-to_user', '{{%transactions}}');
        $this->dropForeignKey('fk-from_user_id-to_user', '{{%transactions}}');
        $this->dropTable('{{%transactions}}');
    }
}
