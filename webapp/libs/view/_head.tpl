<!DOCTYPE html>
<html lang="en" lang="en" prefix="og: http://ogp.me/ns#" itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$app_title}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <style type="text/css">
      body {
      }
      .form-inline {
        padding-bottom: 20px;
      }
      .footer {
        height: 60px;
        background-color: #f5f5f5;
        padding: 20px;
        margin-top: 20px;
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

      <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{$site_root_path}">{$app_title}</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          {if isset($logged_in_user)}
          <!-- Single button -->
          <div class="nav navbar-right">

          </div>
          <form class="navbar-form navbar-right" role="signout">
          <div class="btn-group navbar-nav">
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <img src="{$logged_in_user->avatar_url}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="/signout/">Sign out</a></li>
            </ul>
          </div>
          </form>
          {else}
          <form class="navbar-form navbar-right" role="signin">
            {if isset($sign_in_with_twttr_link)}<a href="{$sign_in_with_twttr_link}"><img src="/assets/img/sign-in-with-twitter-gray.png"></a>{/if}
          </form>
          {/if}

          <form class="navbar-form col-xs-offset-2" role="search" action="/search.php">
            <div class="input-group input-group-sm col-xs-6">
              <input type="search" class="form-control" placeholder="Search" name="q">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default">Go</button>
              </span>
            </div>
          </form>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
