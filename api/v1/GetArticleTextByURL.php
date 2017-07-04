<?php

function GetArticleTextByURL($URL){
  
  
  include('CacheURL.php');
  $LongformText=CacheURL($_REQUEST['LongformURL']);
  
  if($LongformText==false){
    die('<p>Unable to fetch URL.</p>');
  }
  
  //Get article text only
  $doc = new DOMDocument();
  $doc->loadHTML($LongformText);
  $Article = trim($doc->getElementById('article-text')->textContent);
  
  if(trim($Article->textContent)==''){
    $Divs = $doc->getElementsByTagName('div');
    foreach($Divs as $Div){
      $Class = $Div->getAttribute('class');
      if(!(strpos($Class,'article-text')===false)){
        $Article = $Div->textContent;
      }
    }
  }

  //Clean up article text
  
  if($Article==''){
    mail('chris.j.trowbridge@gmail.com','IDK HOW TO PARSE THIS','<a href="view-source:'.$_REQUEST['LongformURL'].'">'.$_REQUEST['LongformURL'].'</a>');
    die('Unable to parse');
  }

  //convert tabs to spaces
  $Article = str_replace('  ',' ',$Article);
  $Article = str_replace('	',' ',$Article);

  //remove any repeated spaces
  $StillHaveSpaces = true;
  while($StillHaveSpaces){
    $Temp = str_replace('  ',' ',$Article);
    if($Article == $Temp){
      $StillHaveSpaces = false;
    }
    $Article = $Temp;
    unset($Temp);
  }
  
  return $Article;
}
