{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 *}
{if isset($action->metadata->avatar_url)}
    {assign var="avatar_url" value=$action->metadata->avatar_url}
{/if}
{if isset($action->metadata->maker->avatar_url)}
    {assign var="maker_avatar_url" value=$action->metadata->maker->avatar_url}
{/if}
{if isset($action->metadata->product->avatar_url)}
    {assign var="product_avatar_url" value=$action->metadata->product->avatar_url}
{/if}

<div class="action-item row">
	<div class="col-xs-2">
		{if isset($maker_avatar_url)}<a href="/m/{$action->metadata->maker->slug}/"><img src="{$maker_avatar_url}" class="img-responsive"></a>{else}<img src="{$avatar_url}" class="img-responsive">{/if}
	</div>

	<div class="col-xs-8">
		{if isset($logged_in_user) && $action->twitter_user_id eq $logged_in_user->twitter_user_id}You{else}<a href="/u/{$action->twitter_user_id}/">{$action->name}</a>{/if} {if isset($action->metadata->slug)}{if $action->action_type eq 'create'}added{elseif $action->action_type eq 'update'}updated{/if} <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->metadata->slug}/">{$action->metadata->name}</a>{else}{$action->action_type}d <a href="/m/{$action->metadata->maker->slug}/">{$action->metadata->maker->name}</a> {if $action->action_type eq 'associate'}with{else}on{/if} <a href="/p/{$action->metadata->product->slug}/">{$action->metadata->product->name}</a>{/if}
		<br />
		<span><small class="text-muted">{$action->time_performed|relative_datetime} ago</small></span>
	</div>
	<div class="col-xs-2">
		{if isset($product_avatar_url)}<a href="/p/{$action->metadata->product->slug}/"><img src="{$product_avatar_url}" class="img-responsive"></a>{/if}
	</div>
</div>
