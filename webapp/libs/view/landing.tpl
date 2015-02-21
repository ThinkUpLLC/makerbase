{include file="_head.tpl"}

<h1>Welcome</h1>

<form action="search.php"><input type='text' name='q'> <input type='Submit' value='Search'></form>

<div class="row">

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

</div>

{include file="_footer.tpl"}