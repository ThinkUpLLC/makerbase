{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 *}

<img src="{$action->metadata->avatar_url}" width="50">&nbsp;{if isset($logged_in_user) && $action->twitter_user_id eq $logged_in_user->twitter_user_id}You{else}<a href="/u/{$action->twitter_user_id}/">{$action->name}</a>{/if} {if $action->action_type eq 'create'}added{elseif $action->action_type eq 'update'}updated{/if} <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->metadata->slug}/">{$action->metadata->name}</a> <span class="badge">{$action->object_type}</span> {$action->time_performed|relative_datetime} ago
