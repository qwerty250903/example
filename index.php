<?php
  require_once 'vendor/autoload.php';
  require_once 'getdata.php';
  require_once 'postdata.php';
  
  print "Processing...\n";
  class DevClass { 
    //Получиаем количество пользователей
    public function devMethod($access_token, $group_id){
      $code = 'var members = API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).items;var offset = 1000;while (offset < 25000 && (offset + 0) < 100){members = members + "," + API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "count": 1000, "offset": (0 + offset)}).items;offset = offset + 1000;};return API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).count;';
      //запрос на получения количества участников
      $count = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      $count = $count->response;
      $result = $this->getMembers($access_token,$group_id, $count);
      return $result;
    }
    //Получиаем данные
    public function getMembers($access_token, $group_id, $count) {
      print "\nВыполняетя:\n\nAPI: ". $access_token . "\n\n";  
      $code = 'return API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "count":'. $count .', "offset":'. 0 .', fields: "name, bdate, country, city, age"}).items;';
      //запрос на получения данных n-нного количества пользователей
      $answer = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      return $answer;
    }
    public function countMembers($access_token, $group_id){
      $code = 'var members = API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).items;var offset = 1000;while (offset < 25000 && (offset + 0) < 100){members = members + "," + API.groups.getMembers({"group_id": '.$group_id.', "v": "5.131", "sort": "id_asc", "count": 1000, "offset": (0 + offset)}).items;offset = offset + 1000;};return API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0, fields: "name, birthday, country, city, age"}).count;';
      //запрос на получения количества участников
      $count = getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      $count = $count->response;
      return $count;
    }
  }


  $group_id = readline("Введите айди группы: ");
  $access_token = readline("Введите API токен: ");
  $dev = new DevClass();
  $res = $dev->devMethod($access_token, $group_id);
  $count = $dev->countMembers($access_token, $group_id);
  postMethod($res, $count);

  
?>
