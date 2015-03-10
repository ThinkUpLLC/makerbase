{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-12 col-xs-12">
	<h1>{$maker->name} is a maker {if !isset($logged_in_user)}<button type="button" class="btn btn-sm btn-success pull-right"><i class="glyphicon glyphicon-user"></i> Hey, I'm {$maker->name}!</button>{/if}
	</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-5 col-xs-12">
    <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$maker->name}" width="100%">

	<p><a href="{$maker->url}">{$maker->url}</a></p>

<p>
<a href="#">
<button type="button" class="btn btn-default" aria-label="Center Align">
  <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
</button>
</a>
<a href="#">
<button type="button" class="btn btn-default" aria-label="Center Align">
  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
</button>
</a>
</p>

    {if sizeof($actions) > 0}
    <h4>History</h4>
    <ul class="list-unstyled">
    {foreach $actions as $action}
        <li class="">
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
        {include file="_role.tpl"}
		</li>
	{/foreach}
	</ul>

 {if isset($logged_in_user)}
    <form method="post" action="/add/role/" class="form-horizontal">
      <input type="hidden" name="maker_slug" value="{$maker->slug}">
      <div class="form-group">
        <label for="maker_slug" class="col-sm-3 control-label">Product:</label>
        <div class="col-sm-9">
          <div class="input-group" id="remote-search-products">
            <span class="input-group-addon" id="basic-addon1">@</span>
            <input type="text" class="form-control typeahead" placeholder="Twitter handle" name="product_slug">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="role" class="col-sm-3 control-label">Role:</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" id="role" name="role" placeholder="Herded unicorns">
        </div>
      </div>
      <div class="col-sm-offset-3">
        <a href="#show-role-dates-{$role->id}" data-toggle="collapse">Dates...</a>
      </div>
      <div class="form-group collapse" id="show-role-dates-{$role->id}">
        <label for="start_date" class="col-sm-3 control-label">From:</label>
        <div class="col-sm-9">
          <div class="input-daterange input-group" id="datepicker">
            <input type="text" class="input-sm form-control" name="start_date" id="start_date" placeholder="YYYY-MM" data-provide="datepicker" autocomplete="off"/>
            <span class="input-group-addon">to</span>
            <input type="text" class="input-sm form-control" name="end_date" id="end_date" placeholder="Leave blank if current" autocomplete="off" />
          </div>
        </div>

      </div>

      <button class="btn btn-primary col-sm-offset-3" type="submit">Add a product</button>
    </form>
{else}
  <a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a{if $roles}nother{/if} project</a>
{/if}

  </div>
</div>

{include file="_footer.tpl"}