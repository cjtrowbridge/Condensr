<?php 

header('Access-Control-Allow-Origin: *');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


if(
  isset($_REQUEST['LongformURL'])&&
  (!(trim($_REQUEST['LongformURL'])==''))
){

  include('CacheURL.php');
  $LongformText=CacheURL($_REQUEST['LongformURL']);
  
  if($LongformText==false){
    die('<p>Unable to fetch URL.</p>');
  }
  
  $Article=strip_tags($LongformText);
  
  /*
  
  //Get article text only
  $doc = new DOMDocument();
  $doc->loadHTML($LongformText);
  $Article = trim($doc->getElementById('article-text')->textContent);
  
  if(trim($Article->textContent)==''){
    $Article = trim($doc->getElementsByTagName('body')[0]->textContent);
    var_dump($Article);
    exit;
  }
  */

  //Clean up article text
  
  if($Article==''){
    mail('chris.j.trowbridge@gmail.com','IDK HOW TO PARSE THIS','<a href="view-source:'.$_REQUEST['LongformURL'].'">'.$_REQUEST['LongformURL'].'</a>');
    die('Unable to parse');
  }

  //convert tabs to spaces
  $Article = str_replace('  ',' ',$Article);

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

  if(isset($_REQUEST['NumberOfSentences'])){
    $NumberOfSentences = intval($_REQUEST['NumberOfSentences']);
    echo Condense($LongformText,$NumberOfSentences);
  }else{
    echo $Article;
  }
  exit;
  
}elseif(isset($_REQUEST['LongformText'])){
  
  if(
    (!(isset($_REQUEST['NumberOfSentences'])))||
    (intval($_REQUEST['NumberOfSentences'])==0)
  ){
    $_REQUEST['NumberOfSentences']=1;
  }
  
  $Start = microtime(true);
  $Output = CacheCondensr($_REQUEST['LongformText'],$_REQUEST['NumberOfSentences']);
  $End = microtime(true);
  $Output['message'].=' in '.round($End-$Start,4).' seconds.';
  
  header("Content-Type: application/json;charset=utf-8");
  $Output=json_encode($Output,JSON_PRETTY_PRINT);
  if($Output==false){
    $Output = array('error'=>'There was a problem with the data.');
  }
  echo $Output;
  
}else{
  //TODO
  include('about.html');
  exit;
}

function CacheCondensr($Text,$NumberOfSentences = 1){
  
  $NumberOfSentences=intval($NumberOfSentences);
  if($NumberOfSentences==0){die('Invalid NumberOfSentences.');}
  if($NumberOfSentences>100){die('NumberOfSentences has a maximum of 100');}
  
  $Path = 'cache/'.$NumberOfSentences.'.'.md5($Text).'.json';
  
  if(file_exists($Path)){
    return array(
      'message'   => 'Fetched From Cache',
      'condensed' => file_get_contents($Path)
    );
  }
  
  $Output = Condense($Text,$NumberOfSentences);
  file_put_contents($Path,$Output);
  return array(
      'message'   => 'Condensed',
      'condensed' => $Output
    );
}

function Condense($Text,$NumberOfSentences = 1){
  //Clean Up The Text
  $CleanText = CleanUp($Text);

  //Score Words
  $Scores = GetWordScores($CleanText);

  //Sort  Word Scores
  SortByScore($Scores, 'Score');

  //Parse Sentences. Punctuation is irrelevant for the purposes of sorting.
  $RawSentences = $Text;
  
  $RawSentences = str_replace('?','?||',$RawSentences);
  $RawSentences = str_replace('!','!||',$RawSentences);
  $RawSentences = str_replace('.','.||',$RawSentences);
  $RawSentences = str_replace(PHP_EOL,"||",$RawSentences);
  
  $RawSentences = explode('||',$RawSentences);
  $Sentences = array();
  foreach($RawSentences as $RawSentence){
    $CleanSentence = CleanUp($RawSentence);

    $ThisSentenceScore = 0;

    $SentenceScores = GetWordScores($CleanSentence);
    foreach($SentenceScores as $SentenceScore){
      $ThisSentenceScore += $SentenceScore['Score'] * FindScore($SentenceScore['Word'],$Scores);
    }

    $Sentences[] = array(
      'Raw'   => $RawSentence,
      'Clean' => $CleanSentence,
      'Score' => $ThisSentenceScore
    );
  }

  SortByScore($Sentences);

  $Output='';
  $NumberOfSentences-=1;
  for($i = 0; $i <= $NumberOfSentences; $i++){
   $Output = trim($Output).' '.$Sentences[$i]['Raw'];
  }

  return $Output;
  //return $Sentences[0]['Raw'];
}

function CleanUp($Text){
  $CleanText = strtolower($Text);
  $CleanText = str_replace("'","",$CleanText);
  $CleanText = str_replace('"','',$CleanText);
  $CleanText = trim($CleanText);
  return $CleanText;
}

function FindScore($Word,$Scores){
  foreach($Scores as $Score){
    if($Score['Word']==$Word){
      return $Score['Score'];
    }
  }
  return 0;
}

function GetWordScores($Text){
  $WordScores = array_count_values(str_word_count($Text, 1));

  $Scores  = array();
  $Ignore=array('a','the','s','and','he','she','said','his','hers','with','in','is','of','that','have','not','on','to','be','it','like','only','was','from','more','many','so','who','also','would','an','at','doesn','t','i','for','think','be','function','var','com','if','in','has','been','or','are','you','this','as','these','your','my');

  foreach($WordScores as $Word => $Score){
    if(!(
      in_array($Word,$Ignore)||
      strlen($Word)<=1
    )){
      $Scores[] = array(
        'Word'  => $Word,
        'Score' => $Score
      );
    }
  }
  return $Scores;
}

function SortByScore(&$arr, $col = 'Score', $dir = SORT_DESC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}

function ArrTabler($arr, $table_class = 'table tablesorter tablesorter-ice tablesorter-bootstrap', $table_id = null){
  $return='';
  if($table_id==null){
    $table_id=md5(uniqid(true));
  }
  if(count($arr)>0){
    $return.="\n<div class=\"table-responsive\">\n";
    $return.= "\r\n".'	<table id="'.$table_id.'" class=" table'.$table_class.'">'."\n";
    $first=true;
    foreach($arr as $row){
      if($first){
        $return.= "		<thead>\n";
        $return.= "			<tr>\n";
        foreach($row as $key => $value){
          $return.= "				<th>".ucwords($key)."</th>\n";
        }
        $return.= "			</tr>\n";
        $return.= "		</thead>\n";
        $return.= "		<tbody>\n";
      }
      $first=false;
      $return.= "			<tr>\n";
      foreach($row as $key => $value){
        $return.="<td>".$value."</td>";
      }
      $return.= "			</tr>\n";
    }
    $return.= "		</tbody>\n";
    $return.= "	</table>\n";
    $return.= "</div>\n";
    $return.= "<script>$('#".$table_id."').tablesorter({widgets: ['zebra', 'filter']});</script>\n";
  }else{
    $return.="No Results Found.";
  }
  return $return;
}

function pd($Var){
  echo '<pre>';
  var_dump($Var);
  echo '</pre>';
}


