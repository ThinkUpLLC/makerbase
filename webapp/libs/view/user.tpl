{include file="_head.tpl"}

<h1>{$user->twitter_username} makes Makerbase</h1>

<div class="row">
  <div class="col-sm-5 col-xs-12">
    <img class="img-responsive" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$user->name}" width="100%">

	<p><a href="{$user->url}">{$user->url}</a></p>
  </div>
  <div class="col-sm-7 col-xs-12">
  	<ul class="list-group">
    {foreach $actions as $action}
        <li class="list-group-item">
        {include file="_action.tpl"}
        </li>
    {/foreach}
	</ul>

  <nav>
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