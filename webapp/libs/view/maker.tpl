{include file="_head.tpl"}

<div class="row">
  <div class="col-xs-12">
	<h1>{$maker->name} is a maker<button type="button" class="btn btn-sm btn-success pull-right"><i class="glyphicon glyphicon-user"></i> Hey, I'm {$maker->name}!</button>
	</h1>
  </div>
</div>

<div class="row">
  <div class="col-xs-5">
    <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$maker->name}" width="100%">

	<p><a href="{$maker->url}">{$maker->url}</a></p>

    {if sizeof($actions) > 0}
    <h4>History</h4>
    <ul class="list-unstyled">
    {foreach $actions as $action}
        <li class="">
        {include file="_action.tpl"}
        </li>
    {/foreach}
    </ul>
    {/if}

  </div>
  <div class="col-xs-7">
  	<ul class="list-group">
	{foreach $roles as $role}
		<li class="list-group-item">
			<span class="badge">{$role->start_MY}{if isset($role->end_MY)} &mdash; {$role->end_MY}{/if}</span>
  			<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$role->product->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$role->product->name} logo" width="100">
			</div>
			<div class="media-body">
				<h3><a href="/p/{$role->product->slug}">{$role->product->name}</a></h3>
				{$role->role}
			</div>
		</li>
	{/foreach}
	</ul>
	<button type="button" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a{if $roles}nother{/if} project</button>
  </div>
</div>

{include file="_footer.tpl"}