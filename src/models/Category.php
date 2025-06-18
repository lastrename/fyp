<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string|null $description
 * @property string $created_at
 * @property string|null $updated_at
 */
class Category extends ActiveRecord
{

    const TYPE_PRODUCT = 'PRODUCT';
    const TYPE_ARTICLE = 'ARTICLE';

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
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['description', 'updated_at'], 'default', 'value' => null],
            [['type'], 'default', 'value' => 'PRODUCT'],
            [['name', 'slug'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'slug', 'type'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'name' => 'Name',
            'slug' => 'Slug',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Список типов категории
     * @return string[]
     */
    public static function getTypeList(): array
    {
        return [
            self::TYPE_PRODUCT => 'Продукт',
            self::TYPE_ARTICLE => 'Статья',
        ];
    }

    /**
     * Метка текущего типа
     * @return string
     */
    public function getTypeLabel(): string
    {
        return self::getTypeList()[$this->type] ?? $this->type;
    }
}
