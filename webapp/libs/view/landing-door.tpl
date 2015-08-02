
{include file="_head.tpl" suppress_navbar='true' body_class='landing-door'}

<div class="row landing-row" id="landing-header">
   <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3 class="col-xs-12">You make great things. Tell the world what you made.</h3>
    <h1 class="col-xs-12 col-sm-8">
      Makerbase
    </h1>
    <div class="col-xs-12 col-sm-4">
      <a class="btn btn-success btn-lg pull-right" href="/twittersignin/" id="join-button">sign in with twitter <i class="fa fa-arrow-right"></i></a>
      <h6 class="pull-right">it's free!</h6>
    </div>
  </div>
</div>

<div class="row">
   <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <form class="navbar-form" role="search" action="/search/" id="homepage-search">
      <div class="" id="remote-search">
        <input type="search" class="form-control input-lg typeahead" placeholder="search by maker or project..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
    </form>

  </div>
</div>


<div class="row" id="landing-featured">

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
    <h3>featured makers</h3>

    {foreach $featured_makers as $featured_maker}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/m/{$featured_maker->uid}/{$featured_maker->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$featured_maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$featured_maker->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/m/{$featured_maker->uid}/{$featured_maker->slug}">{$featured_maker->name}</a></h4>
        <p class="media-body">

          {foreach $featured_maker->products as $featured_maker_product}
            <a href="/p/{$featured_maker_product->uid}/{$featured_maker_product->slug}">
              <img src="{insert name='user_image' image_url=$featured_maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}">
              {$featured_maker_product->name}
            </a>
          {/foreach}

        </p>
      </div>
    </div>
    {/foreach}

  </div>

  <div class="col-xs-12 col-sm-5">
    <h3>featured projects</h3>

    {foreach $featured_products as $featured_product}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/p/{$featured_product->uid}/{$featured_product->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$featured_product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" alt="{$featured_product->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/p/{$featured_product->uid}/{$featured_product->slug}">{$featured_product->name}</a></h4>
        <p class="media-body">
          {foreach $featured_product->makers as $featured_product_maker}
            <a href="/m/{$featured_product_maker->uid}/{$featured_product_maker->slug}">
              <img src="{insert name='user_image' image_url=$featured_product_maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}">
              {$featured_product_maker->name}
            </a>
          {/foreach}
        </p>
      </div>
    </div>
    {/foreach}

  </div>

</div>


<div class="row" id="landing-top-contributors">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3>today&#39;s top contributors</h3>
    <ul class="list-inline">

      {foreach $featured_users as $featured_user}

      {assign var='color_num' value=1|mt_rand:9}

      {if $color_num eq '1'}{assign var='color' value='bubblegum'}
        {elseif $color_num eq '2'}{assign var='color' value='caramel'}
        {elseif $color_num eq '3'}{assign var='color' value='creamsicle'}
        {elseif $color_num eq '4'}{assign var='color' value='dijon'}
        {elseif $color_num eq '5'}{assign var='color' value='mint'}
        {elseif $color_num eq '6'}{assign var='color' value='pea'}
        {elseif $color_num eq '7'}{assign var='color' value='purple'}
        {elseif $color_num eq '8'}{assign var='color' value='salmon'}
        {elseif $color_num eq '9'}{assign var='color' value='sandalwood'}
        {elseif $color_num eq '0'}{assign var='color' value='sepia'}
        {else}{assign var='color' value='gray-lighter'}
      {/if}

      <li class="col-xs-6 col-sm-3">
        <a href="/u/{$featured_user->uid}" class="top-contributor style-{$color}">
            <img class="img-rounded avatar pull-left" src="{insert name='user_image' image_url=$featured_user->avatar_url image_proxy_sig=$image_proxy_sig type='u'}" alt="{$featured_user->name}">
            <buttton class="pull-right contrib-meet">meet <i class="fa fa-arrow-right"></i></buttton>
            <h4>{$featured_user->name}</h4>
          </a>
      </li>

      {/foreach}

    </ul>
  </div>
</div>

{include file="_actions.tpl"}

<div class="row">


    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors">
      <h1 class="col-xs-12">brought to you by sponsors that makers <em>love to use</em>.</h1>
      <p class="col-xs-12 col-sm-4">Don't take our word for it. See which of the projects that inspire you rely on these great products.</p>

      <ul class="list-inline col-sm-8">
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

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-makers">
      <h1 class="col-xs-12">makers.</h1>
      <p class="col-xs-12 col-sm-5">List projects you helped create at work and in your free time. Or find out who made the apps, websites, podcasts and other projects that inspire you every day.</p>
    </div>

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-projects">
      <h1 class="col-xs-12">projects.</h1>
      <p class="col-xs-12 col-sm-5">Makerbase is for every project you created &mdash; even the ones that didn't work out. List everyone who worked with you on projects, describe what they did, and reveal what tools you all used to get the job done.</p>
    </div>

</div>

{include file="_footer.tpl"}

    {if $is_waitlisted}
      {literal}
      <script>
        $.getJSON( "/requestinvites/{/literal}{$waitlisted_twitter_id}{literal}/", function( data ) {
          var items = [];
          $.each( data, function( key, val ) {
            items.push( "<li class='list-group-item'>" +
              "<div class='media-left media-middle'>" +
                "<img class='media-object' src='http://avatars.io/twitter/" +
                val.follower_username +
                "' alt='" +
                val.follower_username +
                "' width='40' height='40'>" +
              "</div><div class='media-body'>" +
                "<a href='https://twitter.com/share?text=Hey%20@" + val.follower_username +
                "%20can%20you%20add%20me%20to%20%40makerbase%20so%20I%20can%20add%20my%20projects%3F%20Thanks!&amp;url=https://makerba.se/add/maker/?q=%2540{/literal}{$waitlisted_username}{literal}'" +
                "class='btn btn-primary btn-lg' onclick=\"javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\">" +
                "Ask @" + val.follower_username +
               "</a>" +
              "</div>"+
              "</li>" );
          });

          $( "<ul/>", {
            "class": "list-group",
            html: items.join( "" )
          }).appendTo( "#beg_invites" );
        });
      </script>
      {/literal}
    {/if}
