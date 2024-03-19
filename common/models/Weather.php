<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "weather".
 *
 * @property int $id
 * @property int $city_id
 * @property string $datetime
 * @property float $temp_min
 * @property float $temp_max
 * @property int $humidity
 *
 * @property City $city
 */
class Weather extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'weather';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'datetime', 'temp_min', 'temp_max', 'humidity'], 'required'],
            [['city_id', 'humidity'], 'default', 'value' => null],
            [['city_id', 'humidity'], 'integer'],
            [['datetime'], 'safe'],
            [['temp_min', 'temp_max'], 'number'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_id' => 'City ID',
            'datetime' => 'Datetime',
            'temp_min' => 'Temp Min',
            'temp_max' => 'Temp Max',
            'humidity' => 'Humidity',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }
}
