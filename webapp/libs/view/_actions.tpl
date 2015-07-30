
{if isset($actions)}
  {if sizeof($actions) > 0}

<div class="row" id="activity">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3>recent activity</h3>

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

      {if isset($maker) || isset($product)}{assign var='color' value='transparent'}{/if}

      <div class="media {if isset($color)}style-{$color}{/if}">
        {include file="_action.tpl"}
      </div>
    {/foreach}

    <nav id="pager">
      <ul class="list-inline">
        {if isset($next_page)}
          <li class="previous"><a href="/activity/{$next_page}#history" class="btn btn-info"><span aria-hidden="true"><i class="fa fa-arrow-left"></i></span> Older</a></li>
        {/if}
        {if isset($prev_page)}
          <li class="next pull-right"><a href="/{if $prev_page neq 1}activity/{$prev_page}{/if}#history" class="btn btn-info">Newer <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span></a></li>
        {/if}
      </ul>
    </nav>

  </div>
</div>
  {/if}
{/if}
