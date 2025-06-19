<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shop".
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string|null $logo_id
 * @property string $owner_id
 * @property string $status
 * @property bool $is_approved
 * @property bool $is_published
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $owner
 */
class Shop extends ActiveRecord
{

    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PUBLISHED = 'PUBLISHED';
    const STATUS_ARCHIVED = 'ARCHIVED';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'slug', 'owner_id', 'status'], 'required'],
            [['description'], 'string'],
            [['is_approved', 'is_published'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['logo_id', 'owner_id'], 'string', 'max' => 255],
            [['name', 'slug', 'status'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'slug' => 'Slug',
            'logo_id' => 'ID логотипа',
            'owner_id' => 'Владелец',
            'status' => 'Статус',
            'is_approved' => 'Одобрен',
            'is_published' => 'Опубликован',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOwner(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * Возвращает список всех доступных статусов
     * @return string[]
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_ARCHIVED => 'Архив',
        ];
    }

    /**
     * Возвращает метку текущего статуса
     * @return string
     */
    public function getStatusLabel(): string
    {
        return self::getStatusList()[$this->status] ?? $this->status;
    }

    /**
     * @return array
     */
    public static function getList(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
