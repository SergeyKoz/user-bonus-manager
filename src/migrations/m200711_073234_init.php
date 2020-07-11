<?php

use yii\db\Migration;

/**
 * Class m200711_073234_init
 */
class m200711_073234_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->comment('User Name'),
            'balance' => $this->double()->notNull()->defaultValue(0)->comment('Balance'),
            'created_at' => $this->integer()->notNull()->comment('Created'),
            'modified_at' => $this->integer()->notNull()->comment('Modified'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
