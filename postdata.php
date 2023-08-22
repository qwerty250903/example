<?php
    require_once 'vendor/autoload.php';
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
            $collection->insertOne(['first_name' => $first_name, 'last_name'=> $last_name, 'bdate'=> $birthday, 'age'=>$age, 'country'=>$country,'city'=>$city]);
            //print_r("\nInsersted with Object ID". $result->getInsertID());
        }
        print"\n\nКоличество участников: ". $count ."\n\n";
  }
?>