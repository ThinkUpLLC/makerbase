{*
 * Display an action.
 *
 * $action (required)
 * $logged_in_user (optional)
 *}

{* Actor: Who is performing the action *}
{if isset($logged_in_user) && $action->user_uid eq $logged_in_user->uid}
    {assign var="actor" value="You"}
{else}
    {assign var="actor" value="<a href=\"/u/`$action->user_uid`\" class=\"user-link\">`$action->username`</a>"}
{/if}

{* Verb: What was the action, and is actor saying so or doing it *}
{assign var="is_says" value=false}

{if $action->action_type eq 'freeze'} {* User froze Project/Maker *}
    {assign var="verbed" value="froze"}
{elseif $action->action_type eq 'unfreeze'} {* User unfroze Project/Maker *}
    {assign var="verbed" value="unfroze"}
{elseif $action->action_type eq 'made with'} {* User said Project uses Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="uses"}
{elseif $action->action_type eq 'not made with'} {* User said Project does not use Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="doesn't use"}
{elseif $action->action_type eq 'associate'} {* User said Maker made Project *}
    {assign var="is_says" value=true}
    {assign var="verbed" value="made"}
{else} {* default *}
    {assign var="verbed" value="`$action->action_type`d"}
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


      <div class="media-left">
        <a href="{$object_url}" class="avatar">
          <img class="media-object img-responsive img-rounded" src="{insert name='user_image' image_url=$object_avatar_url image_proxy_sig=$image_proxy_sig type=$object_type}" alt="{$object_name}">
        </a>
      </div>
      <div class="media-body">
        <h6>{$action->time_performed|relative_datetime} ago</h6>
        <h4 class="media-heading">{if $actor neq 'You'}<a href="{$object_url}">{$actor}</a>{else}{$actor}{/if} {if $is_says}said{else}{$verbed}{/if} <a href="{$object_url}">{$object_name}</a>{if isset($action->object2_id)} {if $is_says}{$verbed}{else}on{/if} <a href="{$object2_url}">{$object2_name}</a>{/if}</a></h4>
          {if isset($object2_avatar_url)}
          <div class="activity-attachment">
            <a href="{$object2_url}" class="action-avatar pull-left">
              <img src="{insert name='user_image' image_url=$object2_avatar_url image_proxy_sig=$image_proxy_sig type=$object2_type}" alt="{$object2_name}" class="img-responsive img-rounded">
            </a>
            <div class="">
              <h5><a href="{$object2_url}">{$object2_name}</a></h5>
              <p></p>
            </div>
          </div>
          {/if}
          {if $action->action_type eq 'update'}
          <blockquote>
            {include file='_diff.tpl'}
          </blockquote>
          {/if}

      </div>

<!--
<div class="action-item">
	<div class="media-left media-top col-xs-2 col-sm-2">
		<a href="{$object_url}"><img src="" class="img-responsive" alt=""></a>
	</div>

    <div class=" pull-right media-right col-xs-2 col-sm-2">
        {if isset($object2_avatar_url)}<a href="{$object2_url}"><img src="{insert name='user_image' image_url=$object2_avatar_url image_proxy_sig=$image_proxy_sig type=$object2_type}" class="img-responsive"></a>{/if}
    </div>

	<div class="media-body">
		<h5>{$actor} {if $is_says}said{else}{$verbed}{/if} <a href="{$object_url}">{$object_name}</a>{if isset($action->object2_id)} {if $is_says}{$verbed}{else}on{/if} <a href="{$object2_url}">{$object2_name}</a>{/if}</h5>


	</div>
</div>
-->

{* Clear vars for next loop iteration *}
{assign var="actor" value=null}
{assign var="verbed" value=null}
{assign var="is_says" value=null}

{assign var="object_avatar_url" value=null}
{assign var="object_name" value=null}
{assign var="object_url" value=null}
{assign var="object_type" value=null}

{assign var="object2_avatar_url" value=null}
{assign var="object2_name" value=null}
{assign var="object2_url" value=null}
{assign var="object2_type" value=null}
