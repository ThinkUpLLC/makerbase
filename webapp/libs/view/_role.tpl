{*
 * Display a role. Show makers on product pages, and products on maker pages.
 *
 * $role (required)
 *}

{if isset($role->product)}
    {assign var="display_object" value=$role->product}
    {assign var="object_route" value='p'}
    {assign var="object_type" value='product'}
{elseif isset($role->maker)}
    {assign var="display_object" value=$role->maker}
    {assign var="object_route" value='m'}
    {assign var="object_type" value='maker'}
{/if}

{if isset($role->start_MY)}<span class="badge">{$role->start_MY}{if isset($role->end_MY)} &mdash; {$role->end_MY}{/if}</span>{/if}

<a href="#">
<button type="button" class="btn btn-default" aria-label="Center Align">
  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
</button>
</a>
<a href="#">
<button type="button" class="btn btn-default" aria-label="Center Align">
  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
</button>
</a>
<div class="media-left">
    <img class="media-object" src="{insert name='user_image' image_url=$display_object->avatar_url image_proxy_sig=$image_proxy_sig type=$object_type}" alt="{$display_object->name} logo" width="100">
</div>
<div class="media-body">
    <h3><a href="/{$object_route}/{$display_object->slug}">{$display_object->name}</a></h3>
    {$role->role}
</div>
