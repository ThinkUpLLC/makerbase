{include file="_head.tpl"}

<div class="row">

<h2>Add {$object}</h2>

<div class="col-xs-6">
	<div class="form-group">
	{if isset($twitter_user_details)}
	<form method="post" action="/add/{$object}/">
		<div class="input-group">
			<label>Name</label>
			<input type="text" class="form-control" name="full_name" value="{$twitter_user_details.full_name}">
			<input type="hidden" name="username" value="{$twitter_user_details.user_name}">
		</div>
		{if $object eq 'product'}
		<div class="input-group">
			<label>Description</label>
			<input type="text" class="form-control" name="description" value="{$twitter_user_details.description}">
		</div>
		{/if}
		<div class="input-group">
			<label>Web site url</label>
			<input type="text" class="form-control" name="url" value="{$twitter_user_details.url}">
		</div>
		<div class="input-group">
			<label>Avatar</label><br>
			<img src="{$twitter_user_details.avatar}" width="100">
			<input type="hidden" name="avatar_url" value="{$twitter_user_details.avatar}">
		</div>
		<div class="input-group">
			<span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Add</button>
		    </span>
	    </div>
	</form>
	{else}
	<form method="post" action="/add/{$object}/">
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

</div>

{include file="_footer.tpl"}