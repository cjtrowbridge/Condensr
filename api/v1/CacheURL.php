<?php

function CacheURL($URL){
  //DeleteOldCache();  
  $Path = 'cache/'.md5($URL).'.json';
  if(file_exists($Path)){
    if(isset($_GET['verbose'])){echo '<p>Fetching '.$URL.' From Cache: '.$Path.'</p>';}
    return file_get_contents($Path);
  }
  if(isset($_GET['verbose'])){echo '<p>Fetching '.$URL.' Fresh: '.$Path.'</p>';}
  $Data = file_get_contents($URL);
  file_put_contents($Path,$Data);
  return $Data;
}
