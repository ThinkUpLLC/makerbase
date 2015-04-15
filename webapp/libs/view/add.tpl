{include file="_head.tpl"}

{if !isset($name) && isset($smarty.get.q)}
	{assign var="name" value=$smarty.get.q}
{/if}

{if $object eq 'maker'}
	{assign var="addtype" value="Maker"}
{else}
	{assign var="addtype" value="Project"}
{/if}


{if isset($existing_objects)}
<div class="row" id="search-results">
	<div class="col-xs-12">
		<h2>This what you were trying to add?</h2>

		<div class="list-group">
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

		<button onclick="$('#add-interface').toggle();$('#search-results').toggle();" class="btn btn-primary">Nope, I've got something else.</button>

	</div>
</div>
{/if}

<div id="add-interface" {if isset($existing_objects)}style="display:none;"{/if}>

	<div class="row" id="add-form">

		<h2 class="col-xs-12">Add a {$addtype}</h2>

		<form method="post" action="/add/{$object}/" class="form-horizontal">

			<div class="col-xs-2">
					<img src="{if isset($avatar_url)}{$avatar_url}{else}http://placehold.it/300&text=Image{/if}" id="avatar-img" class="img-responsive">
			</div>

			<div class="col-xs-10">

				<div class="form-group">
					<label for="name" class="hidden-xs col-sm-3 control-label">Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="name" id="name" value="{if isset($name)}{$name}{/if}" placeholder="{$addtype} Name" required="true" autofocus="true">
					</div>
				</div>

				<div class="form-group">
					<label for="url" class="hidden-xs col-sm-3 control-label">Web site</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" name="url" id="url" value="{if isset($url)}{$url}{/if}" placeholder="{$addtype} web site (like http://example.com)">
					</div>
				</div>

				{if $object eq 'product'}
				<div class="form-group">
					<label for="description" class="hidden-xs col-sm-3 control-label">Description</label>
					<div class="col-sm-9">
						<textarea class="form-control" name="description" id="description" value="{if isset($description)}{$description}{/if}" placeholder="{$addtype} description" maxlength="140" rows="2">{if isset($description)}{$description}{/if}</textarea>
					</div>
				</div>
				{/if}

				<div class="form-group">
					<label for="avatar_url" class="hidden-xs col-sm-3 control-label">Avatar URL</label>
					<div class="col-sm-9">
						<input type="text" class="form-control text-muted"  name="avatar_url" id="avatar-url" value="{if isset($avatar_url)}{$avatar_url}{/if}" onkeyup="document.getElementById('avatar-img').src = this.value" placeholder="{$addtype} avatar (URL like http://example.com/image.png)">
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-12">
						<input type="hidden" class="form-control col-xs-6" name="slug" id="slug" value="{if isset($slug)}{$slug}{/if}">
						<input type="hidden" name="network_id" id="network-id" value="">
						<input type="hidden" name="network" id="network" value="">
						<button class="btn btn-primary col-xs-4 col-sm-2 col-sm-offset-3" type="submit">Make it!</button>
					</div>
		    </div>

			</div>

		</form>

	</div>

{if isset($smarty.get.q)}
	<h4>Is it one of these?</h4>

	<div class="col-xs-12" id="add-panels">

			{if isset($twitter_users) && count($twitter_users) > 0}
				<h6 class="text-muted"><i class="fa fa-twitter"></i> From Twitter</h6>
				<div class="row">
					<div class="col-xs-12">
						<div class="list-group">
						<!-- begin auto-fill loop here -->
						{foreach $twitter_users as $twitter_user}
							<a class="list-group-item media add-autofill" data-name="{if isset($twitter_user.full_name)}{$twitter_user.full_name}{/if}" data-avatar="{if isset($twitter_user.avatar)}{$twitter_user.avatar}{/if}" data-url="{if isset($twitter_user.url)}{$twitter_user.url}{/if}" data-description="{if isset($twitter_user.description)}{$twitter_user.description}{/if}" data-slug="{if isset($twitter_user.user_name)}{$twitter_user.user_name}{/if}" data-network-id="{$twitter_user.user_id}" data-network="twitter">
								<div class="media-left media-top">
									{if isset($twitter_user.avatar)}<img src="{$twitter_user.avatar}" id="avatar-img" width="40">{/if}
								</div>

							  <div class="media-body">
							    <h4 class="media-heading">
							    	{if isset($twitter_user.user_name)}{$twitter_user.user_name}{/if}
							    	{if isset($twitter_user.url)} <small class="text-muted">{$twitter_user.url}</small>{/if}
							    </h4>
							    <p>{if isset($twitter_user.description)}{$twitter_user.description}{/if}</p>
							  </div>
							</a>
						{/foreach}

						</div><!-- end list group -->
					</div>
				</div>
			{/if}


			{if isset($ios_apps) && count($ios_apps) > 0}
				<h6 class="text-muted"><i class="fa fa-apple"></i> From the App Store</h6>
				<div class="row">
					<div class="col-xs-12">
						<div class="list-group">
						<!-- begin auto-fill loop here -->
						{foreach $ios_apps as $ios_app}
							<a class="list-group-item media add-autofill" data-name="{if isset($ios_app.full_name)}{$ios_app.full_name}{/if}" data-avatar="{if isset($ios_app.avatar)}{$ios_app.avatar}{/if}" data-url="{if isset($ios_app.url)}{$ios_app.url}{/if}" data-description="{if isset($ios_app.description)}{$ios_app.description}{/if}" data-slug="{if isset($ios_app.user_name)}{$ios_app.user_name}{/if}">
								<div class="media-left media-top">
									{if isset($ios_app.avatar)}<img src="{$ios_app.avatar}" id="avatar-img" width="40">{/if}
								</div>

							  <div class="media-body">
							    <h4 class="media-heading">
							    	{if isset($ios_app.user_name)}{$ios_app.user_name}{/if}
							    	{if isset($ios_app.url)} <small class="text-muted">{$ios_app.url}</small>{/if}
							    </h4>
							    <p>{if isset($ios_app.description)}{$ios_app.description}{/if}</p>
							  </div>
							</a>
						{/foreach}

						</div><!-- end list group -->
					</div>
					<div class="col-xs-2">

					</div>
				</div>
			{/if}


	</div>
{/if}


</div> <!-- /add-form -->

{include file="_footer.tpl"}
