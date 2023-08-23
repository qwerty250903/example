<?php
  
  require_once __DIR__ . '/vendor/autoload.php'; //зачем __DIR__ есть можно просто require_once 'vendor/autoload.php';
  use Example\DevCLass\GetPublicMembers;
  use Example\DevCLass\SaveMembers;
  
  print "Processing...\n";
  //получение данных от пользователя
  $group_id = readline("Введите айди группы: ");
  $access_token = readline("Введите API токен: ");
  //получение пользователей сообщества
  $members = new GetPublicMembers();
  //получение информации пользователей
  $result = $members->getMembersInfo($access_token, $group_id);
  //получение количества пользователей
  $count = $members->getMembersCount($access_token, $group_id);
  //сохранение данных
  $save = new SaveMembers;
  $save->saveMembers($res, $count);

