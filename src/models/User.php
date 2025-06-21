<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * Class User
 *
 * @property int $id
 * @property string $username
 * @property string $full_name
 * @property string $password_hash
 * @property string|null $auth_key
 * @property string $role
 * @property string|null $access_token
 * @property int|null $email_verified
 * @property int $created_at
 * @property int $update_at
 * @property bool $is_social
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_USER = 'USER';
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MANAGER= 'MANAGE';
    const ROLE_SELLER= 'SELLER';

    const ROLE_LIST = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
        self::ROLE_MANAGER,
        self::ROLE_SELLER
    ];

    private string $password;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * Поведения для даты создания и изменения
     *
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @param $insert
     * @return bool
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if ($insert) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->access_token = Yii::$app->security->generateRandomString(64);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'email_verified' => 'Email подтверждён',
            'full_name' => 'Полное имя',
            'password_hash' => 'Хеш пароля',
            'auth_key' => 'Ключ аутентификации',
            'role' => 'Роль',
            'access_token' => 'Токен доступа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'is_social' => 'Авторизован через соц. сети',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'role', 'email'], 'required'],
            [['username', 'full_name', 'email'], 'string', 'min' => 3, 'max' => 255],
            [['username', 'email'], 'unique'],
            ['email', 'email'],

            ['emailVerified', 'boolean'],

            [['role'], 'in', 'range' => self::ROLE_LIST],

            [['password', 'is_social'], 'safe'],

            [['auth_key', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * Поиск пользователя по ID (для сессий)
     *
     * @param $id
     * @return IdentityInterface|null
     */
    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    /**
     * Поиск пользователя по access token (если используется, например, для API)
     *
     * @param $token
     * @param $type
     * @return IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Поиск пользователя по логину
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username): ?self
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * ID пользователя
     *
     * @return int|string|null
     */
    public function getId(): int|string|null
    {
        return $this->getPrimaryKey();
    }

    /**
     * Ключ авторизации для куки
     *
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Проверка ключа авторизации
     *
     * @param $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Проверка пароля (через password_hash)
     *
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Проверка, является ли пользователь администратором
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isSeller(): bool
    {
        return $this->role === self::ROLE_SELLER;
    }

    /**
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    /**
     * @param string $password
     * @return void
     * @throws Exception
     */
    public function setPassword(string $password): void
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'username');
    }

    public static function getRoleList(): array
    {
        return [
            self::ROLE_USER => 'Пользователь',
            self::ROLE_SELLER => 'Продавец',
            self::ROLE_MANAGER => 'Менеджер',
            self::ROLE_ADMIN => 'Администратор',
        ];
    }
}
