{include file="_head.tpl"}

<h1>we made {$product->slug} <button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->slug}!</button></h1>

<div class="row">
  <div class="col-xs-2">
  	<img src="{$product->avatar_url}" class="img-responsive" width="100%" />
	<p><a href="{$product->url}">{$product->url}</a></p>
  </div>
  <div class="col-xs-10">
  	<ul class="list-group">
		<li class="list-group-item">
			<span class="badge">years</span>
  			<div class="media-left">
				<img class="media-object" src="http://placehold.it/75&text=maker+avatar" alt="maker">
			</div>
			<div class="media-body">
				<h3>maker</h3>
				role
			</div>
		</li>
	</ul>
  </div>
</div>


{include file="_footer.tpl"}