{*
 * Display a role. Show makers on product pages, and products on maker pages.
 *
 * $role (required)
 *}

{if isset($role->product)}
    {assign var="display_object" value=$role->product}
    {assign var="object_route" value='p'}
    {assign var="object_uid" value=$role->product->uid}
    {assign var="object_type" value='product'}
{elseif isset($role->maker)}
    {assign var="display_object" value=$role->maker}
    {assign var="object_route" value='m'}
    {assign var="object_type" value='maker'}
    {assign var="object_uid" value=$role->maker->uid}
{/if}

{if isset($role->start_MY)}<span class="badge">{$role->start_MY}{if isset($role->end_MY)} &mdash; {$role->end_MY}{/if}</span>{/if}

<div class="media-left media-middle">
    <a href="/{$object_route}/{$display_object->slug}">
    <img class="media-object" src="{insert name='user_image' image_url=$display_object->avatar_url image_proxy_sig=$image_proxy_sig type=$object_type}" alt="{$display_object->name} logo" width="40" height="40">
    </a>
</div>
<div class="media-body">
    <h3>
        <a href="/{$object_route}/{$object_uid}/{$display_object->slug}">{$display_object->name}</a>
        <a {if isset($logged_in_user)}href="#edit-role-{$role->uid}" data-toggle="collapse"{else}href="{$sign_in_with_twttr_link}"{/if} type="button" class="btn btn-default btn-xs" aria-label="Center Align" id="edit-role-btn">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
        </a>
    </h3>
    <h4>{$role->role}</h4>
</div>

{if isset($logged_in_user)}
<!-- edit form -->
<div class="media-footer">
<form method="post" action="/edit/role/" class="form-horizontal collapse" id="edit-role-{$role->uid}">
    <div class="form-group">
        <label for="role" class="col-sm-1 control-label">Role:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="role" name="role" value="{$role->role}">
        </div>
    </div>
    <div class="form-group">
        <label for="start_date" class="col-sm-1 control-label">From:</label>
        <div class="col-sm-9">
          <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="start_date" id="start_date" {if !isset($role->start)}placeholder="YYYY-MM"{else}value="{$role->start_YM}"{/if} data-provide="datepicker" autocomplete="off"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm form-control" name="end_date" id="end_date" {if !isset($role->end)}placeholder="Leave blank if current"{else}value="{$role->end_YM}"{/if} autocomplete="off" />
          </div>
        </div>
    </div>
    <input type="hidden" name="role_uid" value="{$role->uid}">
    <input type="hidden" name="originate_slug" value="{if isset($product->slug)}{$product->slug}{elseif isset($maker->slug)}{$maker->slug}{/if}">
    <input type="hidden" name="originate_uid" value="{if isset($product->uid)}{$product->uid}{elseif isset($maker->uid)}{$maker->uid}{/if}">
    <input type="hidden" name="originate" value="{if isset($product->slug)}product{elseif isset($maker->slug)}maker{/if}">

    <div class="form-group">
        <label for="update-role" class="col-sm-1 control-label"></label>
        <div class="col-sm-9">
            <button class="btn btn-primary" type="submit" id="update-role">Update role</button>
            <button type="button" href="#" class="btn btn-danger pull-right" aria-label="Center Align">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete role
            </button>
        </div>
    </div>
</form>
</div>
{/if}
