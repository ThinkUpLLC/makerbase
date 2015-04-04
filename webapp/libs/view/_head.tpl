{if !isset($suppress_search)}
  {assign var='suppress_search' value=false}
{/if}

<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#" itemscope itemtype="http://schema.org/Article">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{if isset($maker)}{$maker->name} on {elseif isset($product)}{$product->name} on {/if}{$app_title}</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
      <div class="container col-xs-12 col-sm-12">


        <div class="navbar-header col-xs-12">

          <a class="navbar-brand col-xs-5 col-sm-4" href="{$site_root_path}">{$app_title}</a>

          {if $suppress_search neq true}
          <form class="navbar-form col-xs-6 col-sm-6" role="search" action="/search/">
            <div class="" id="remote-search">
              <div class="input-group">
              <input type="search" class="form-control typeahead col-xs-6" placeholder="Search for..." name="q" autocomplete="off" id="nav-typeahead">
              </div>
            </div>
           </form>
           {/if}



        {if isset($logged_in_user)}
        <div class="col-xs-1 col-sm-1 pull-right" role="signout" id="signout-button">
          <div class="btn-group">
            <button type="button" class="btn btn-default btn-sm navbar-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="user-menu">
              <img src="{$logged_in_user->avatar_url}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="{$site_root_path}u/{$logged_in_user->uid}">Your activity</a></li>
              <li><a href="{$site_root_path}signout/">Sign out</a></li>
            </ul>
          </div>
        </div>
        {else}
        <div class="col-xs-2 col-sm-1 pull-right" role="signin" id="signin-button">
          {if isset($sign_in_with_twttr_link)}<a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-sm navbar-btn" id="signin-button"><i class="fa fa-twitter"></i> Sign in</a>{/if}
        </div>
        {/if}

        </div>


      </div>
    </nav>

    <div class="container">

{include file="_usermessage.tpl"}
