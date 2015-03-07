{include file="_head.tpl"}

{include file="_isosceles.usermessage.tpl"}

<div class="row">

{if isset($actions)}
  <div class="col-xs-6">
  <br><br>
	<form method="post" action="/add/maker/">
		<label>Add a maker:</label>
		<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">@</span>
		  <input type="text" class="form-control" placeholder="Twitter username" aria-describedby="basic-addon1" name="twitter_username">
			<span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Go</button>
		    </span>
		</div>
	</form>
  <br><br>
  	{if sizeof($actions) > 0}
  	<h3>Recent activity</h3>
	<ul class="list-group">
	{foreach $actions as $action}
	    <li class="list-group-item">
	    {if $action->twitter_user_id eq $logged_in_user->twitter_user_id}You{else}<a href="/u/{$action->twitter_user_id}/">{$action->name}</a>{/if} {if $action->action_type eq 'create'}added{elseif $action->action_type eq 'update'}updated{/if} <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->object_slug}/">{$action->object_name}</a> <span class="badge">{$action->object_type}</span> {$action->time_performed|relative_datetime} ago
	    </li>
	{/foreach}
	</ul>
  	{/if}
  </div>
{/if}

{if isset($makers) && isset($products)}
  <div class="col-xs-6">
	<h2>Makers</h2>
	<ul class="list-group">
	{foreach $makers as $maker}
	    <li class="list-group-item">
  			<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$maker->name}" width="30">
			</div>
			<div class="media-body">
	    		<a href="/m/{$maker->slug}">{$maker->name}</a>
	    	</div>
	    </li>
	{/foreach}
	</ul>
  </div>

  <div class="col-xs-6">
	<h2>Projects</h2>
	<ul class="list-group">
	{foreach $products as $product}
	    <li class="list-group-item">
	    	<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='product'}" alt="{$product->name}" width="30">
	    	</div>
	    	<div class="media-body">
	    		<a href="/p/{$product->slug}">{$product->name}</a>
	    	</div>
	    </li>
	{/foreach}
	</ul>
  </div>
{/if}

</div>

{include file="_footer.tpl"}