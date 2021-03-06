<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Condensr</title>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.8/js/jquery.tablesorter.combined.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.8/css/theme.bootstrap_4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.28.8/css/theme.ice.min.css">
  
  <script src="api/v1/condensr-api-helper.js"></script>

</head>
<body>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-49854332-9', 'auto');
    ga('send', 'pageview');

  </script>
  <form onsubmit="return false;" class="form container">
    <div class="row no-gutters">
      <div class="col-xs-12">
        <br>
        <!--p><a href="javascript:(function(){m='https://condensr.io/?LongformURL='+encodeURIComponent(document.location);w=window.open(m);})();" class="btn btn-lg btn-outline-success">Condensr</a> &lt;&lt;= This is a bookmarklet! Try it on an article.</p-->
        <h1><a href="/">Condensr.io</a></h1>
        <div class="form-group">
          <label for="longform">Put some long-form text here. We will condense it to as few sentences as you would like. <a href="https://github.com/cjtrowbridge/Condensr">How does it work?</a></label>
          <textarea class="form-control" name="LongformText" id="LongformText" rows="6"><?php 
            if(isset($_REQUEST['LongformURL'])){
              $URL = 'https://condensr.io/api/v1/?LongformURL='.urlencode($_REQUEST['LongformURL']);
              echo file_get_contents($URL);
            }
          ?></textarea>
        </div>
      </div>
    </div>
    <div class="row no-gutters">
      <div class="col-xs-12 col-md-4">
        <div class="input-group">
          <span class="input-group-addon">Condense To </span>
          <input class="form-control" type="number" name="NumberOfSentences" value="1" id="NumberOfSentences">
          <span class="input-group-addon"> Sentence(s)</span>
        </div>
      </div>
      <div class="hidden-md-up">
        &nbsp;<br>
      </div>
      <div class="col-xs-12 col-md-8">
        <input type="submit" class="btn btn-success btn-block" value="Condense" id="Condense">
      </div>
      <div class="hidden-md-up">
        &nbsp;<br>
      </div>
    </div>
    
    <div class="row no-gutters">
      <div class="col-xs-12" id="Results">
        
      </div>
    </div>
    
  </form><!-- /.container -->
<script>
  $('#LongformText').focus();
  $("#Condense").click(function(){
    
    var txt = $('#LongformText').val();
    var num = $('#NumberOfSentences').val();
    
    Condensr(txt,num,function(data){
      $("#Results").html('<h2>Condensed:</h2><p><i>'+data.message+'</i></p><p>'+data.condensed+'</p>');
    });
    
  });
  <?php if(isset($_REQUEST['LongformURL'])){ ?>
  $("#Condense").click();
  <?php } ?>
</script>
</body>
</html>
