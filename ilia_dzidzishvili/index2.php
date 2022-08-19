<?php
  echo 'started';
  $url = 'https://api.nomics.com/v1/currencies/ticker?key=827e44e59005c5d2d404dfc432b2980503f301f7';
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');



  // curl_setopt($curl, CURLOPT_HTTPHEADER, ['x-user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0', 'x-forwarded-for: 91.184.107.246']);
  $response = curl_exec($curl);
  $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);
  
   // $response = file_get_contents('https://api.nomics.com/v1/currencies/ticker?key=827e44e59005c5d2d404dfc432b2980503f301f7');
  
  $response = json_decode($response);
  print_r($response);


?>