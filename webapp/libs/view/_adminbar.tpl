      <div class="col-xs-1 col-sm-1 pull-right" role="signout" id="signout-button">
        <div class="btn-group">
          <button type="button" class="btn btn-danger btn-sm navbar-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="user-menu" >
            <img src="{insert name='user_image' image_url=$logged_in_user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="Signed in as {$logged_in_user->twitter_username}" width="20" height="20">
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" style="min-width: 200px; left: -158px;">

              <li><a href="{$site_root_path}u/{$logged_in_user->uid}">Your activity</a></li>
              <li><a href="{$site_root_path}signout/">Sign out</a></li>
              <li><a href="/s3cr3t"><i class="fa fa-tachometer text-primary"></i> Dashboard</a></li>
              <li role="separator" class="divider"></li>

            {if isset($last_admin_activity) && sizeof($last_admin_activity) > 0}
              <li class="dropdown-header">Action History</li>
            {foreach $last_admin_activity as $action}
              <li><p style="color: black; padding: 10px 20px;">{include file="_adminaction.tpl"}</p></li>
            {/foreach}
              <li role="separator" class="divider"></li>
            {/if}

            <li class="dropdown-header">Admin</li>


            {if isset($maker)}
            <li>
                <form method="post" action="/adminedit/maker/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$maker->uid}" />
                    <input type="hidden" name="freeze" value="{if $maker->is_frozen}0{else}1{/if}"/>
                    <button class="btn {if $maker->is_frozen}btn-success{else}btn-warning{/if} btn-sm" type="submit">{if $maker->is_frozen}Unf{else}F{/if}reeze {$maker->name}</button>
                </form>
            </li>
            <li>
                <form method="post" action="/adminedit/maker/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$maker->uid}" />
                    <input type="hidden" name="delete" value="1"/>
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure? This cannot be undone.');">DELETE {$maker->name}</button>
                </form>
            </li>
            {/if}

            {if isset($product)}
            <li>
                <form method="post" action="/adminedit/product/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$product->uid}" />
                    <input type="hidden" name="freeze" value="{if $product->is_frozen}0{else}1{/if}"/>
                    <button class="btn {if $product->is_frozen}btn-success{else}btn-warning{/if} btn-sm" type="submit">{if $product->is_frozen}Unf{else}F{/if}reeze {$product->name}</button>
                </form>
            </li>
            <li>
                <form method="post" action="/adminedit/product/" class="" style="padding: 10px 20px;">
                    <input type="hidden" name="uid" value="{$product->uid}" />
                    <input type="hidden" name="delete" value="1"/>
                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Are you sure? This cannot be undone.');">DELETE {$product->name}</button>
                </form>
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
            {/if}

          </ul>
        </div>
      </div>
