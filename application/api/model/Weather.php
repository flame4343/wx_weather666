<?php
namespace app\api\model;

use think\Model;
use think\Db;

class Weather extends Model
{
  public function getWeather($id=1)
  {
    $res=Db::name('ins_county')->where('id',$id)->find();
    return $res;
  }
  public function getWeatherCode($county_name="顺义")
  {
    $res=Db::name('ins_county')->where('county_name',$county_name)->find();
    return $res;
  }
  public function getWeatherInfo($weather_code=101010400)
  {
    $res=Db::name('ins_county')->where('weather_code',$weather_code)->find();
    return $res;
  }
}