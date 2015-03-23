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

	{if isset($twitter_users) && count($twitter_users) > 0}
		<div class="list-group">
		<!-- begin auto-fill loop here -->
		{foreach $twitter_users as $twitter_user}
			<a class="list-group-item media add-autofill" data-name="{if isset($twitter_user.full_name)}{$twitter_user.full_name}{/if}" data-avatar="{if isset($twitter_user.avatar)}{$twitter_user.avatar}{/if}" data-url="{if isset($twitter_user.url)}{$twitter_user.url}{/if}" data-description="{if isset($twitter_user.description)}{$twitter_user.description}{/if}" data-slug="{if isset($twitter_user.user_name)}{$twitter_user.user_name}{/if}">
				<div class="media-left media-middle">
					{if isset($twitter_user.avatar)}<img src="{$twitter_user.avatar}" id="avatar-img" width="30">{/if}
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-twitter pull-right"></i>
			    	{if isset($twitter_user.user_name)}@{$twitter_user.user_name}{/if}
			    	{if isset($twitter_user.url)}&nbsp;<small>{$twitter_user.url}</small>{/if}
			    </h3>
			    <p>{if isset($twitter_user.description)}{$twitter_user.description}{/if}</p>
			  </div>
			</a>
		{/foreach}

		</div><!-- end list group -->
	{else}
		No Twitter users named {$smarty.get.q} found
	{/if}
	</div>

</div>


{include file="_footer.tpl"}
