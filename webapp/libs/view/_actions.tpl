{*
Display recent activity on a maker, project, or user page.

$actions
$object_type 'project' or 'maker' or 'user'
$object either the Product or Maker or User
*}


{if isset($actions)}

<div class="row" id="activity">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

  {if isset($friends_activity)}
    {if $friends_activity eq 'true'}
      <h3>your friends' activity <small>(see <a href="/activity/1/all">all activity</a>)</small></h3>
    {else}
      <h3>all activity <small>(see <a href="/activity/1/friends">your friends' activity</a>)</small></h3>
    {/if}
  {else}
    <h3>recent activity</h3>

  {/if}
  {if sizeof($actions) eq 0}
  <p>No {if $friends_activity eq 'true'}friend {/if}activity found!{if $friends_activity eq 'true'} Try <a href="/activity/1/all">all activity</a>.{/if}</p>
  {/if}
    {foreach $actions as $action}

      {assign var='color_num' value=($action->id|substr:-1)}
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

      {if isset($maker) || isset($product) || isset($user)}{assign var='color' value='transparent'}{/if}

      <div class="media {if isset($color)}style-{$color}{/if}">
        {include file="_action.tpl"}
      </div>
    {/foreach}

    {if isset($user)}
      {assign var='paging_path' value="/u/"|cat:$user->uid}
    {elseif isset($maker)}
      {assign var='paging_path' value="/m/"|cat:$maker->uid|cat:"/"|cat:$maker->slug}
    {elseif isset($product)}
      {assign var='paging_path' value="/p/"|cat:$product->uid|cat:"/"|cat:$product->slug}
    {else}
      {assign var='paging_path' value="/activity"}
    {/if}

    <nav id="pager">
      <ul class="list-inline">
        {if isset($next_page)}
          <li class="previous"><a href="{$paging_path}/{$next_page}/{if isset($friends_activity) && $friends_activity eq 'true'}friends{/if}{if isset($friends_activity) && $friends_activity eq 'false'}all{/if}#activity" class="btn btn-info"><span aria-hidden="true"><i class="fa fa-arrow-left"></i></span> Older</a></li>
        {/if}
        {if isset($prev_page)}
          <li class="next pull-right"><a href="{$paging_path}/{$prev_page}/{if isset($friends_activity) && $friends_activity eq 'true'}friends{/if}{if isset($friends_activity) && $friends_activity eq 'false'}all{/if}#activity" class="btn btn-info">Newer <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span></a></li>
        {/if}
      </ul>
    </nav>

  </div>
</div>
{/if}
