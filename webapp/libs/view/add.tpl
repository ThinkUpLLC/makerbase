{include file="_head.tpl"}

{* Don't collapse any fields if they're filled in *}
{if isset($slug) || isset($avatar_url) || isset($description)}
	{assign var="collapsed" value=false}
{else}
	{assign var="collapsed" value=true}
{/if}

<div class="row">

	<div class="col-sm-6 col-xs-12">

		<h2>Add a {$object}<br />
		<small>{if $object eq 'maker'}A human being who builds things out of bits and bytes.{elseif $object eq 'product'}A digital work, like a web site, app, or service.{/if}</small></h2>

		{if $is_manual}
		<form method="post" action="/add/{$object}/" class="form-horizontal">

			<div class="form-group">
				<label for="full_name" class="col-xs-3 control-label">Name</label>
				<div class="col-xs-9">
					<input type="text" class="form-control" name="name" id="name" value="{if isset($name)}{$name}{/if}">
				</div>
			</div>

			<div class="form-group">
				<label for="url" class="col-xs-3 control-label">Web site</label>
				<div class="col-xs-9">
					<input type="text" class="form-control col-xs-6" name="url" id="url" value="{if isset($url)}{$url}{/if}">
				</div>
			</div>

			{if $collapsed}
			<div class="form-group">
				<label class="col-xs-3 control-label"></label>
				<div class="col-xs-9">
					<a href="#all-the-fields" data-toggle="collapse" class="btn btn-xs btn-default">More <i class="caret"></i></a>
				</div>
			</div>
			{/if}

			<div{if $collapsed} class="collapse" id="all-the-fields"{/if}>

			{if $object eq 'product'}

			<div class="form-group">
				<label for="description" class="col-xs-3 control-label">Description</label>
				<div class="col-xs-9">
					<input type="text" class="form-control col-xs-6" name="description" id="description" value="{if isset($description)}{$description}{/if}">
				</div>
			</div>

			{/if}

			<div class="form-group">
				<label for="avatar_url" class="col-xs-3 control-label">Avatar URL</label>
				<div class="col-xs-2">
					<img src="{if isset($avatar_url)}{$avatar_url}{/if}" id="avatar-img" width="100">
				</div>
				<div class="col-xs-6 col-xs-offset-1">
					<input type="text" class="form-control col-xs-6"  name="avatar_url" id="avatar-url" value="{if isset($avatar_url)}{$avatar_url}{/if}" onkeyup="document.getElementById('avatar-img').src = this.value">
				</div>
			</div>

			<div class="form-group">
				<label for="description" class="col-xs-3 control-label">Slug</label>
				<div class="col-xs-9">
					<input type="text" class="form-control col-xs-6" name="slug" id="slug" value="{if isset($slug)}{$slug}{/if}">
				</div>
			</div>

    	</div>
			<div class="form-group">
				<button class="btn btn-primary col-xs-6 col-xs-offset-4" type="submit">Make it!</button>
	    </div>
		</form>
		{else}
		<form method="post" action="/add/{$object}/" class="form-horizontal">
			<label>Enter a Twitter username:</label>
			<div class="input-group">
			  <span class="input-group-addon" id="basic-addon1">@</span>
			  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1" name="twitter_username">
				<span class="input-group-btn">
			        <button class="btn btn-default" type="submit">Go</button>
			    </span>
			</div>
		</form>
		{/if}
	</div>


	<div class="col-sm-6 col-xs-12 add-panels">

		<h2>&nbsp;<br />
		<small>Choose an auto-fill&nbsp;</small></h2>

		<div class="list-group">
		<!-- begin auto-fill loop here -->

			<a class="list-group-item media add-autofill" data-name="{if isset($name)}{$name}{/if}" data-avatar="{if isset($avatar_url)}{$avatar_url}{/if}" data-url="{if isset($url)}{$url}{/if}" data-description="{if isset($description)}{$description}{/if}" data-slug="{if isset($slug)}{$slug}{/if}">
				<div class="media-left media-middle">
					{if isset($avatar_url)}<img src="{$avatar_url}" id="avatar-img" width="30">{/if}
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-twitter pull-right"></i>
			    	{if isset($name)}@{$name}{/if}
			    	{if isset($url)}&nbsp;<small>{$url}</small>{/if}
			    </h3>
			    <p>{if isset($description)}{$description}{/if}</p>
			  </div>
			</a>


			<a class="list-group-item media add-autofill" data-name="{if isset($name)}{$name}{/if} 2" data-avatar="{if isset($avatar_url)}{$avatar_url}{/if}#2" data-url="{if isset($url)}{$url}{/if}#2" data-description="2 {if isset($description)}{$description}{/if}" data-slug="{if isset($slug)}{$slug}{/if} 2">
				<div class="media-left media-middle">
					{if isset($avatar_url)}<img src="{$avatar_url}" id="avatar-img" width="30">{/if}
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-facebook-official pull-right"></i>
			    	{if isset($name)}@{$name}{/if} 2
			    	{if isset($url)}&nbsp;<small>{$url}</small>{/if}
			    </h3>
			    <p>{if isset($description)}{$description}{/if}</p>
			  </div>
			</a>



			<a class="list-group-item media add-autofill" data-name="{if isset($name)}{$name}{/if} 3" data-avatar="{if isset($avatar_url)}{$avatar_url}{/if}#3" data-url="{if isset($url)}{$url}{/if}#3" data-description="3 {if isset($description)}{$description}{/if}" data-slug="{if isset($slug)}{$slug}{/if} 3">
				<div class="media-left media-middle">
					{if isset($avatar_url)}<img src="{$avatar_url}" id="avatar-img" width="30">{/if}
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-angellist pull-right"></i>
			    	{if isset($name)}@{$name}{/if} 3
			    	{if isset($url)}&nbsp;<small>{$url}</small>{/if}
			    </h3>
			    <p>{if isset($description)}{$description}{/if}</p>
			  </div>
			</a>

			<a class="list-group-item media add-autofill" data-name="Fourth Result" data-avatar="http://placehold.it/100/46bcff/ffffff&text=avatar" data-url="https://example.com" data-description="We'll need to add better handling for longer descriptions being displayed here, since not everything in the world is constrained to being simply one hundred and forty characters or less." data-slug="SLUGGO!">
				<div class="media-left media-middle">
					<img src="http://placehold.it/30/46bcff/ffffff&text=avatar" id="avatar-img" width="30">
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-github pull-right"></i>
			    	Fourth Result
			    	&nbsp;<small>https://example.com</small>
			    </h3>
			    <p>We'll need to add better handling for longer descriptions being displayed here, since not everything in the world is constrained to being simply one hundred and forty characters or less.</p>
			  </div>
			</a>

		</div><!-- end list group -->

	</div>

</div>


{include file="_footer.tpl"}
