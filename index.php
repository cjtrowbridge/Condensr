<?php

function Condense($Text,$NumberOfSentences = 1){
  //Clean Up The Text
  $CleanText = CleanUp($Text);

  //Score Words
  $Scores = GetWordScores($CleanText);

  //Score Words
  SortByScore($Scores, 'Score');

  //Parse Sentences. Punctuation is irrelevant for the purposes of this algorithm.
  $RawSentences = $Text;
  $RawSentences = str_replace('?','.',$RawSentences);
  $RawSentences = str_replace('!','.',$RawSentences);
  $RawSentences = explode('.',$RawSentences);
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
  $Ignore=array('a','the','s','and','he','she','said','his','hers','with','in','is','of','that','have','not','on','to','be','it','like','only','was','from','more','many','so','who','also','would','an','at','doesn','t','i','for','think','be','function','var','com','if','in','has','been','or','are','you');

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

function pd($Var){
  echo '<pre>';
  var_dump($Var);
  echo '</pre>';
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="<?php echo $ASTRIA['app']['favicon']; ?>">

  <title>Condense</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
</head>
<body>
  <div class="container no-gutters">
    <h1>Condense</h1>
    <form action="" class="form" method="post">
      <div class="form-group">
        <label for="longform">Put some long-form text here</label>
        <textarea class="form-control" name="longform" id="longform" rows="6"><?php
          if(isset($_POST['longform'])){
            echo $_POST['longform'];
          }
        ?></textarea>
      </div>
      <div class="form-group row">
        <div class="col-xs-12 form-inline">
          Number of Sentences:
          <input class="form-control" type="number" name="numberOfSentences" value="<?php if(isset($_POST['numberOfSentences'])){echo $_POST['numberOfSentences'];} ?>" id="numberOfSentences">
          <input type="submit" class="btn btn-success float-right" value="Condense">
        </div>
      </div>
    </form>
  </div><!-- /.container -->
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-4">
        <?php
          if(isset($_POST['longform'])){
            pd(GetWordScores(CleanUp($_POST['longform'])));
          }
        ?>
      </div>
      <div class="col-xs-12 col-md-8">
        <?php
          if(isset($_POST['longform'])){
            echo Condense($_POST['longform'],$_POST['numberOfSentences']);
          }
        ?>
      </div>
    </div>
  </div>
<script>
  $('#longform').focus();
</script>
</body>
</html>
