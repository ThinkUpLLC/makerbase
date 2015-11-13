
{include file="_head.tpl" body_class='landing-not-signed-in'
title="GitHub Universe 2015 on Makerbase"
url="https://makerbase.co/universe"
description="Meet the makers of GitHub Universe 2015."
}

{if isset($makers_col1) && isset($makers_col2)}
  {assign var="is_going" value=true}
{else}
  {assign var="is_going" value=false}
{/if}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class=" style-seabreeze" style="padding: 2em; margin-top: 1em;">
      <h1 style="margin-top: 0;">
        <a href="http://githubuniverse.com/"><strong>GitHub Universe</strong></a>: Meet your makers
      </h1>
      {if !$is_going}
        {if isset($sign_in_with_twttr_link)}
          <p style="padding-bottom: 1em;"><strong>Makerbase is like IMDb for apps, websites, games, and podcasts.</strong><br/>
          Add projects you've made and discover what the GitHub Universe community has created.</p>
            <a class="btn btn-lg btn-default" href="{$sign_in_with_twttr_link}"><i class="fa fa-twitter"></i> Sign in to add yourself</a>
        {else}
          <p style="padding-bottom: 1em;"><strong>Are you going?</strong> Add yourself to the attendee list.</p>
            <a class="btn btn-lg btn-default" href="/gotoghuniverse/">I'm going!</a>
        {/if}
      {else}
        <p>This unofficial <a href="http://githubuniverse.com/">GitHub Universe</a> attendee directory is an attendee-generated work-in-progress, edited by the community.</p>
      {/if}
    </div>
  </div>
</div>

<div class="row" id="landing-featured">

  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <a name="speakers" href="#speakers"><h3>Speakers</h3></a>
  </div>

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
  <p>Thursday, October 1st</p>
    {foreach $speakers_col1 as $maker}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/m/{$maker->uid}/{$maker->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name|escape}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/m/{$maker->uid}/{$maker->slug}">{$maker->name|escape}</a></h4>
        <p class="media-body">

        {if isset($maker->products)}
          {foreach $maker->products as $maker_product}
            <a href="/p/{$maker_product->uid}/{$maker_product->slug}">
              <img src="{insert name='user_image' image_url=$maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">
              {$maker_product->name|truncate:20|escape}
            </a>
          {/foreach}
          {/if}
        </p>
      </div>
    </div>
    {/foreach}
    </div>

    <div class="col-xs-12 col-sm-5">
      <p>Friday, October 2nd</p>

        {foreach $speakers_col2 as $maker}
        <div class="media row">
          <div class="media-left media-top col-xs-3">
            <a href="/m/{$maker->uid}/{$maker->slug}" class="avatar">
              <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name|escape}">
            </a>
          </div>
          <div class="col-xs-9">
            <h4 class="media-heading"><a href="/m/{$maker->uid}/{$maker->slug}">{$maker->name|escape}</a></h4>
            <p class="media-body">

            {if isset($maker->products)}
              {foreach $maker->products as $maker_product}
                <a href="/p/{$maker_product->uid}/{$maker_product->slug}">
                  <img src="{insert name='user_image' image_url=$maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">
                  {$maker_product->name|truncate:20|escape}
                </a>
              {/foreach}
              {/if}
            </p>
          </div>
        </div>
        {/foreach}
    </div>
</div>

<div class="row" id="landing-featured">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <a name="attendees" href="#attendees"><h3>Attendees</h3></a>
    {if $is_going}
      <p class="text-muted"><span class="fa fa-certificate"></span> You're going! Everybody wants to know what you made. <a href="/m/{$users_maker->uid}/{$users_maker->slug}">Update your project list.</a></p>
      {/if}
  </div>

{if isset($sign_in_with_twttr_link) || !$is_going}

<div class="row" style="padding-top: 15px;">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    {if isset($sign_in_with_twttr_link)}
    <a class="btn btn-lg btn-primary" href="{$sign_in_with_twttr_link}">Sign in with Twitter</a>
    {else}
    <a class="btn btn-lg btn-primary" href="/gotoghuniverse/">Get on the list</a>
    {/if}
    <p style="padding-top:1em;">Everything on Makerbase is public.</p>
  </div>
{/if}

</div>

<!--ATTENDEES-->
<div class="row" id="landing-featured">

{if $is_going}

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
    {foreach $makers_col1 as $maker}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/m/{$maker->uid}/{$maker->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name|escape}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/m/{$maker->uid}/{$maker->slug}">{$maker->name|escape}</a></h4>
        <p class="media-body">

        {if isset($maker->products)}
          {foreach $maker->products as $maker_product}
            <a href="/p/{$maker_product->uid}/{$maker_product->slug}">
              <img src="{insert name='user_image' image_url=$maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">
              {$maker_product->name|truncate:20|escape}
            </a>
          {/foreach}
          {/if}
        </p>
      </div>
    </div>
    {/foreach}
    </div>

    <div class="col-xs-12 col-sm-5">
        {foreach $makers_col2 as $maker}
        <div class="media row">
          <div class="media-left media-top col-xs-3">
            <a href="/m/{$maker->uid}/{$maker->slug}" class="avatar">
              <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name|escape}">
            </a>
          </div>
          <div class="col-xs-9">
            <h4 class="media-heading"><a href="/m/{$maker->uid}/{$maker->slug}">{$maker->name|escape}</a></h4>
            <p class="media-body">

            {if isset($maker->products)}
              {foreach $maker->products as $maker_product}
                <a href="/p/{$maker_product->uid}/{$maker_product->slug}">
                  <img src="{insert name='user_image' image_url=$maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">
                  {$maker_product->name|truncate:20|escape}
                </a>
              {/foreach}
              {/if}
            </p>
          </div>
        </div>
        {/foreach}
    </div>
{/if}
  </div>

<div class="row" id="landing-featured">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <p><a href="http://githubuniverse.com/" style="text-decoration:underline">GitHub Universe</a> is two full days on how to build, collaborate, and deploy great software. This unofficial GitHub Universe directory is a work-in-progress listing of projects made by attendees, edited by the community.</p>

  </div>

</div>


<div class="row" id="promo-boxes">

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors" style="min-height: 75px;">
      <h3 class="col-xs-12">brought to you by sponsors that makers <em>really love</em>.</h3>
      <p class="col-xs-12 col-sm-2"><small>See who's using these great services:</small></p>

      <ul class="list-inline col-sm-10">
        <li class="col-xs-12 col-sm-4">
          <a href="/p/9u0s6y/mailchimp" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-mailchimp.jpg" alt="MailChimp">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/m348b6/slackhq" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-slack.png" alt="Slack">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/7p97ga/hover" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-hover.png" alt="Hover">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
      </ul>

    </div>
</div>

{include file="_footer.tpl"}
