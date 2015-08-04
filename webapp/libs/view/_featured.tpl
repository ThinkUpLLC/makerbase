
<div class="row" id="landing-featured">

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
    <h3>featured makers</h3>

    {foreach $featured_makers as $featured_maker}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/m/{$featured_maker->uid}/{$featured_maker->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{$featured_maker->avatar_url}" alt="{$featured_maker->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/m/{$featured_maker->uid}/{$featured_maker->slug}">{$featured_maker->name}</a></h4>
        <p class="media-body">

          {foreach $featured_maker->products as $featured_maker_product}
            <a href="/p/{$featured_maker_product->uid}/{$featured_maker_product->slug}">
              <img src="{$featured_maker_product->avatar_url}">
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
          <img class="media-object img-responsive img-rounded" src="{$featured_product->avatar_url}" alt="{$featured_product->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/p/{$featured_product->uid}/{$featured_product->slug}">{$featured_product->name}</a></h4>
        <p class="media-body">
          {foreach $featured_product->makers as $featured_product_maker}
            <a href="/m/{$featured_product_maker->uid}/{$featured_product_maker->slug}">
              <img src="{$featured_product_maker->avatar_url}">
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