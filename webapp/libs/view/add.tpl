{include file="_head.tpl"}

{* Don't collapse any fields if they're filled in *}
{if isset($slug) || isset($avatar_url) || isset($description)}
	{assign var="collapsed" value=false}
{else}
	{assign var="collapsed" value=true}
{/if}

{if !isset($name) && isset($smarty.get.q)}
	{assign var="name" value=$smarty.get.q}
{/if}


{if isset($existing_objects)}
<div class="row" id="search-results">
	<h2>This what you were trying to add? <small><a href="#" onclick="$('#add-form').toggle();$('#search-results').toggle();">No, I've got something else.</a></small></h2>

	{foreach $existing_objects as $hit}
	<a class="list-group-item" href="/{$hit._source.type}/{$hit._source.uid}/{$hit._source.slug}">
			<div class="media-left">
        <img class="media-object" src="{insert name='user_image' image_url=$hit._source.avatar_url image_proxy_sig=$image_proxy_sig type=$hit._source.type}" alt="{$hit._source.name}" width="100">
		</div>
		<div class="media-body">
			<h3>{$hit._source.name}</h3>
			{if $hit._source.description neq ''}{$hit._source.description}{/if}
		</div>
	</a>
	{/foreach}
</div>
{/if}

<div class="row" {if isset($existing_objects)}style="display:none;"{/if} id="add-form">


	<div class="col-sm-6 col-xs-12">

		<h2>Add a {if $object eq 'maker'}maker{else}project{/if}<br />
		<small>{if $object eq 'maker'}A human being who builds things out of bits and bytes.{elseif $object eq 'product'}A digital work, like a web site, app, or service.{/if}</small></h2>

		<form method="post" action="/add/{$object}/" class="form-horizontal">

			<div class="col-sm-2 col-xs-2">
					<img src="{if isset($avatar_url)}{$avatar_url}{/if}" id="avatar-img" width="100">
			</div>

			<div class="col-sm-10 col-xs-10">
				<div class="form-group">
					<label for="full_name" class="col-xs-3 control-label">Name</label>
					<div class="col-xs-9">
						<input type="text" class="form-control" name="name" id="name" value="{if isset($name)}{$name}{/if}">
					</div>
				</div>

				<div class="form-group">
					<label for="avatar_url" class="col-xs-3 control-label">Avatar URL</label>
					<div class="col-xs-9">
						<input type="text" class="form-control col-xs-6"  name="avatar_url" id="avatar-url" value="{if isset($avatar_url)}{$avatar_url}{/if}" onkeyup="document.getElementById('avatar-img').src = this.value">
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
					<label for="url" class="col-xs-3 control-label">Web site</label>
					<div class="col-xs-9">
						<input type="text" class="form-control col-xs-6" name="url" id="url" value="{if isset($url)}{$url}{/if}">
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
					<div class="col-xs-12">
						<input type="hidden" name="network_id" id="network-id" value="">
						<input type="hidden" name="network" id="network" value="">
						<button class="btn btn-primary col-xs-4 pull-right" type="submit">Make it!</button>
					</div>
		    </div>
	    </div>
		</form>
	</div>


	<div class="col-sm-6 col-xs-12 add-panels">

{if isset($smarty.get.q)}
		<h2>&nbsp;<br />
		<small>Choose an auto-fill&nbsp;</small></h2>

	{if isset($ios_apps) && count($ios_apps) > 0}
		<div class="list-group">
		<!-- begin auto-fill loop here -->
		{foreach $ios_apps as $ios_app}
			<a class="list-group-item media add-autofill" data-name="{if isset($ios_app.full_name)}{$ios_app.full_name}{/if}" data-avatar="{if isset($ios_app.avatar)}{$ios_app.avatar}{/if}" data-url="{if isset($ios_app.url)}{$ios_app.url}{/if}" data-description="{if isset($ios_app.description)}{$ios_app.description}{/if}" data-slug="{if isset($ios_app.user_name)}{$ios_app.user_name}{/if}">
				<div class="media-left media-middle">
					{if isset($ios_app.avatar)}<img src="{$ios_app.avatar}" id="avatar-img" width="50">{/if}
				</div>

			  <div class="media-body">
			    <h3 class="media-heading">
			    	<i class="fa fa-apple pull-right"></i>
			    	{if isset($ios_app.user_name)}{$ios_app.user_name}{/if}
			    	{if isset($ios_app.url)}&nbsp;<small>{$ios_app.url}</small>{/if}
			    </h3>
			    <p>{if isset($ios_app.description)}{$ios_app.description}{/if}</p>
			  </div>
			</a>
		{/foreach}

		</div><!-- end list group -->
	{else}
		<p>No iOS apps named {$smarty.get.q} found.</p>
	{/if}

	{if isset($twitter_users) && count($twitter_users) > 0}
		<div class="list-group">
		<!-- begin auto-fill loop here -->
		{foreach $twitter_users as $twitter_user}
			<a class="list-group-item media add-autofill" data-name="{if isset($twitter_user.full_name)}{$twitter_user.full_name}{/if}" data-avatar="{if isset($twitter_user.avatar)}{$twitter_user.avatar}{/if}" data-url="{if isset($twitter_user.url)}{$twitter_user.url}{/if}" data-description="{if isset($twitter_user.description)}{$twitter_user.description}{/if}" data-slug="{if isset($twitter_user.user_name)}{$twitter_user.user_name}{/if}" data-network-id="{$twitter_user.user_id}" data-network="twitter">
				<div class="media-left media-middle">
					{if isset($twitter_user.avatar)}<img src="{$twitter_user.avatar}" id="avatar-img" width="50">{/if}
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
		<p>No Twitter users named {$smarty.get.q} found.</p>
	{/if}
{/if}
	</div>
</div>


{include file="_footer.tpl"}
