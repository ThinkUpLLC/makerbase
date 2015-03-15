{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-12 col-xs-12">
	<h1>{$maker->name} is a maker {if !isset($logged_in_user)}<button type="button" class="btn btn-sm btn-success pull-right"><i class="glyphicon glyphicon-user"></i> Hey, I'm {$maker->name}!</button>{/if}
	</h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 col-xs-12">
    <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="{$maker->name}" width="100%">

	<p><a href="{$maker->url}">{$maker->url}</a></p>

<p>
<a {if isset($logged_in_user)}href="#edit-maker" data-toggle="collapse"{else}href="{$sign_in_with_twttr_link}"{/if} type="button" class="btn btn-default btn-xs" aria-label="Center Align" id="edit-role-btn">
    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
</a>


{if isset($logged_in_user)}
<!-- edit form -->
<div class="media-footer" class="form-horizontal collapse" id="edit-maker">
<form method="post" action="/edit/maker/">
    <div class="form-group">
      <label for="full_name" class="col-xs-3 control-label">Name</label>
      <div class="col-xs-9">
        <input type="text" class="form-control" name="name" value="{$maker->name}">
      </div>
    </div>
    <div class="form-group">
      <label for="url" class="col-xs-3 control-label">Web site url</label>
      <div class="col-xs-9">
        <input type="text" class="form-control col-xs-6" name="url" value="{$maker->url}">
      </div>
    </div>
    <div class="form-group">
      <label for="url" class="col-xs-3 control-label">Avatar url</label>
      <div class="col-xs-9">
        <input type="text" class="form-control col-xs-6" name="avatar_url" value="{$maker->avatar_url}">
      </div>
    </div>
    <input type="hidden" name="maker_uid" value="{$maker->uid}">
    <div class="form-group">
        <label for="update-maker" class="col-sm-2 control-label"></label>
        <div class="col-sm-9">
            <button class="btn btn-primary" type="submit" id="update-maker">Update maker</button>
        </div>
    </div>
</form>

<!-- archive form (PLEASE DON'T HATE ME)-->
<div>
  <form method="post" action="/edit/maker/">
      <div class="form-group">
      <input type="hidden" name="uid" value="{$maker->uid}" />
      <input type="hidden" name="archive" value="{if $maker->is_archived}0{else}1{/if}"/>
      <!-- Why oh why won't this button submit
      <button type="button" type="submit" class="btn btn-danger pull-right" aria-label="Center Align">
          <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete maker
      </button>-->
      <input type="submit" value="{if $maker->is_archived}Unarchive{else}Archive{/if} maker" />
    </div>
  </form>
</div>

</div>
{/if}

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
  <div class="col-sm-8 col-xs-12">
  	<ul class="list-group">
	{foreach $roles as $role}
		<li class="list-group-item">
        {include file="_role.tpl"}
		</li>
	{/foreach}
	</ul>

 {if isset($logged_in_user)}
    <form method="post" action="/add/role/" class="form-horizontal">
      <input type="hidden" name="maker_uid" value="{$maker->uid}">
      <input type="hidden" name="originate_slug" value="{$maker->slug}">
      <input type="hidden" name="originate_uid" value="{$maker->uid}">
      <input type="hidden" name="originate" value="maker">
      <div class="form-group">
        <label for="maker_slug" class="col-sm-3 control-label">Product:</label>
        <div class="col-sm-9">
          <div class="input-group" id="remote-search-products">
            <span class="input-group-addon" id="basic-addon1">@</span>
            <input type="text" class="form-control typeahead" placeholder="" name="product_uid">
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
        <a href="#show-role-dates" data-toggle="collapse">Dates...</a>
      </div>
      <div class="form-group collapse" id="show-role-dates">
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