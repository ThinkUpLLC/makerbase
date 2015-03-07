{include file="_head.tpl"}

{include file="_isosceles.usermessage.tpl"}

<div class="row">

{if isset($actions)}
<h1><a href="/add/maker">Add a maker</a></h1>
  <div class="col-xs-6">
	<ul class="list-group">
	{foreach $actions as $action}
	    <li class="list-group-item">
	    {$action->name} created <a href="/{if $action->object_type eq 'Maker'}m{else}p{/if}/{$action->object_slug}/">{$action->object_name}</a> {$action->time_performed|relative_datetime} ago
	    </li>
	{/foreach}
	</ul>
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