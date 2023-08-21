<?php
    $j = 2;
    for ($i=0; $i < $j; $i++) { 
        //print $i . " ";
    }
    //print "\nend";
    
    $bdate = '25.9.2003';
    $age = date_diff(date_create($bdate), date_create('now'))->y;
    print($age);
    /*
    function calculate_age($birthday) {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
          $age--;
        }
        return $age;
      }
      print calculate_age('25.9.2003');
      */
?>

