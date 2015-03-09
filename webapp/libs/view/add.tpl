{include file="_head.tpl"}

<div class="row">

<h2>Add a {$object}</h2>

<div class="col-xs-6">
	{if isset($twitter_user_details)}
	<form method="post" action="/add/{$object}/" class="form-horizontal">

		<div class="form-group">
			<label for="full_name" class="col-xs-3 control-label">Name</label>
			<div class="col-xs-9">
				<input type="text" class="form-control" name="full_name" value="{$twitter_user_details.full_name}">
				<input type="hidden" name="username" value="{$twitter_user_details.user_name}">
			</div>
		</div>
		{if $object eq 'product'}
		<div class="form-group">
			<label for="description" class="col-xs-3 control-label">Description</label>
			<div class="col-xs-9">
				<input type="text" class="form-control col-xs-6" name="description" value="{$twitter_user_details.description}">
			</div>
		</div>
		{/if}
		<div class="form-group">
			<label for="url" class="col-xs-3 control-label">Web site url</label>
			<div class="col-xs-9">
				<input type="text" class="form-control col-xs-6" name="url" value="{$twitter_user_details.url}">
			</div>
		</div>
		<div class="form-group">
			<label for="avatar_url" class="col-xs-3 control-label">Avatar</label>
			<div class="col-xs-9">
				<img src="{$twitter_user_details.avatar}" width="100">
				<input type="hidden" name="avatar_url" value="{$twitter_user_details.avatar}">
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
