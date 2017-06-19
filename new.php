<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="<?php echo $ASTRIA['app']['favicon']; ?>">

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
<body>
  <div class="container no-gutters">
    <h1>Condensr</h1>
    <form action="" class="form" method="post">
      <div class="form-group">
        <label for="longform">Put some long-form text here</label>
        <textarea class="form-control" name="longform" id="longform" rows="6"><?php if(isset($_REQUEST['LongformText'])){echo $_REQUEST['LongformText'];}?></textarea>
      </div>
      <div class="form-group row">
        <div class="col-xs-12 form">
          <div class="col-xs-12 col-md-4">
            <div class="input-group">
              <span class="input-group-addon" id="basic-addon1">URL Instead:</span>
              <input class="form-control" type="text" name="LongformURL" value="<?php if(isset($_REQUEST['LongformURL'])){echo $_REQUEST['LongformURL'];} ?>" id="LongformURL" aria-describedby="basic-addon1">
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <div class="input-group">
              <span class="input-group-addon" id="basic-addon2">Number of Sentences:</span>
              <input class="form-control" type="number" name="numberOfSentences" value="<?php if(isset($_REQUEST['NumberOfSentences'])){echo $_REQUEST['NumberOfSentences'];}else{echo '2';}?>" id="NumberOfSentences" aria-describedby="basic-addon1">
            </div>
          </div>
          <div class="col-xs-12 col-md-4">
            <input type="submit" class="btn btn-success btn-block" value="Condense">
          </div>
        </div>
      </div>
    </form>
  </div><!-- /.container -->
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-4">
        
      </div>
    </div>
  </div>
<script>
  $('#LongformText').focus();
</script>
</body>
</html>
