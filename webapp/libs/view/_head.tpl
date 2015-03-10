<!DOCTYPE html>
<html lang="en" lang="en" prefix="og: http://ogp.me/ns#" itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$app_title}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{$site_root_path}assets/css/makerbase.css">
    <link rel="stylesheet" href="{$site_root_path}assets/css/vendor/bootstrap-datepicker3.min.css">
    <script src="//use.typekit.net/xzh8ady.js"></script>
    {literal}
    <script>try{Typekit.load();}catch(e){}</script>
    {/literal}

    <style type="text/css">
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
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="user-menu">
              <img src="{$logged_in_user->avatar_url}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="{$site_root_path}u/{$logged_in_user->twitter_user_id}">Your activity</a></li>
              <li><a href="{$site_root_path}signout/">Sign out</a></li>
            </ul>
          </div>
          </form>
          {else}
          <form class="navbar-form navbar-right" role="signin">
            {if isset($sign_in_with_twttr_link)}<a href="{$sign_in_with_twttr_link}"><img src="/assets/img/sign-in-with-twitter-gray.png"></a>{/if}
          </form>
          {/if}

          <form class="navbar-form col-xs-offset-2" role="search" action="/search/">
            <div class="" id="remote-search">
              <div class="input-group col-xs-6">
                <input type="search" class="form-control typeahead" placeholder="Search for..." name="q" autocomplete="off" id="nav-typeahead">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit">Go</button>
                </span>
              </div><!-- /input-group -->
            </div>

           </form>

        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

{include file="_usermessage.tpl"}
