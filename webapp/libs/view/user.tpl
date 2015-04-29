{include file="_head.tpl"}

<h1>{$user->name} makes Makerbase</h1>

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
  {if isset($next_page)}
    <a href="/u/{$user->uid}/{$next_page}">Next</a>
  {/if}
  {if isset($prev_page)}
    <a href="/u/{$user->uid}{if $prev_page neq 1}/{$prev_page}{/if}">Prev</a>
  {/if}
  </div>
</div>

{include file="_footer.tpl"}