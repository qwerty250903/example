<?php
  namespace Example;

  require_once __DIR__ . '/vendor/autoload.php'; //зачем __DIR__ есть можно просто require_once 'vendor/autoload.php'
  require_once 'getdata.php';
  require_once 'postdata.php';

  use Example\DevCLass\DevClass;
  
  print "Processing...\n";

  $group_id = readline("Введите айди группы: ");
  $access_token = readline("Введите API токен: ");
  $dev = new DevClass();
  $res = $dev->devMethod($access_token, $group_id);
  $count = $dev->countMembers($access_token, $group_id);
  postMethod($res, $count);

