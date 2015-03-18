{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-12 col-xs-12">
	<h1{if $product->is_archived} class="archived"{/if}>We made {$product->name} {if !isset($logged_in_user)}<button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button>{/if}</h1>
  </div>
</div>


<div class="row">
  <div class="col-sm-4 col-xs-12">
  	<img src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" class="img-responsive" width="100%" />
    <p>{$product->description}</p>
	<p><a href="{$product->url}">{$product->url}</a></p>

<div>

  <a {if isset($logged_in_user)}href="#edit-product"{else}href="{$sign_in_with_twttr_link}"{/if} data-toggle="collapse" type="button" class="btn btn-default btn-xs" id="edit-product-btn">
    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Edit
  </a>

</div>

{if isset($logged_in_user)}
<!-- edit form -->
<div class="form-horizontal collapse" id="edit-product">


  <form method="post" action="/edit/product/" class="edit-product-form">
    <div class="form-group">
      <input type="hidden" name="uid" value="{$product->uid}" />
      <input type="hidden" name="archive" value="{if $product->is_archived}0{else}1{/if}"/>
      <div class="col-xs-12">
        <button type="submit" class="btn btn-danger btn-xs pull-right">
            <span class="glyphicon glyphicon-remove"></span> {if $product->is_archived}Unarchive{else}Archive{/if}
        </button>
      </div>

    </div>
  </form>

  <form method="post" action="/edit/product/">
      <div class="form-group">
        <label for="full_name" class="col-xs-3 control-label">Name</label>
        <div class="col-xs-9">
          <input type="text" class="form-control" name="name" value="{$product->name}">
        </div>
      </div>
      <div class="form-group">
        <label for="description" class="col-xs-3 control-label">Description</label>
        <div class="col-xs-9">
          <input type="text" class="form-control col-xs-6" name="description" value="{$product->description}">
        </div>
      </div>
      <div class="form-group">
        <label for="url" class="col-xs-3 control-label">Web site url</label>
        <div class="col-xs-9">
          <input type="text" class="form-control col-xs-6" name="url" value="{$product->url}">
        </div>
      </div>
      <div class="form-group">
        <label for="url" class="col-xs-3 control-label">Avatar url</label>
        <div class="col-xs-9">
          <input type="text" class="form-control col-xs-6" name="avatar_url" value="{$product->avatar_url}">
        </div>
      </div>
      <input type="hidden" name="product_uid" value="{$product->uid}">
      <input type="hidden" name="product_slug" value="{$product->slug}">

      <div class="form-group">
          <label for="update-product" class="col-xs-3 control-label"></label>
          <div class="col-xs-9">
              <button class="btn btn-primary" type="submit" id="update-product">Update product</button>
          </div>
      </div>
  </form>



</div>
{/if}

</p>

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
			<input type="hidden" name="product_uid" value="{$product->uid}">
      <input type="hidden" name="originate_slug" value="{$product->slug}">
      <input type="hidden" name="originate_uid" value="{$product->uid}">
      <input type="hidden" name="originate" value="product">
			<div class="form-group">
				<label for="maker_slug" class="col-sm-3 control-label">Maker:</label>
				<div class="col-sm-9">
					<div class="input-group" id="remote-search-makers">
						<input type="text" class="form-control typeahead" placeholder="" name="maker_uid" id="maker-uid">
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
        <label for="role" class="col-sm-3 control-label"></label>
        <div class="col-sm-9">
          <a href="#show-role-dates" data-toggle="collapse" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-calendar"></i> <i class="caret"></i></a>
        </div>
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

      <button class="btn btn-primary col-sm-offset-3" type="submit">Add a maker</button>
    </form>
{else}
    <a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a{if $roles}nother{/if} maker</a>
{/if}

  </div>

</div>


{include file="_footer.tpl"}