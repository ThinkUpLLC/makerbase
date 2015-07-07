
<nav class="navbar navbar-fixed-bottom" style="background-color: #666; border-top: 4px solid black; padding-top: 6px;">
  <div class="container-fluid">
    <div class="navbar-header">
      <strong><a style="color:white" href="/s3cr3t">Admin</a></strong>
        {if isset($last_admin_activity) && sizeof($last_admin_activity) > 0}
        {foreach $last_admin_activity as $action}
          {include file="_adminaction.tpl"}
        {/foreach}
        {/if}
    </div>

	<ul class="nav navbar-nav navbar-right">
        {if isset($maker)}
        <li>
        <form method="post" action="/adminedit/maker/" class="">
        <input type="hidden" name="uid" value="{$maker->uid}" />
        <input type="hidden" name="freeze" value="{if $maker->is_frozen}0{else}1{/if}"/>
        <button class="btn btn-warning btn-sm" style="margin-right: 30px;">{if $maker->is_frozen}Unf{else}F{/if}reeze</button></form>
        </li>
        {/if}

        {if isset($product)}
        <li>
        <form method="post" action="/adminedit/product/" class="">
        <input type="hidden" name="uid" value="{$product->uid}" />
        <input type="hidden" name="freeze" value="{if $product->is_frozen}0{else}1{/if}"/>
        <button class="btn btn-warning btn-sm" style="margin-right: 30px;">{if $product->is_frozen}Unf{else}F{/if}reeze</button></form>
        </li>
        {/if}

        {if isset($user)}
        <li>
        <form method="post" action="/adminedit/user/" class="">
        <input type="hidden" name="uid" value="{$user->uid}" />
        <input type="hidden" name="freeze" value="{if $user->is_frozen}0{else}1{/if}"/>
        <button class="btn btn-warning btn-sm" style="margin-right: 30px;">{if $user->is_frozen}Unf{else}F{/if}reeze</button></form>
        </li>
        {/if}

		{if isset($maker) || isset($product)}<li><button href="#" class="btn btn-danger btn-sm" style="margin-right: 30px;">DELETE</button></li>{/if}
	</ul>

  </div>
</nav>