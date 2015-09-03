{**
* Display sections of featured makers, products, users.
*
* $maker_section_title Like 'featured makers'
* $product_section_title Like 'featured projects'
* $user_section_title Like 'top contributors'
* $makers Array of Maker objects with a products attribute that is an array of Products
* $products Array of Product objects with a makers attribute that is an array of Makers
* $users Array of User objects
*}
<div class="row" id="landing-featured">

  <div class="col-xs-12 col-sm-5 col-sm-offset-1">
    <h3>{$maker_section_title}</h3>

    {foreach $makers as $maker}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/m/{$maker->uid}/{$maker->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/m/{$maker->uid}/{$maker->slug}">{$maker->name}</a></h4>
        <p class="media-body">

        {if isset($maker->products)}
          {foreach $maker->products as $maker_product}
            <a href="/p/{$maker_product->uid}/{$maker_product->slug}">
              <img src="{insert name='user_image' image_url=$maker_product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}">
              {$maker_product->name}
            </a>
          {/foreach}
          {/if}
        </p>
      </div>
    </div>
    {/foreach}

  </div>

  <div class="col-xs-12 col-sm-5">
    <h3>{$product_section_title}</h3>

    {foreach $products as $product}
    <div class="media row">
      <div class="media-left media-top col-xs-3">
        <a href="/p/{$product->uid}/{$product->slug}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" alt="{$product->name}">
        </a>
      </div>
      <div class="col-xs-9">
        <h4 class="media-heading"><a href="/p/{$product->uid}/{$product->slug}">{$product->name}</a></h4>
        <p class="media-body">
          {if isset($product->makers)}
          {foreach $product->makers as $product_maker}
            <a href="/m/{$product_maker->uid}/{$product_maker->slug}">
              <img src="{insert name='user_image' image_url=$product_maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}">
              {$product_maker->name}
            </a>
          {/foreach}
          {/if}
        </p>
      </div>
    </div>
    {/foreach}

  </div>

</div>


<div class="row" id="landing-top-contributors">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3>{$users_section_title}</h3>
    <ul class="list-inline">

      {foreach $users as $user}

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
        <a href="/u/{$user->uid}" class="top-contributor style-{$color}">

            <img class="img-rounded avatar pull-left" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='u'}" alt="{$user->name}">

            <buttton class="pull-right contrib-meet">meet</buttton>
            <h4>{$user->name}</h4>
          </a>
      </li>

      {/foreach}

    </ul>
  </div>
</div>