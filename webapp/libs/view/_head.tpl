<!--
#      __  ___        __                __                          __
#     /  |/  /____ _ / /__ ___   _____ / /_   ____ _ _____ ___     / /____  _   __ ___   _____   __  __ ____   __  __
#    / /|_/ // __ `// //_// _ \ / ___// __ \ / __ `// ___// _ \   / // __ \| | / // _ \ / ___/  / / / // __ \ / / / /
#   / /  / // /_/ // ,<  /  __// /   / /_/ // /_/ /(__  )/  __/  / // /_/ /| |/ //  __/(__  )  / /_/ // /_/ // /_/ /
#  /_/  /_/ \__,_//_/|_| \___//_/   /_.___/ \__,_//____/ \___/  /_/ \____/ |___/ \___//____/   \__, / \____/ \__,_/
#                                                                                             /____/
-->
{if !isset($suppress_search)}
  {assign var='suppress_search' value=false}
{/if}

{assign var="application_url" value="https://makerbase.co"}

{include file="_reusablecopy.tpl"}

<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#" itemscope itemtype="http://schema.org/Article">
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="{"/assets/css/makerbase.css"|autoversion}">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="//use.typekit.net/lym0xhq.js"></script>
    {literal}
    <script>try{Typekit.load();}catch(e){}</script>
    {/literal}

    <meta property="og:site_name" content="Makerbase" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@makerbase" />
    <meta name="twitter:domain" content="makerbase.co" />
    <meta name="twitter:creator" content="@makerbase" />

    <link rel="shortcut icon" type="image/x-icon" href="{$site_root_path}assets/img/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$site_root_path}assets/img/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$site_root_path}assets/img/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$site_root_path}assets/img/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="{$site_root_path}assets/img/apple-touch-icon-57-precomposed.png">

    {if isset($maker) || isset($product) || isset($user)}
      {if isset($maker)}
        {if $active_tab neq 'projects'}
          {assign var="url" value="/m/{$maker->uid}/{$maker->slug}/{$active_tab}"}
        {else}
          {assign var="url" value="/m/{$maker->uid}/{$maker->slug}"}
        {/if}
        {assign var="description" value="{$maker->name|escape}'s {$active_tab}"}
        {assign var="title" value="{$maker->name|escape} on Makerbase"}
        {assign var="image" value=$maker->avatar_url}
        {assign var="type" value="profile"}
        {assign var="image_type" value='m'}
      {elseif isset($product)}
        {assign var="url" value="/p/{$product->uid}/{$product->slug}"}
        {assign var="title" value="{$product->name|escape} on Makerbase"}
        {assign var="description" value=$product->description}
        {assign var="image" value=$product->avatar_url}
        {assign var="image_type" value='p'}
        {assign var="type" value="product"}
      {elseif isset($user)}
        {assign var="url" value="/u/{$user->uid}"}
        {assign var="title" value="{$user->twitter_username|escape}'s Makerbase activity"}
        {assign var="description" value="{$user->twitter_username|escape}'s Makerbase activity"}
        {assign var="image" value=$user->avatar_url}
        {assign var="type" value="profile"}
        {assign var="image_type" value='u'}
      {/if}

      <title>{$title}</title>
      <meta property="og:url" content="{$application_url}{$url}" />
      <meta itemprop="name" content="{$title}" />
      <meta name="twitter:title" content="{$title}" />
      <meta property="og:title" content="{$title}" />
      <meta property="og:type" content="{$type}" />

      <meta itemprop="description" content="{$description}" />
      <meta name="description" content="{$description}" />
      <meta name="twitter:description" content="{$description}" />

      <meta itemprop="image" content="{$image}">
      <meta property="og:image" content="{$image}" />
      <meta property="twitter:image" content="{insert name='user_image' image_url=$image image_proxy_sig=$image_proxy_sig type=$image_type}" />

      <meta property="og:image:type" content="image/jpg">

      <link rel="canonical" href="{$application_url}{$url}" />
    {else}
      {if !isset($title)}
        {assign var="title" value="{$app_title}"}
      {/if}
      {if isset($url)}
        {assign var="url" value="{$url}"}
      {else}
        {assign var="url" value="{$application_url}"}
      {/if}
      {if isset($description)}
        {assign var="description" value="{$description}"}
      {else}
        {assign var="description" value="A directory of people who make things."}
      {/if}

      <title>{$title}</title>
      <meta property="og:url" content="{$url}" />
      <meta property="og:type" content="website" />
      <meta itemprop="name" content="{$title}" />
      <meta name="twitter:title" content="{$title}" />
      <meta property="og:title" content="{$title}" />
      <meta itemprop="description" content="{$description}" />
      <meta name="description" content="{$description}" />
      <meta name="twitter:description" content="{$description}" />

      {assign var="image" value="https://makerbase.co/assets/img/makerbase-logo-horizontal.png"}
      {assign var="image_square" value="https://makerbase.co/assets/img/makerbase-logo.png"}
      <meta itemprop="image" content="{$image}">
      <meta property="og:image" content="{$image}" />
      <meta property="twitter:image" content="{$image}" />
      <meta property="og:image:secure" content="{$image}" />
      <meta name="twitter:image:src" content="{$image_square}" />
      <meta name="twitter:image:width" content="300" />

      <link rel="canonical" href="{$url}" />
    {/if}

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body {if isset($body_class)}class="{$body_class}"{/if}>


  {if (isset($suppress_navbar) && $suppress_navbar eq false) || !(isset($suppress_navbar))}
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="row">
          <div class="col-xs-2 col-sm-1">
            <a class="navbar-brand" href="{$site_root_path}">{$app_title}</a>
          </div>
          <div class=" col-xs-8 col-sm-10">
            {if $suppress_search neq true}
            <form class="navbar-form" role="search" action="/search/">
              <div class="" id="remote-search">
                <input type="search" class="form-control typeahead" placeholder="Search for..." name="q" autocomplete="off" id="nav-typeahead">
              </div>
            </form>
            {/if}
          </div>
          {if isset($logged_in_user)}
            {if $logged_in_user->is_admin}
              {include file="_adminbar.tpl"}
            {else}
            <div class=" col-xs-2 col-sm-1" role="signout" id="signout-button">
              <button type="button" class="btn btn-default btn-sm navbar-btn dropdown-toggle pull-right" data-toggle="dropdown" aria-expanded="false" id="user-menu">
                <img src="{insert name='user_image' image_url=$logged_in_user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="{$site_root_path}u/{$logged_in_user->uid}">Your account</a></li>
                <li><a href="{$site_root_path}signout/">Sign out</a></li>
              </ul>
            </div>
            {/if}
          {else}
            <div class=" col-xs-2 col-sm-1" role="signout" id="signout-button">
              {if isset($sign_in_with_twttr_link)}<a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-sm navbar-btn" id="signin-button" rel="nofollow"><i class="fa fa-twitter"></i> Sign in</a>{/if}
            </div>
          {/if}
        </div>
      </div>
    </nav>
  {/if}

{include file="_usermessage.tpl"}

    <div class="container">
