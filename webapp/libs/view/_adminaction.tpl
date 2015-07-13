{*
 * Display action text (for the admin bar).
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
    {elseif $action->object_type eq 'User'}
        {assign var="user_name" value=$action->metadata->name}
        {assign var="user_uid" value=$action->metadata->uid}
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

{if $actor neq 'You'}<a href="/u/{$action->user_uid}" class="user-link">{$action->username}</a>{else}{$actor}{/if} {$action->action_type|replace:'reez':'roz'} <a href="/{if $action->object_type eq 'Maker'}m/{$maker_uid}/{$maker_slug}">{$maker_name}{elseif $action->object_type eq 'Product'}p/{$product_uid}/{$product_slug}">{$product_name}{elseif $action->object_type eq 'User'}u/{$user_uid}">{$user_name}{/if}</a>{if isset($action->object2_id)} {if $action->action_type eq 'associate'}with{else}on{/if} <a href="/p/{$product_uid}/{$product_slug}">{$product_name}</a>{/if}<br /><small class="text-muted">{$action->time_performed|relative_datetime} ago</small>
{assign var="actor" value=null}
{assign var="product_avatar_url" value=null}
{assign var="product_slug" value=null}
{assign var="product_name" value=null}
{assign var="maker_avatar_url" value=null}
{assign var="maker_slug" value=null}
{assign var="maker_name" value=null}
