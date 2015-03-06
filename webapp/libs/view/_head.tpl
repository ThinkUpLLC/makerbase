<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Makerba.se</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <style type="text/css">
		body {
		margin-top: 50px;
		}
	</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

      <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Makerba.se</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">Home</a></li>
            <li><a href="https://github.com/ThinkUpLLC/makerbase">Source</a></li>
          </ul>
          {if isset($logged_in_user)}
          <!-- Single button -->
          <form class="navbar-form navbar-right" role="signout">
          <div class="btn-group navbar-nav">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Signed in as {$logged_in_user} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="/signout/">Sign out</a></li>
            </ul>
          </div>
          </form>
          {/if}
          <form class="navbar-form navbar-right" role="signin">
          {if isset($sign_in_with_twttr_link)}<a href="{$sign_in_with_twttr_link}"><img src="/assets/img/sign-in-with-twitter-gray.png"></a>{/if}
          </form>

          <form class="navbar-form navbar-right" role="search" action="/search.php">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search" name="q">
            </div>
            <button type="submit" class="btn btn-default">Go</button>
          </form>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">