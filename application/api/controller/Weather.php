<?php
namespace app\api\controller;
use think\Controller;

class Weather extends Controller
{
  public function read()
  {
    echo "111111";
    date_default_timezone_set("Asia/Shanghai");
    for($id=2475;$id<=2564;$id++){//2564
    	$model=model('Weather');
    	$data=$model->getWeather($id);
    	$city_code=$data["weather_code"];
    	$url="http://t.weather.sojson.com/api/weather/city/".$city_code;
    	$result=$this->postcurl($url);	
      	var_dump($result);
        if($result["status"]==200){
          	$info=$result["data"]["forecast"][0]["type"];
            $temp["weather_info"]=$info;
      		$temp["gmt_modify"]= $result["time"];
      		$condition = array('id'=>$id);
        	$result = DB('ins_county')->where($condition)->update($temp);//更新数据
          	var_dump($info);
          }
      else{
        var_dump($city_code);
      }
    }
  }
  
  function postcurl($url,$data = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return 	$output=json_decode($output,true);
    }
  public function getWeatherInfo(){//http://140.143.186.215/api/weather/getWeatherInfo/county_name/海淀
    $county_name=input('county_name');
    $model=model('Weather');
    $data=$model-> getWeatherCode($county_name);
    $gmt_modify=$data["gmt_modify"];
    //时间差
    $time=time()-strtotime($gmt_modify);
    if($data["weather_info"]!=NULL&&$time<18000){//没有天气信息或更新超过5小时(18000)
      $weather_info=$data["weather_info"];
    }
    else
    {
      $city_code=$data["weather_code"];
    	$url="http://t.weather.sojson.com/api/weather/city/".$city_code;
    	$result=$this->postcurl($url);	
      	if($result["status"]==200){
          	$weather_info=$result["data"]["forecast"][0]["type"];
          	$temp["weather_info"]=$weather_info;
      		$temp["gmt_modify"]= $result["time"];
          	$id=$data["id"];
      		$condition = array('id'=>$id);
        	$result = DB('ins_county')->where($condition)->update($temp);//更新数据
        }
    }
    return json($weather_info);
  }
  public function getCityCode(){
    $county_name=input('county_name');
    $model=model('Weather');
    $data=$model-> getWeatherCode($county_name);
    if($data){
      $code=200;
    }else{
      $code=404;
    }
    $data=[
      'code'=>$code,
      'citycode'=>$data["weather_code"]
      ];
    return json($data);
  }
  public function getInfo(){
    $weather_code=input('weather_code');
    $model=model('Weather');
    $data=$model-> getWeatherInfo($weather_code);
    if($data){
      $code=200;
      $gmt_modify=$data["gmt_modify"];
    //时间差
      $time=time()-strtotime($gmt_modify);
      if($data["weather_info"]!=NULL&&$time<18000){//没有天气信息或更新超过5小时(18000)
        $weather_info=$data["weather_info"];
      }
      else
      {
    	$url="http://t.weather.sojson.com/api/weather/city/".$weather_code;
    	$result=$this->postcurl($url);	
      	if($result["status"]==200){
          	$weather_info=$result["data"]["forecast"][0]["type"];
          	$temp["weather_info"]=$weather_info;
      		$temp["gmt_modify"]= $result["time"];
          	$id=$data["id"];
      		$condition = array('id'=>$id);
        	$result = DB('ins_county')->where($condition)->update($temp);//更新数据
        }
      }
      $data=[
      'code'=>$code,
      'weather'=>$weather_info
      ];
      return json($data);
    }else{
      $code=404;
      return json($code);
    }
  }
}