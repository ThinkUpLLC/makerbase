
{include file="_head.tpl" body_class='landing-not-signed-in'
title="XOXO Festival 2015"
url="https://makerba.se/xoxo2015"
description="Meet the makers of XOXO Festival 2015."
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
        <a href="http://2015.xoxofest.com/"><strong>XOXO</strong></a>: Meet your makers
      </h1>
      {if !$is_going}
        {if isset($sign_in_with_twttr_link)}
          <p style="padding-bottom: 1em;"><strong>Makerbase is like IMDb for apps, websites, games, and podcasts.</strong><br/>
          Add projects you've made and discover what the XOXO community has created.</p>
            <a class="btn btn-lg btn-default" href="{$sign_in_with_twttr_link}"><i class="fa fa-twitter"></i> Sign in to add yourself</a>
        {else}
          <p style="padding-bottom: 1em;"><strong>Are you going?</strong> Add yourself to the attendee list.</p>
            <a class="btn btn-lg btn-default" href="/gotoxoxo/">I'm going!</a>
            <p style="padding-top: 1em;">Only XOXO attendees can see the list. Everything else on Makerbase is public.</p>
        {/if}
      {else}
        <p>This unofficial <a href="http://2015.xoxofest.com/">XOXO 2015</a> attendee directory is an incomplete work-in-progress, edited by the community. See you in Portland!</p>
      {/if}
    </div>
  </div>
</div>

<div class="row" id="landing-featured">

  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <a name="speakers" href="#speakers"><h3>Speakers</h3>
  </div>

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
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

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
    <div class="media row">
      <div class="media-left media-top col-xs-3 blur">
        <a href="/m/psw2ud/anildash" class="avatar">
          <img class="media-object img-responsive img-rounded" src="https://makerba.se/img.php?url=http://pbs.twimg.com/profile_images/529664614863101952/yBQgCUMW.png&amp;t=m&amp;s=d324372b5018ab44536ea0dc260a89d0" alt="Anil Dash">
        </a>
      </div>
      <div class="col-xs-9 blur">
        <h4 class="media-heading"><a href="/m/psw2ud/anildash">Anil Dash</a></h4>
        <p class="media-body">

                              <a href="/p/n8de86/dashescom">
              <img src="https://makerba.se/img.php?url=https://yt3.ggpht.com/-i80kuwCUg_U/AAAAAAAAAAI/AAAAAAAAAAA/79-NVn3SFG8/s900-c-k-no/photo.jpg&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              dashes.com
            </a>
                      <a href="/p/1xn85d/thinkup">
              <img src="https://makerba.se/img.php?url=https://www.thinkup.com/join/assets/img/landing/crowd.png&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              ThinkUp
            </a>
                      <a href="/p/m96udz/makerbase">
              <img src="https://makerba.se/img.php?url=https://pbs.twimg.com/profile_images/619510887020281857/DBR4fDc9.png&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              Makerbase
            </a>
                            </p>
      </div>
    </div>
  </div>

  <div class="col-xs-12 col-sm-5">
    <div class="media row">
      <div class="media-left media-top col-xs-3 blur">
        <a href="/m/583wg3/ginatrapani" class="avatar">
          <img class="media-object img-responsive img-rounded" src="https://makerba.se/img.php?url=http://pbs.twimg.com/profile_images/550825678673682432/YRqb4FJE.png&amp;t=m&amp;s=d324372b5018ab44536ea0dc260a89d0" alt="Gina Trapani">
        </a>
      </div>
      <div class="col-xs-9 blur">
        <h4 class="media-heading"><a href="/m/583wg3/ginatrapani">Gina Trapani</a></h4>
        <p class="media-body">

                                      <a href="/p/2ti9l9/todotxt">
              <img src="https://makerba.se/img.php?url=http://pbs.twimg.com/profile_images/2843613884/456d5bd0b70386b50e36399171d314dc.png&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              Todo.txt
            </a>
                          <a href="/p/1xn85d/thinkup">
              <img src="https://makerba.se/img.php?url=https://www.thinkup.com/join/assets/img/landing/crowd.png&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              ThinkUp
            </a>
                          <a href="/p/h005bf/narrowthegapp">
              <img src="https://makerba.se/img.php?url=http://narrowthegapp.com/images/narrow-the-gapp.jpg&amp;t=p&amp;s=d324372b5018ab44536ea0dc260a89d0">
              Narrow the Gapp
            </a>
                                    </p>
      </div>
    </div>
  </div>

</div>

<div class="row" style="padding-top: 15px;">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    {if isset($sign_in_with_twttr_link)}
    <a class="btn btn-lg btn-primary" href="{$sign_in_with_twttr_link}">Sign in with Twitter</a>
    {else}
    <a class="btn btn-lg btn-primary" href="/gotoxoxo/">Get on the list</a>
    {/if}
    <p style="padding-top:1em;"><strong>Only XOXO attendees can see the list.</strong> Everything else on Makerbase is public.</p>
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
    <p><a href="http://2015.xoxofest.com/" style="text-decoration:underline">XOXO</a> is an experimental festival that celebrates independent art and technology from September 10-13 in Portland, Oregon. This unofficial XOXO 2015 attendee directory is an incomplete work-in-progress, edited by the community.</p>

  </div>

</div>


<div class="row" id="promo-boxes">

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors" style="min-height: 75px;">
      <h3 class="col-xs-12">brought to you by sponsors that makers <em>really love</em>.</h3>
      <p class="col-xs-12 col-sm-2"><small>See who's using these great services:<small></p>

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
