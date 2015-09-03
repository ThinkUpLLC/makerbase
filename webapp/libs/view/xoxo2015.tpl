
{include file="_head.tpl" body_class='landing'}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="jumbotron style-pea">
      <h3>
        XOXO Festival 2015 Speakers<br />
        <small>Meet the makers slated to speak at <a href="http://2015.xoxofest.com/">XOXO Festival in Portland, Oregon</a>, September 2015.</small>
      </h3>
    {if !isset($makers_col1) && !isset($makers_col2) }
      {if isset($sign_in_with_twttr_link)}
          <a class="btn btn-primary" href="{$sign_in_with_twttr_link}">Sign in to add yourself</a>
      {else}
          <a class="btn btn-primary" href="/gotoxoxo/">I'm going!</a>
      {/if}
    {else}
      You're going! Make sure you <a href="#">list all your projects</a>.
    {/if}
    </div>
  </div>
</div>

<div class="row" id="landing-featured">

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
              {$maker_product->name|escape}
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
                  {$maker_product->name|escape}
                </a>
              {/foreach}
              {/if}
            </p>
          </div>
        </div>
        {/foreach}
    </div>
</div>

<!--ATTENDEES-->
{if isset($makers_col1) && isset($makers_col2)}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="jumbotron style-pea">
      <h3>
        Attendees
      </h3>
    </div>
  </div>
</div>

<div class="row" id="landing-featured">

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
              {$maker_product->name|escape}
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
                  {$maker_product->name|escape}
                </a>
              {/foreach}
              {/if}
            </p>
          </div>
        </div>
        {/foreach}
    </div>
</div>
{/if}

{include file="_footer.tpl"}
