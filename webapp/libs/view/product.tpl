{include file="_head.tpl"}

<h1>we made {$product->slug} <button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->slug}!</button></h1>

<div class="row">
  <div class="col-xs-2">
  	<img src="{$product->avatar_url}" class="img-responsive" width="100%" />
	<p><a href="{$product->url}">{$product->url}</a></p>
  </div>
  {foreach $roles as $role}
  <div class="col-xs-10">
  	<ul class="list-group">
		<li class="list-group-item">
			<span class="badge">{if isset($role->years) && $role->years > 0}{$role->years} year{if $role->years neq 1}s{/if}{else}{$role->start_MY}{/if}</span>
  			<div class="media-left">
				<img class="media-object" src="{$role->maker->avatar_url}" alt="maker">
			</div>
			<div class="media-body">
				<h3>{$role->maker->name}</h3>
				{$role->role}
			</div>
		</li>
	</ul>
  </div>
  {/foreach}
</div>


{include file="_footer.tpl"}