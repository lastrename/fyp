<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $stock
 * @property string $status
 * @property int $category_id
 * @property int $shop_id
 * @property int $user_id
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Category $category
 * @property Shop $shop
 * @property User $user
 */
class Product extends ActiveRecord
{
    const STATUS_DRAFT = 'DRAFT';
    const STATUS_PUBLISHED = 'PUBLISHED';
    const STATUS_OUT_OF_STOCK = 'OUT_OF_STOCK';


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
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['updated_at'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'DRAFT'],
            [['name', 'description', 'price', 'stock', 'category_id', 'shop_id', 'user_id'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['stock', 'category_id', 'shop_id', 'user_id'], 'default', 'value' => null],
            [['stock', 'category_id', 'shop_id', 'user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'status'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::class, 'targetAttribute' => ['shop_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'price' => 'Цена',
            'stock' => 'Наличие',
            'status' => 'Статус',
            'category_id' => 'Category ID',
            'shop_id' => 'Shop ID',
            'user_id' => 'User ID',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Shop]].
     *
     * @return ActiveQuery
     */
    public function getShop(): ActiveQuery
    {
        return $this->hasOne(Shop::class, ['id' => 'shop_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Список статусов продукта
     * @return array
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_OUT_OF_STOCK => 'Нет в наличии',
        ];
    }

    /**
     * Метка текущего статуса
     * @return string
     */
    public function getStatusLabel(): string
    {
        return self::getStatusList()[$this->status] ?? $this->status;
    }
}
