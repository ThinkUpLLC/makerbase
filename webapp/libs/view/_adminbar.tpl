
        <div class=" col-xs-2 col-sm-1" role="signout" id="signout-button">
          <button type="button" class="btn btn-danger btn-sm navbar-btn dropdown-toggle pull-right" data-toggle="dropdown" aria-expanded="false" id="user-menu">
            <img src="{insert name='user_image' image_url=$logged_in_user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" style="min-width: 200px; left: -158px;">

              <li><a href="{$site_root_path}u/{$logged_in_user->uid}">Settings</a></li>
              <li><a href="{$site_root_path}signout/">Sign out</a></li>
              <li><a href="/s3cr3t"><i class="fa fa-tachometer text-primary"></i> Dashboard</a></li>
              <li role="separator" class="divider"></li>

            {if isset($last_admin_activity) && sizeof($last_admin_activity) > 0}
              <li class="dropdown-header">Action History</li>
            {foreach $last_admin_activity as $action}
              <li><p style="color: black; padding: 10px 20px;">{include file="_action.tpl" admin_bar=true}</p></li>
            {/foreach}
              <li role="separator" class="divider"></li>
            {/if}

            <li class="dropdown-header">Admin</li>


            {if isset($maker)}
            <li>
                <form method="post" action="/adminedit/maker/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$maker->uid}" />
                    <input type="hidden" name="freeze" value="{if $maker->is_frozen}0{else}1{/if}"/>
                    <button class="btn {if $maker->is_frozen}btn-success{else}btn-warning{/if} btn-sm" type="submit">{if $maker->is_frozen}Unf{else}F{/if}reeze {$maker->name|escape}</button>
                </form>
            </li>
            <li>
                <form method="post" action="/adminedit/maker/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$maker->uid}" />
                    <input type="hidden" name="delete" value="1"/>
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure? This cannot be undone.');">DELETE {$maker->name|escape}</button>
                </form>
            </li>
            <li>
<textarea style="margin: 10px 20px; height: 100px;">
$featured_maker_1_array = array(
    'name' => '{$maker->name|escape}',
    'avatar_url' => '{$maker->avatar_url}',
    'uid' => '{$maker->uid}',
    'slug' => '{$maker->slug}'
);

$featured_maker_1_products = array();

{if isset($roles)}
{if count($roles) > 0}
{foreach $roles as $role}
$featured_maker_1_products[] = array(
    'name' => '{$role->product->name}',
    'uid' => '{$role->product->uid}',
    'slug' => '{$role->product->slug}',
    'avatar_url' => '{$role->product->avatar_url}',
);
{/foreach}
{/if}
{/if}

</textarea>
            </li>
            {/if}

            {if isset($product)}
            <li>
                <form method="post" action="/adminedit/product/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$product->uid}" />
                    <input type="hidden" name="freeze" value="{if $product->is_frozen}0{else}1{/if}"/>
                    <button class="btn {if $product->is_frozen}btn-success{else}btn-warning{/if} btn-sm" type="submit">{if $product->is_frozen}Unf{else}F{/if}reeze {$product->name|escape}</button>
                </form>
            </li>
            <li>
                <form method="post" action="/adminedit/product/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$product->uid}" />
                    <input type="hidden" name="delete" value="1"/>
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure? This cannot be undone.');">DELETE {$product->name|escape}</button>
                </form>
            </li>
            <li>
<textarea style="margin: 10px 20px; height: 100px;">
$featured_product_1_array = array(
    'name' => '{$product->name|escape}',
    'avatar_url' => '{$product->avatar_url}',
    'uid' => '{$product->uid}',
    'slug' => '{$product->slug}'
);

$featured_product_1_makers = array();

{if isset($roles)}
{if count($roles) > 0}
{foreach $roles as $role}
$featured_product_1_products[] = array(
    'name' => '{$role->maker->name}',
    'uid' => '{$role->maker->uid}',
    'slug' => '{$role->maker->slug}',
    'avatar_url' => '{$role->maker->avatar_url}',
);
{/foreach}
{/if}
{/if}

</textarea>
            </li>

            {/if}

            {if isset($user) && $user->uid !== $logged_in_user->uid}
            <li>
                <form method="post" action="/adminedit/user/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$user->uid}" />
                    <input type="hidden" name="freeze" value="{if $user->is_frozen}0{else}1{/if}"/>
                    <button class="btn {if $user->is_frozen}btn-success{else}btn-warning{/if} btn-sm">{if $user->is_frozen}Unf{else}F{/if}reeze {$user->name}</button>
                </form>
            </li>
            <li><a>Logged in {$user->last_login_time|relative_datetime} ago</a></li>
            <li><a>Joined {$user->creation_time|relative_datetime} ago</a></li>
            <li><a>Fetched friends {$user->last_loaded_friends|relative_datetime} ago</a></li>
            <li><a>Email {if !isset($user->email)}not set{else}set, {if !$user->is_subscribed_maker_change_email}un{/if}subscribed{/if}</a></li>
            {/if}

          </ul>
        </div>
