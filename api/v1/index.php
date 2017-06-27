<?php 

header('Access-Control-Allow-Origin: *');

/* This will come later. Is not necessary for initial release and is nontrivial.
if(
  isset($_REQUEST['LongformURL'])&&
  (!(trim($_REQUEST['LongformURL'])==''))
){

  $LongformText=file_get_contents($_REQUEST['LongformURL']);
  if($LongformText==false){
    echo '<p>Invalid URL</p>';
  }else{
    if(isset($_REQUEST['NumberOfSentences'])){
      $NumberOfSentences = $_REQUEST['NumberOfSentences'];
    }else{
      $NumberOfSentences = 1;
    }

    //Clean up the contents of the page before condensing
    //TODO get only the text in a better way that this
    $LongformText = strip_tags($LongformText);

    echo Condense($LongformText,$NumberOfSentences);
  }

}else*/if(isset($_REQUEST['LongformText'])){
  
  if(
    (!(isset($_REQUEST['NumberOfSentences'])))||
    (intval($_REQUEST['NumberOfSentences'])==0)
  ){
    $_REQUEST['NumberOfSentences']=1;
  }
  
  echo Condense($_REQUEST['LongformText'],$_REQUEST['NumberOfSentences']);

}else{
  //TODO
  echo 'Welcome and here are some instructions...';
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
