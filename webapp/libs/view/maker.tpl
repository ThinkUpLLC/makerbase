{include file="_head.tpl"}

<h1>{$maker->slug} is a maker <button type="button" class="btn btn-success pull-right">Hey, I'm {$maker->slug}!</button>
</h1>

<div class="row">
  <div class="col-xs-2">
  	<img src="{$maker->avatar_url}" class="img-responsive" width="100%" />
	<p><a href="{$maker->url}">{$maker->url}</a></p>
  </div>
  <div class="col-xs-10">
  	<ul class="list-group">
	{foreach $roles as $role}
		<li class="list-group-item">
			<span class="badge">{$role->start} &mdash; {$role->end}</span>
  			<div class="media-left">
				<img class="media-object" src="http://placehold.it/75&text={$role->product->name}+logo" alt="{$role->product->name} logo">
			</div>
			<div class="media-body">
				<h3>{$role->product->name}</h3>
				{$role->role}
			</div>
		</li>
	{/foreach}
	</ul>
  </div>
</div>

{include file="_footer.tpl"}