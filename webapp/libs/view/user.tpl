{include file="_head.tpl"}

<h1>User {$user->name}</h1>

<div class="row">
  <div class="col-xs-2">
    <img class="img-responsive" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$user->name}" width="100%">

	<p><a href="{$user->url}">{$user->url}</a></p>
  </div>
  <div class="col-xs-10">
  	<ul class="list-group">
    {foreach $actions as $action}
        <li class="list-group-item">
        {$action->name} {if $action->action_type eq 'create'}added{elseif $action->action_type eq 'update'}updated{/if} <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->object_slug}/">{$action->object_name}</a> <span class="badge">{$action->object_type}</span> {$action->time_performed|relative_datetime} ago
        </li>
    {/foreach}
	</ul>
  </div>
</div>

{include file="_footer.tpl"}