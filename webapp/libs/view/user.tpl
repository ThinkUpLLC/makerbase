{include file="_head.tpl"}

<div class="row" id="maker-profile">
  <div class="col-xs-2">
    <img class="img-responsive" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$user->name}" width="100%">
  </div>
  <div class="col-xs-10">
    <h1><strong>{$user->twitter_username}</strong> makes Makerbase</h1>
    <h5><a href="{$user->url}">{$user->url}</a></h5>
  </div>
</div>

<div class="row">

  <div id="history" class="col-xs-10 col-xs-offset-1">

    <h3>Recent activity</h3>

    {if sizeof($actions) > 0}
    <ul class="list-group">
    {foreach $actions as $action}
        <li class="list-group-item col-xs-12">
        {include file="_action.tpl"}
        </li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <nav id="pager" class="col-xs-10 col-xs-offset-1">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/u/{$user->uid}/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/u/{$user->uid}{if $prev_page neq 1}/{$prev_page}{/if}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>

  </div>
</div>

{include file="_footer.tpl"}