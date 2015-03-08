{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 *}
{if isset($action->metadata->avatar_url)}
    {assign var="avatar_url" value=$action->metadata->avatar_url}
{elseif isset($action->metadata->maker->avatar_url)}
    {assign var="avatar_url" value=$action->metadata->maker->avatar_url}
{/if}
{if isset($action->metadata->product->avatar_url)}
    {assign var="product_avatar_url" value=$action->metadata->product->avatar_url}
{/if}

<img src="{$avatar_url}" width="50">{if isset($product_avatar_url)}<img src="{$product_avatar_url}" width="50">{/if}&nbsp;{if isset($logged_in_user) && $action->twitter_user_id eq $logged_in_user->twitter_user_id}You{else}<a href="/u/{$action->twitter_user_id}/">{$action->name}</a>{/if} {if $action->action_type neq 'associate'}{if $action->action_type eq 'create'}added{elseif $action->action_type eq 'update'}updated{/if} <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->metadata->slug}/">{$action->metadata->name}</a>{else}added <a href="/m/{$action->metadata->maker->slug}/">{$action->metadata->maker->name}</a> to <a href="/p/{$action->metadata->product->slug}/">{$action->metadata->product->name}</a>{/if} <small class="text-muted">{$action->time_performed|relative_datetime} ago</small>
