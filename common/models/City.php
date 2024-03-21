<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property float $lat
 * @property float $lon
 * @property string|null $state
 *
 * @property Country $country
 * @property Weather[] $weathers
 */
class City extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['country_id', 'name', 'lat', 'lon'], 'required'],
            [['country_id'], 'default', 'value' => null],
            [['country_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['name', 'state'], 'string', 'max' => 50],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'name' => 'Name',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'state' => 'State',
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    /**
     * Gets query for [[Weathers]].
     *
     * @return ActiveQuery
     */
    public function getWeathers(): ActiveQuery
    {
        return $this->hasMany(Weather::class, ['city_id' => 'id']);
    }
}
