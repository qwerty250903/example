<?php
    require_once 'vendor/autoload.php';
    function getMethod($method, $options = [], $decode = true){
        $query = "?".http_build_query($options);
        $url = "https://api.vk.com/method/".$method.urldecode($query);
        $data = file_get_contents($url);
        $decoded = json_decode($data);
        return ($decode ? $decoded : $data);
    }
