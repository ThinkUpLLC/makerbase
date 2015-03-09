{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-12 col-xs-12">
	<h1>We made {$product->name} {if !isset($logged_in_user)}<button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button>{/if}</h1>
  </div>
</div>


<div class="row">
  <div class="col-sm-5 col-xs-12">
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
  <div class="col-sm-7 col-xs-12">
  	<ul class="list-group">
  		{foreach $roles as $role}
		<li class="list-group-item">
			<span class="badge">{if isset($role->years) && $role->years > 0}{$role->years} year{if $role->years neq 1}s{/if}{else}{if isset($role->start_MY)}{$role->start_MY}{/if}{/if}</span>
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
				<label for="role" class="col-sm-3 control-label">Role:</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" id="role" name="role" placeholder="Herded unicorns">
				</div>
			</div>
      <div class="form-group">
        <label for="start_date" class="col-sm-3 control-label">From:</label>
        <div class="col-sm-9">
          <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="start_date" id="start_date" placeholder="YYYY-MM" data-provide="datepicker" autocomplete="off"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm form-control" name="end_date" id="end_date" placeholder="Leave blank if current" autocomplete="off" />
          </div>
        </div>
			</div>

      <button class="btn btn-primary col-sm-offset-3" type="submit">Add a maker</button>
    </form>
{else}
    <a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a{if $roles}nother{/if} maker</a>
{/if}

  </div>

</div>


{include file="_footer.tpl"}