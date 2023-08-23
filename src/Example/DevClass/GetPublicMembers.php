<?php
namespace Example\DevCLass;
class GetPublicMembers { 
    //Получиаем количество пользователей
    public function getMembersInfo($access_token, $group_id){
      

      $code = 'var members = API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0}).count;';

      //запрос на получения количества участников
      $count = $this->getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      $count = $count->response;
      $result = $this->getMembers($access_token,$group_id, $count);
      return $result;
    }
    //Получиаем данные
    public function getMembers($access_token, $group_id, $count) {
      print "\nВыполняетя:\n\nAPI: ". $access_token . "\n\n"; 
      $offset = 0;
      if($count < 1000){
        $count_answer = 1000;
      } 
      else{
        $count_answer = $count;
      }
      $code = 'var array=[];';
      while($count_answer > $offset){
        $offset +=1000;
        $code .= 'r=r + API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "count":'. $count .', "offset":'. $offset .', fields: "name, bdate, country, city, age"}).items;';

      }
      $code .= 'return r;';

      
      //запрос на получения данных n-нного количества пользователей
      $answer = $this->getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      return $answer;
    }
    public function getMembersCount($access_token, $group_id){
      //получить количество
      $code = 'return API.groups.getMembers({"group_id": '. $group_id.', "v": "5.131", "sort": "id_asc", "offset": 0, }).count;';
      
      //запрос на получения количества участников
      $count = $this->getMethod("execute", ["code" => urlencode($code), "v"=>"5.131", "access_token" => $access_token], true);
      $count = $count->response;
      return $count;
    }
    protected function getMethod($method, $options = [], $decode = true){
      $query = "?".http_build_query($options);
      $url = "https://api.vk.com/method/".$method.urldecode($query);
      $data = file_get_contents($url);
      $decoded = json_decode($data);
      return ($decode ? $decoded : $data);
    }
    //пример кода
    /*
    private function code()
    {
        $code = 'var r=[];';

        foreach ($this->users as $user) {
            $code .= 'r=r+API.users.get({"user_ids":"'.$user.'","fields":"counters"});';
        }

        $code .= 'return r;';

        return $code;
    }
    */
  }

  