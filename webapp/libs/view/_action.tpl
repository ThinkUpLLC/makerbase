{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 * $admin_bar (optional)
 *}

{* Actor: Who is performing the action *}
{if isset($logged_in_user) && $action->user_uid eq $logged_in_user->uid}
    {assign var="actor" value="You"}
{else}
    {assign var="actor" value="<a href=\"/u/`$action->user_uid`\" class=\"user-link\">`$action->username`</a>"}
{/if}

{* Verb: What was the action, and is actor saying so or doing it *}
{assign var="is_says" value=false}
{assign var="fa_icon" value="fa-edit"}

{if $action->action_type eq 'freeze'} {* User froze Project/Maker *}
    {assign var="verbed" value="froze"}
    {assign var="fa_icon" value="fa-lock"}
{elseif $action->action_type eq 'unfreeze'} {* User unfroze Project/Maker *}
    {assign var="verbed" value="unfroze"}
    {assign var="fa_icon" value="fa-unlock"}
{elseif $action->action_type eq 'made with'} {* User said Project uses Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="uses"}
    {assign var="fa_icon" value="fa-wrench"}
{elseif $action->action_type eq 'not made with'} {* User said Project does not use Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="doesn't use"}
    {assign var="fa_icon" value="fa-wrench"}
{elseif $action->action_type eq 'associate'} {* User said Maker made Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="made"}
    {assign var="fa_icon" value="fa-exchange"}
{elseif $action->action_type eq 'create'} {* User added Maker/Project *}
    {assign var="verbed" value="added"}
    {assign var="fa_icon" value="fa-plus"}
{elseif $action->action_type eq 'inspire'} {* Maker inspires Maker *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="inspires"}
    {assign var="fa_icon" value="fa-bolt"}
{elseif $action->action_type eq 'hide'} {* Maker hid an inspiration *}
    {assign var="verbed" value="hid"}
    {assign var="fa_icon" value="fa-close"}
{else} {* default *}
    {assign var="verbed" value="`$action->action_type`d"}
      {if $action->action_type eq 'update'}
        {assign var="fa_icon" value="fa-edit"}
      {elseif $action->action_type eq 'archive' || $action->action_type eq 'unarchive'}
        {assign var="fa_icon" value="fa-briefcase"}
      {elseif $action->action_type eq 'delete'}
        {assign var="fa_icon" value="fa-close"}
      {/if}
{/if}

{* Object(s): What was created or changed, etc. Set object and object2 avatar_url, url, and name here *}
{if isset($action->metadata->avatar_url)}
    {assign var="object_avatar_url" value=$action->metadata->avatar_url}
    {if $action->object_type eq 'Maker'}
        {assign var="object_url" value="/m/`$action->metadata->uid`/`$action->metadata->slug`"}
        {assign var="object_name" value=$action->metadata->name}
        {assign var="object_type" value='m'}
    {elseif $action->object_type eq 'Product'}
        {assign var="object_url" value="/p/`$action->metadata->uid`/`$action->metadata->slug`"}
        {assign var="object_name" value=$action->metadata->name}
        {assign var="object_type" value='p'}
    {elseif $action->object_type eq 'User'}
        {assign var="object_name" value=$action->metadata->name}
        {assign var="object_url" value="/u/`$action->metadata->uid`"}
        {assign var="object_type" value='u'}
    {/if}
{/if}
{if isset($action->metadata->after->avatar_url)}
    {assign var="object_avatar_url" value=$action->metadata->after->avatar_url}
    {if $action->object_type eq 'Maker'}
        {assign var="object_url" value="/m/`$action->metadata->after->uid`/`$action->metadata->after->slug`"}
        {assign var="object_name" value=$action->metadata->after->name}
        {assign var="object_type" value='m'}
    {elseif $action->object_type eq 'Product'}
        {assign var="object_url" value="/p/`$action->metadata->after->uid`/`$action->metadata->after->slug`"}
        {assign var="object_name" value=$action->metadata->after->name}
        {assign var="object_type" value='p'}
    {/if}
{/if}
{if isset($action->metadata->maker->avatar_url)}
    {assign var="object_url" value="/m/`$action->metadata->maker->uid`/`$action->metadata->maker->slug`"}
    {assign var="object_avatar_url" value=$action->metadata->maker->avatar_url}
    {assign var="object_name" value=$action->metadata->maker->name}
    {assign var="object_type" value='m'}
{/if}
{if isset($action->metadata->product->avatar_url)}
    {if $action->object_type eq 'Product'}
        {assign var="object_url" value="/p/`$action->metadata->product->uid`/`$action->metadata->product->slug`"}
        {assign var="object_avatar_url" value=$action->metadata->product->avatar_url}
        {assign var="object_name" value=$action->metadata->product->name}
        {assign var="object_type" value='p'}
    {else}
        {assign var="object2_url" value="/p/`$action->metadata->product->uid`/`$action->metadata->product->slug`"}
        {assign var="object2_avatar_url" value=$action->metadata->product->avatar_url}
        {assign var="object2_name" value=$action->metadata->product->name}
        {assign var="object2_type" value='p'}
    {/if}
{/if}
{if isset($action->metadata->used_product->avatar_url)}
    {assign var="object2_url" value="/p/`$action->metadata->used_product->uid`/`$action->metadata->used_product->slug`"}
    {assign var="object2_avatar_url" value=$action->metadata->used_product->avatar_url}
    {assign var="object2_name" value=$action->metadata->used_product->name}
    {assign var="object2_type" value='p'}
{/if}
{if isset($action->metadata->after->maker->avatar_url)}
    {assign var="object_avatar_url" value=$action->metadata->after->maker->avatar_url}
    {assign var="object_url" value="/m/`$action->metadata->after->maker->uid`/`$action->metadata->after->maker->slug`"}
    {assign var="object_name" value=$action->metadata->after->maker->name}
    {assign var="object_type" value='m'}
{/if}
{if isset($action->metadata->after->product->avatar_url)}
    {assign var="object2_url" value="/p/`$action->metadata->after->product->uid`/`$action->metadata->after->product->slug`"}
    {assign var="object2_avatar_url" value=$action->metadata->after->product->avatar_url}
    {assign var="object2_name" value=$action->metadata->after->product->name}
    {assign var="object2_type" value='p'}
{/if}
{if isset($action->metadata->inspirer_maker_id)}
    {assign var="object_avatar_url" value=$action->metadata->inspirer_maker->avatar_url}
    {assign var="object_url" value="/m/`$action->metadata->inspirer_maker->uid`/`$action->metadata->inspirer_maker->slug`"}
    {assign var="object_name" value=$action->metadata->inspirer_maker->name}
    {assign var="object_type" value='m'}

    {assign var="object2_avatar_url" value=$action->metadata->maker->avatar_url}
    {assign var="object2_url" value="/m/`$action->metadata->maker->uid`/`$action->metadata->maker->slug`"}
    {assign var="object2_name" value=$action->metadata->maker->name}
    {assign var="object2_type" value='m'}
{/if}

{if !isset($admin_bar)}
      <div class="media-left {$action->action_type}">
        <a class="fa {$fa_icon} text-muted fa-3x"></a>
      </div>
      <div class="media-body">
        <h4 class="media-heading">
            {if $is_says}{else}{$verbed|capitalize}{/if}
            <a href="{$object_url}">
                {$object_name|escape}
            </a>

            {if isset($action->object2_id)}
                {if $is_says}{$verbed}{else}on{/if}
                <a href="{$object2_url}">
                    {$object2_name|escape}
                </a>
            {/if}
        </h4>
        <div class="media-attachment row">
          <div class="col-xs-1">
          <a href="{$object_url}"><img src="{insert name='user_image' image_url=$object_avatar_url image_proxy_sig=$image_proxy_sig type=$object_type}" alt="{$object_name|escape}" class="img-rounded"></a>
          </div>
          <div class="col-xs-10 media-attachment-detail">
            {if isset($action->object2_id)}
              <small>
                <a href="{$object2_url}">
                    <img src="{insert name='user_image' image_url=$object2_avatar_url image_proxy_sig=$image_proxy_sig type=$object2_type}" alt="{$object2_name|escape}" class="img-rounded">
                    {$object2_name|escape}
                </a>
              </small>
            {/if}

            {if $action->action_type eq 'update'}{include file='_diff.tpl'}{/if}
            {if $action->action_type eq 'inspire'}
                <p>{$action->metadata->description|escape}</p>
            {/if}
            </div>

        </div>

        <h6>{$action->time_performed|relative_datetime} ago &bull; {$actor} </h6>
      </div>
{else}
    {* Show the admin bar version of the action *}

    {$actor} {if $is_says}{else}{$verbed}{/if} <a href="{$object_url}">{$object_name|escape}</a> {if isset($action->object2_id)} {if $is_says}{$verbed}{else}on{/if} <a href="{$object2_url}">{$object2_name|escape}</a>{/if} <br /><small class="text-muted">{$action->time_performed|relative_datetime} ago</small>

{/if}

