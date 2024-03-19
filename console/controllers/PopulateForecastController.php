<?php

namespace console\controllers;

use common\models\City;
use common\models\Country;
use common\models\Weather;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\helpers\Console;

class PopulateForecastController extends Controller
{
    private string $appid = '1eacc92855029bcf2f5896154362f5fe';
    private string $baseApiUrl = "https://api.openweathermap.org/";
    private string $cityApiParams = "geo/1.0/direct?q=%s,%s&limit=%s&appid=%s";
    private string $forecastApiParams = "data/2.5/forecast?lat=%s&lon=%s&units=metric&appid=%s";

    public int $limit = 1;
    public $country;
    public $city;

    /**
     * @param $actionID
     * @return string[]
     */
    public function options($actionID): array
    {
        return ['country', 'city'];
    }

    /**
     * @return string[]
     */
    public function optionAliases(): array
    {
        return ['country' => 'country', 'city' => 'city'];
    }

    /**
     * @return void
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $cities = $this->getApiCities();

        if (count($cities) == 0) {
            echo "Can not find city " . $this->city . "\n";
            $this->stdout("Can not find city $this->city \n", Console::FG_RED);
            return;
        }

        foreach ($cities as $city) {
            $country_id = $this->findOrCreateCountry($city->country);

            $city_id = $this->findOrCreateCity($country_id, $city);

            $this->updateOrCreateWeather($city_id, $this->getApiForecast($city->lat, $city->lon));
        }
    }

    /**
     * @param $countryCode
     * @return int
     */
    private function findOrCreateCountry($countryCode): int
    {
        $country = Country::findOne(['country_code' => $countryCode]);
        if (!$country) {
            $country = new Country();
            $country->name = $this->country;
            $country->country_code = $countryCode;
            $country->save();
        }
        $this->stdout("Country: $country->name \n", Console::FG_GREEN);
        return $country->id;
    }

    /**
     * @param $countryId
     * @param $cityData
     * @return int
     */
    private function findOrCreateCity($countryId, $cityData): int
    {
        $city = City::findOne(['country_id' => $countryId, 'name' => $cityData->name]);
        if (!$city) {
            $city = new City();
            $city->country_id = $countryId;
            $city->name = $cityData->name;
            $city->lat = $cityData->lat;
            $city->lon = $cityData->lon;
            $city->state = $cityData->state ?? null;
            $city->save();
        }
        $this->stdout("City: $city->name \n", Console::FG_GREEN);
        return $city->id;
    }

    /**
     * @param $cityId
     * @param $weatherData
     * @return void
     * @throws InvalidConfigException
     */
    private function updateOrCreateWeather($cityId, $weatherData)
    {
        foreach ($weatherData as $list) {
            $datetime = Yii::$app->formatter->asDatetime($list->dt, 'yyyy-MM-dd HH:mm:ss');
            $weather = Weather::findOne(['city_id' => $cityId, 'datetime' => $datetime]);
            if (!$weather) {
                $weather = new Weather();
                $weather->city_id = $cityId;
            }
            $weather->datetime = $datetime;
            $weather->temp_min = $list->main->temp_min;
            $weather->temp_max = $list->main->temp_max;
            $weather->humidity = $list->main->humidity;

            $weather->save();
        }
        $this->stdout("Populated with weathers data\n", Console::FG_GREEN);
    }
    /**
     * @return array
     */
    public function getApiCities(): array
    {
        $url = $this->baseApiUrl . $this->cityApiParams;
        $url = sprintf($url, urlencode($this->city), urlencode($this->country), urlencode($this->limit), urlencode($this->appid));
        return json_decode(file_get_contents($url));
    }

    public function getApiForecast($lat, $lon): array
    {
        $url = $this->baseApiUrl . $this->forecastApiParams;
        $url = sprintf( $url, urlencode($lat), urlencode($lon), urlencode($this->appid));
        $forecast =  json_decode(file_get_contents($url));
        return $forecast->list;
    }
}
