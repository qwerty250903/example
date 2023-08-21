<?php
  require_once 'vendor/autoload.php';
  
  print "Processing...\n";
  class DevClass {
    public $group_id = 222059481;
    public $access_token = "vk1.a.HlmOrSWrqEtYmGchg4LlqLXbXovnR9ZL4o_bsLEfWVOWjSzBzTI7LpA22BiackuMG6GNlDY_utuNjIlB2q6PjSdpNx0poS1xppx1JyzpDbXv0norMzmfpz71TGg2gflvEDlHxcFPov9eQFHr_jX4RoRgGzILkJMRlVpRj3Eh4AkbLBcwE8cBtjhgp_h1rJxGr3W3J0cRytRCCSlFwsrXxQ";
    
    //Получиаем количество пользователей
    public function devMethod(){
      $code = 'var members = API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).items;var offset = 1000;while (offset < 25000 && (offset + 0) < 100){members = members + "," + API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": (0 + offset)}).items;offset = offset + 1000;};return API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).count;';
      //запрос на получения количества участников
      $count = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $this->access_token], true);
      $count = $count->response;
      $result = $this->getMembers($this->group_id, $count);
      return $result;
    }
    
    //Получиаем данные
    public function getMembers($group_id, $count) {
      print "\nВыполняетя:\n\nAPI: ". $this->access_token . "\n\n";  
      $code = 'return API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count":'. $count .', "offset":'. 0 .', fields: "name, bdate, country, city, age"}).items;';
      //запрос на получения данных n-нного количества пользователей
      $answer = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $this->access_token], true);
      //print_r($answer->response[0]);
      //print"\n\nКоличество участников: ". $count ."\n\n";
      return $answer;
    }
    public function countMembers(){
      $code = 'var members = API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).items;var offset = 1000;while (offset < 25000 && (offset + 0) < 100){members = members + "," + API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": (0 + offset)}).items;offset = offset + 1000;};return API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).count;';
      //запрос на получения количества участников
      $count = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $this->access_token], true);
      $count = $count->response;
      
      return $count;
    }
  }

  $dev = new DevClass();
  $res = $dev->devMethod();
  $count = $dev->countMembers();
  postMethod($res, $count);

  //Отправляем запросы
  function getMethod($method, $options = [], $decode = true){
    $query = "?".http_build_query($options);
    $url = "https://api.vk.com/method/".$method.urldecode($query);
    $data = file_get_contents($url);
    $decoded = json_decode($data);
    return ($decode ? $decoded : $data);
  }

  function postMethod($res, $count){ 
    $client = new MongoDB\Client("mongodb://localhost:27017");
    $collection = $client->vk_api->GroupMembers;
    for ($i=0; $i < $count; $i++) {
      print_r($res->response[$i]);
      $first_name = $res->response[$i]->first_name;
      $last_name = $res->response[$i]->last_name;
      if(!empty($res->response[$i]->bdate)){
        $birthday = $res->response[$i]->bdate;
        $age = date_diff(date_create($birthday), date_create('now'))->y;
        //print($age);
      }
      else{
        $birthday = 'NULL';
        $age = 0;
      }
      if(!empty($res->response[$i]->country)){
        $country = $res->response[$i]->country->title;
      }
      else{
        $country = 'NULL';
      }
      if(!empty($res->response[$i]->city)){
        $city = $res->response[$i]->city->title;
      }
      else{
        $city = 'NULL';
      }

      //vkdb> db.GroupMembers.insertOne([first_name => '$first_name', last_name=> '$last_name',bdate=>'$birthday', age=>'$age',country=>'$country',city=>'$city'])

      $collection->insertOne(['first_name' => $first_name, 'last_name'=> $last_name, 'bdate'=> $birthday, 'age'=>$age, 'country'=>$country,'city'=>$city]);
      //print_r("\nInsersted with Object ID". $result->getInsertID());
    }
    print"\n\nКоличество участников: ". $count ."\n\n";
  }
?>
