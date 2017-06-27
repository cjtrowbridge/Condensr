<?php

function Condensr($LongformText,$NumberOfSentences=1){
   $URL='https://api.condensr.io/v1';
  
  $Arguments=array(
    'LongformText'      => $LongformText,
    'NumberOfSentences' => $NumberOfSentences
  );
  
  //Set up cURL  
  $cURL = curl_init();
  curl_setopt($cURL,CURLOPT_URL, $URL);
  curl_setopt($cURL,CURLOPT_POST, count($Arguments));
  $URLArguments = http_build_query($Arguments);
  curl_setopt($cURL,CURLOPT_POSTFIELDS, $URLArguments);
  curl_setopt($cURL,CURLOPT_RETURNTRANSFER, true);
  
  //Run cURL and close it
  $Data = curl_exec($cURL);
  if(curl_exec($cURL) === false){
    echo 'Curl error: ' . curl_error($cURL);
  }
  curl_close($cURL);
  
  $Data=json_decode($Data,true);
  
  return $Data;
}
