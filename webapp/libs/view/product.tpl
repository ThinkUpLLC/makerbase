{include file="_head.tpl"}

<h1>we made {$product->name} <button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button></h1>

<div class="row">
  <div class="col-xs-2">
  	<img src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" class="img-responsive" width="100%" />
    <p>{$product->description}</p>
	<p><a href="{$product->url}">{$product->url}</a></p>
  </div>
  <div class="col-xs-10">
  	<ul class="list-group">
  		{foreach $roles as $role}
		<li class="list-group-item">
			<span class="badge">{if isset($role->years) && $role->years > 0}{$role->years} year{if $role->years neq 1}s{/if}{else}{$role->start_MY}{/if}</span>
  			<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$role->maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="maker" width="100">
			</div>
			<div class="media-body">
				<h3><a href="/m/{$role->maker->slug}">{$role->maker->name}</a></h3>
				{$role->role}
			</div>
		</li>
  		{/foreach}
	</ul>
  </div>
</div>


{include file="_footer.tpl"}