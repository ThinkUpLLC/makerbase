{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 *}

{* Who is performing the action *}
{if isset($logged_in_user) && $action->user_uid eq $logged_in_user->uid}
    {assign var="actor" value="You"}
{else}
    {assign var="actor" value=null}
{/if}

{* Set maker and product vars depending on structure of metadata *}
{if isset($action->metadata->avatar_url)}
    {assign var="avatar_url" value=$action->metadata->avatar_url}
    {if $action->object_type eq 'Maker'}
        {assign var="maker_slug" value=$action->metadata->slug}
        {assign var="maker_uid" value=$action->metadata->uid}
        {assign var="maker_name" value=$action->metadata->name}
    {elseif $action->object_type eq 'Product'}
        {assign var="product_slug" value=$action->metadata->slug}
        {assign var="product_name" value=$action->metadata->name}
        {assign var="product_uid" value=$action->metadata->uid}
    {/if}
{/if}
{if isset($action->metadata->after->avatar_url)}
    {assign var="avatar_url" value=$action->metadata->after->avatar_url}
    {if $action->object_type eq 'Maker'}
        {assign var="maker_slug" value=$action->metadata->after->slug}
        {assign var="maker_name" value=$action->metadata->after->name}
        {assign var="maker_uid" value=$action->metadata->after->uid}
    {elseif $action->object_type eq 'Product'}
        {assign var="product_slug" value=$action->metadata->after->slug}
        {assign var="product_uid" value=$action->metadata->after->uid}
        {assign var="product_name" value=$action->metadata->after->name}
    {/if}
{/if}
{if isset($action->metadata->maker->avatar_url)}
    {assign var="maker_avatar_url" value=$action->metadata->maker->avatar_url}
    {assign var="maker_slug" value=$action->metadata->maker->slug}
    {assign var="maker_name" value=$action->metadata->maker->name}
    {assign var="maker_uid" value=$action->metadata->maker->uid}
{/if}
{if isset($action->metadata->product->avatar_url)}
    {assign var="product_avatar_url" value=$action->metadata->product->avatar_url}
    {assign var="product_slug" value=$action->metadata->product->slug}
    {assign var="product_uid" value=$action->metadata->product->uid}
    {assign var="product_name" value=$action->metadata->product->name}
{/if}
{if isset($action->metadata->after->maker->avatar_url)}
    {assign var="maker_avatar_url" value=$action->metadata->after->maker->avatar_url}
    {assign var="maker_slug" value=$action->metadata->after->maker->slug}
    {assign var="maker_name" value=$action->metadata->after->maker->name}
    {assign var="maker_uid" value=$action->metadata->after->maker->uid}
{/if}
{if isset($action->metadata->after->product->avatar_url)}
    {assign var="product_avatar_url" value=$action->metadata->after->product->avatar_url}
    {assign var="product_slug" value=$action->metadata->after->product->slug}
    {assign var="product_uid" value=$action->metadata->after->product->uid}
    {assign var="product_name" value=$action->metadata->after->product->name}
{/if}


<div class="action-item row">
	<div class="col-xs-2 col-sm-2">
		{if isset($maker_avatar_url)}<a href="/m/{$maker_uid}/{$maker_slug}"><img src="{$maker_avatar_url}" class="img-responsive" alt="{$maker_name}"></a>{else}<img src="{$avatar_url}" class="img-responsive" alt="">{/if}
	</div>

    <div class="col-xs-2 col-sm-2">
        {if isset($product_avatar_url)}<a href="/p/{$product_uid}/{$product_slug}"><img src="{$product_avatar_url}" class="img-responsive"></a>{/if}
    </div>

	<div class="col-xs-8 col-sm-8">
		{if $actor neq 'You'}<a href="/u/{$action->user_uid}">{$action->name}</a>{else}{$actor}{/if} {if $action->action_type eq 'update'}<a data-toggle="popover" data-html="true" data-content="{include file='_diff.tpl'}" tabindex="0" data-trigger="focus" >{/if}{$action->action_type}d{if $action->action_type eq 'update'}</a>{/if} <a href="/{if $action->object_type eq 'Maker'}m/{$maker_uid}/{$maker_slug}">{$maker_name}{else}p/{$product_uid}/{$product_slug}">{$product_name}{/if}</a>{if isset($action->object2_id)} {if $action->action_type eq 'associate'}with{else}on{/if} <a href="/p/{$product_uid}/{$product_slug}">{$product_name}</a>{/if}

		<span><small class="text-muted">{$action->time_performed|relative_datetime} ago</small></span>
	</div>
</div>


{assign var="actor" value=null}
{assign var="product_avatar_url" value=null}
{assign var="product_slug" value=null}
{assign var="product_name" value=null}
{assign var="maker_avatar_url" value=null}
{assign var="maker_slug" value=null}
{assign var="maker_name" value=null}
