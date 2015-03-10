{include file="_head.tpl"}

<div class="row">

<h2>Add a {$object}<br />
<small>{if $object eq 'maker'}A human being who builds things out of bits and bytes.{elseif $object eq 'product'}A digital work, like a web site, app, or service.{/if}</small></h2>

<div class="col-xs-6">
	{if $is_manual}
	<form method="post" action="/add/{$object}/" class="form-horizontal">
		<div class="form-group">
			<label for="full_name" class="col-xs-3 control-label">Name</label>
			<div class="col-xs-9">
				<input type="text" class="form-control" name="name" value="{if isset($name)}{$name}{/if}">
			</div>
		</div>
		{if $object eq 'product'}
		<div class="form-group">
			<label for="description" class="col-xs-3 control-label">Slug</label>
			<div class="col-xs-9">
				<input type="text" class="form-control col-xs-6" name="slug" value="{if isset($slug)}{$slug}{/if}">
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-xs-3 control-label">Description</label>
			<div class="col-xs-9">
				<input type="text" class="form-control col-xs-6" name="description" value="{if isset($description)}{$description}{/if}">
			</div>
		</div>
		{/if}
		{if $object eq 'maker'}
		<div class="form-group">
			<label for="full_name" class="col-xs-3 control-label">Username</label>
			<div class="col-xs-9">
				<input type="text" name="username" value="{if isset($username)}{$username}{/if}">
			</div>
		</div>
		{/if}

		<div class="form-group">
			<label for="url" class="col-xs-3 control-label">Web site url</label>
			<div class="col-xs-9">
				<input type="text" class="form-control col-xs-6" name="url" value="{if isset($url)}{$url}{/if}">
			</div>
		</div>
		<div class="form-group">
			<label for="avatar_url" class="col-xs-3 control-label">Avatar URL</label>
			<div class="col-xs-9">
				{if isset($avatar_url)}<img src="{$avatar_url}" width="100">{/if}
				<input type="text" class="form-control col-xs-6"  name="avatar_url" value="{if isset($avatar_url)}{$avatar_url}{/if}">
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-primary col-xs-offset-4" type="submit">Make it!</button>
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

</div>

{include file="_footer.tpl"}
