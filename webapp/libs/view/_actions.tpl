{*
Display recent activity on a maker, project, or user page.

$actions
$object_type 'project' or 'maker' or 'user'
$object either the Product or Maker or User
*}


{if isset($actions)}
  {if sizeof($actions) > 0}

<div class="row" id="activity">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3>recent activity{if isset($object_type)}            {include file="_reportpage.tpl"  object=$object object_type=$object_type}{/if}</h3>

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
          <li class="previous"><a href="{$paging_path}/{$next_page}#activity" class="btn btn-info"><span aria-hidden="true"><i class="fa fa-arrow-left"></i></span> Older</a></li>
        {/if}
        {if isset($prev_page)}
          <li class="next pull-right"><a href="{$paging_path}/{if $prev_page neq 1}{$prev_page}{/if}#activity" class="btn btn-info">Newer <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span></a></li>
        {/if}
      </ul>
    </nav>

  </div>
</div>
{else}
<div class="row" id="activity">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3>{include file="_reportpage.tpl" object=$product object_type='project'}</h3>
  </div>
</div>
{/if}
{/if}
