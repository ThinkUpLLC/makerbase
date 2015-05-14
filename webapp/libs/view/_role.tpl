
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

<div class="list-group-item col-xs-12" id="role-{$role->uid}">
    <div class="media-left media-top">
        <a href="/{$object_route}/{$display_object->uid}/{$display_object->slug}">
        <img class="media-object" src="{insert name='user_image' image_url=$display_object->avatar_url image_proxy_sig=$image_proxy_sig type=$object_route}" alt="{$display_object->name} logo" width="50" height="50">
        </a>

        <form method="post" action="/edit/role/" class="role-archive-form" id="role-archive-{$role->uid}">
          <input type="hidden" name="uid" value="{$role->uid}" />
          <input type="hidden" name="archive" value="{if $role->is_archived}0{else}1{/if}"/>
            <input type="hidden" name="originate_slug" value="{if isset($product->slug)}{$product->slug}{elseif isset($maker->slug)}{$maker->slug}{/if}">
            <input type="hidden" name="originate_uid" value="{if isset($product->uid)}{$product->uid}{elseif isset($maker->uid)}{$maker->uid}{/if}">
            <input type="hidden" name="originate" value="{if isset($product->slug)}product{elseif isset($maker->slug)}maker{/if}">
            <button type="submit" class="btn btn-danger btn-xs pull-left">
                {if $role->is_archived}Unarchive{else}Archive{/if}
            </button>
        </form>

    </div>
    <div class="media-body">
        <h5 class="pull-right {if !isset($role->end_MY)}text-success{/if}">{if isset($role->start_MY)}{$role->start_MY} &mdash; {if isset($role->end_MY)}{$role->end_MY}{else}Present{/if}{/if}</h5>

        <h3><a href="/{$object_route}/{$object_uid}/{$display_object->slug}">{$display_object->name}</a></h3>

        <div id="role-description-{$role->uid}">

            <a {if isset($logged_in_user)}href="#" onclick="$('#edit-role-{$role->uid}').toggle();$('#role-description-{$role->uid}').toggle();$('#role-archive-{$role->uid}').toggle();"{else}href="{$sign_in_with_twttr_link}"{/if} type="button" class="btn btn-link btn-xs pull-right" id="edit-role-btn">edit</a>

            <h4>
                {$role->role|atnames:'/search/?q='}
            </h4>


       </div>

        {if isset($logged_in_user)}
        <!-- edit form -->
        <div class="media-footer" id="edit-role-{$role->uid}">
        <form method="post" action="/edit/role/" class="form-horizontal edit-role-form">


            <div class="form-group col-xs-12">
                <label for="role" class="col-sm-1 control-label hidden-xs">Role</label>
                <div class="col-xs-12 col-sm-10">
                  <input type="text" class="form-control edit-role-name" autocomplete="off" id="role" name="role" placeholder="'{$placeholder}'" value="{$role->role}">
                   <small>{$role_guidance}</small>
                </div>
            </div>

            {include file="_dates.tpl" start_m={$role->start_m} start_Y={$role->start_Y} end_m={$role->end_m} end_Y={$role->end_Y}}

            <input type="hidden" name="role_uid" value="{$role->uid}">
            <input type="hidden" name="originate_slug" value="{if isset($product->slug)}{$product->slug}{elseif isset($maker->slug)}{$maker->slug}{/if}">
            <input type="hidden" name="originate_uid" value="{if isset($product->uid)}{$product->uid}{elseif isset($maker->uid)}{$maker->uid}{/if}">
            <input type="hidden" name="originate" value="{if isset($product->slug)}product{elseif isset($maker->slug)}maker{/if}">

            <div class="form-group">
                <label for="update-role" class="col-sm-1 control-label"></label>
                <div class="col-sm-9">
                    <button class="btn btn-primary" type="submit" id="update-role">Update role</button>
                </div>
            </div>
        </form>


        <button onclick="$('#role-description-{$role->uid}').toggle();$('#edit-role-{$role->uid}').toggle();$('#role-archive-{$role->uid}').toggle();" type="button" class="btn btn-link btn-xs pull-right" id="edit-role-btn">cancel</button>

        </div>
        {/if}


    </div>
</div>

