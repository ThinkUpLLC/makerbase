{include file="_head.tpl"}

<h1>we made {$product->name} <button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button></h1>

<div class="row">
  <div class="col-xs-5">
  	<img src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" class="img-responsive" width="100%" />
    <p>{$product->description}</p>
	<p><a href="{$product->url}">{$product->url}</a></p>

    {if sizeof($actions) > 0}
    <h4>History</h4>
    <ul class="list-unstyled">
    {foreach $actions as $action}
        <li>
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
			<span class="badge">{if isset($role->years) && $role->years > 0}{$role->years} year{if $role->years neq 1}s{/if}{else}{$role->start_MY}{/if}</span>
  			<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$role->maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="maker" width="100">
			</div>
			<div class="media-body">
				<h3><a href="/m/{$role->maker->slug}">{$role->maker->name}</a></h3>
				{$role->role}
			</div>
		</li>
  		{/foreach}
	</ul>

  {if isset($logged_in_user)}
    <form method="post" action="/add/role/" class="form-horizontal">
			<input type="hidden" name="product_slug" value="{$product->slug}">
			<div class="form-group">
				<label for="maker_slug" class="col-sm-3 control-label">Maker:</label>
				<div class="col-sm-9">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">@</span>
						<input type="text" class="form-control" placeholder="Twitter username" name="maker_slug">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="start_date" class="col-sm-3 control-label">Start date:</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" id="start_date" name="start_date" placeholder="YYYY-MM-DD">
				</div>
			</div>
			<div class="form-group">
				<label for="end_date" class="col-sm-3 control-label">End date:</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" id="end_date" name="end_date" placeholder="YYYY-MM-DD">
				</div>
			</div>
			<div class="form-group">
				<label for="role" class="col-sm-3 control-label">Role:</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" id="role" name="role" placeholder="Herded unicorns">
				</div>
			</div>

      <button class="btn btn-primary col-sm-offset-3" type="submit">Add a maker</button>
    </form>
{else}
    <a href="{$sign_in_with_twttr_link}"><button type="button" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a{if $roles}nother{/if} maker</button>
{/if}

  </div>

</div>


{include file="_footer.tpl"}