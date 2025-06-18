<?php

use app\models\User;
use yii\db\Migration;

class m250616_173604_user_create_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'email_verified' => $this->boolean()->null(),
            'full_name' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'auth_key' => $this->string(255)->null(),
            'role' => $this->string()->defaultValue(User::ROLE_USER)->notNull(),
            'access_token' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'update_at' => $this->integer()->notNull(),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'email' => 'admin@fyp.ru',
            'full_name' => 'Administrator',
            'password_hash' => Yii::$app->security->generatePasswordHash('adminfyp'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'role' => User::ROLE_ADMIN,
            'access_token' => null,
            'created_at' => time(),
            'update_at' => time(),
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
