<?php

  class Data {

  } 
  print "Processing...\n";
  class DevClass {
    public $group_id = 222059481;
    public $membersGroups = array();
    public $access_token = "vk1.a.j229q0vVYaA5xjmCPDmW7b_uzXAKAgJj0SzuWRqERUp3bTWJ6JmXRQ7Fq-QX7vzQVU3UEUo7LZT9n03yDMmo-4EPhabU8xmgR25gT-fPlWiASYy8HQ9uY5A2Sy8-GDjEtPY0CUaid-bQKTg5-GpE1VcK90vGQ00sL1s4U-jDEBX5zbEZCKLN3GhXcniZe2MRoK6E9njbDbndx_VWvDa_1w";
    
    public function devMethod(){
      $opts = ["group_id"=>$this->group_id, "access_token"=>$this->access_token, "fields"=>"members_count", "v"=>"5.131"];
      $answer = getMethod("groups.getById", $opts, true);
      //$members_count = $answer->response[0]->members_count;
      $members_count = 100;  //количество абоб было 4500
      $members_groups = 0;    //изначально в массиве 0 объектов
      //выполняем цикл пока полученное кол-во участников меньше общего кол-ва участников в группе
      while($members_count > $members_groups){
        usleep(300);   //задержка на 0.3 сек.
        $answer = $this->getMembers($this->group_id, $members_count);
        if(empty($answer->response)){
          //echo "\nNO RESPONSE\n\n";
          print_r($answer);
          //$answer->response = NULL;
          print_r("\nКол-во участников: $members_groups (Произошла ошибка)\n\n");
          die();
        }
        //print_r($opts);
        $new = explode(",",$answer->response);
        //print_r($answer);
        $this->membersGroups = array_merge($this->membersGroups, $new);
        $members_groups = count($this->membersGroups);
      }

      print_r("\nКол-во участников: $members_groups\n\n");
      print_r($this->membersGroups);
      die();
    }
    
    public function getMembers($group_id, $members_count) {
      $members_groups = count($this->membersGroups);
      $offset = 1000;

      //лучше юзать return [API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": 0}).items];
      
      $code =  'var members = API.groups.getMembers({"group_id": '.$this->group_id.', "v": "5.131", "sort": "id_asc", "count": '.$offset.', "offset": '.$members_groups.'}).items;'
        .'var offset = '.$offset.';'
        .'while (offset < 25000 && (offset + '.$members_groups.') < '.$members_count.')'
        .'{members = members + "," + API.groups.getMembers({"group_id": '.$this->group_id.', "v": "5.131", "sort": "id_asc", "count": '.$offset.', "offset": ('.$members_groups.' + offset)}).items;offset = offset + '.$offset.';};'
        .'return members;';
      //code = 'return [API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": 0}).items];';
      print "\nВыполняетя:\n\nAPI: ". $this->access_token . "\n\nЗапрос: ". $code.  "\n";  
      
      $answer =getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $this->access_token], true);
          return $answer;
    }
  }
  //protected $method_url = "https://api.vk.com/method/";

  $dev = new DevClass();
  $dev->devMethod();

  function getMethod($method, $options = [], $decode = true){
    $query = "?".http_build_query($options);
    $url = "https://api.vk.com/method/".$method.urldecode($query);
    $data = file_get_contents($url);
    $decoded = json_decode($data);
    return ($decode ? $decoded : $data);
  }

  /*
  var members = API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": 0}).items; var offset = 1000; while (offset < 25000 && (offset + 0) < 45000){members = members + "," + API.groups.getMembers({"group_id": 222059481, "v": "5.131", "sort": "id_asc", "count": 1000, "offset": (0 + offset)}).items;offset = offset + 1000;}; return members;
  */
  ?>
