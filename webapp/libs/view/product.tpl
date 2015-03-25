{include file="_head.tpl"}


<h1 {if $product->is_archived} class="archived"{/if}>We made {$product->name} {if !isset($logged_in_user)}<button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button>{/if}</h1>


<div class="row">
  <div class="col-xs-2 col-sm-4">
    <img src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" class="img-responsive main-avatar" alt="{$product->name}" />

    <div class="history hidden-xs">
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

  </div>

  <div class="col-xs-10 col-sm-8">

    <h3>{$product->description}</h3>
    <a {if isset($logged_in_user)}href="#edit-product"{else}href="{$sign_in_with_twttr_link}"{/if} data-toggle="collapse" class="btn btn-default btn-xs btn-link pull-right" id="edit-product-btn">
      <span class="fa fa-pencil" aria-hidden="true"></span> Edit
    </a>
    <p><a href="{$product->url}">{$product->url}</a></p>

  </div>

  {if isset($logged_in_user)}

  <div class="col-xs-12 col-sm-8 collapse" id="edit-product">

    <div class="row">
      <div class="col-xs-12">
        <form method="post" action="/edit/product/" class="form-horizontal">
          <div class="form-group">
            <input type="hidden" name="uid" value="{$product->uid}" />
            <input type="hidden" name="archive" value="{if $product->is_archived}0{else}1{/if}"/>
            <button type="submit" class="btn btn-xs btn-danger pull-right">
                <span class="fa fa-remove"></span> {if $product->is_archived}Unarchive{else}Archive{/if}
            </button>
          </div>
        </form>
      </div>
    </div>

  <!-- edit form -->

    <div class="row">
      <form method="post" action="/edit/product/" class="edit-product-form form-horizontal">
          <div class="form-group">
            <label for="full_name" class="col-xs-2 col-sm-2 control-label">Name</label>
            <div class="col-xs-10 col-sm-10">
              <input type="text" autocomplete="off" class="form-control" name="name" value="{$product->name}">
            </div>
          </div>
          <div class="form-group">
            <label for="description" class="col-xs-2 col-sm-2 control-label">Description</label>
            <div class="col-xs-10 col-sm-10">
              <input type="text" class="form-control" autocomplete="off" name="description" value="{$product->description}">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-xs-2 col-sm-2 control-label">Web site</label>
            <div class="col-xs-10 col-sm-10">
              <input type="text" class="form-control" autocomplete="off" name="url" value="{$product->url}">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-xs-2 col-sm-2 control-label">Avatar</label>
            <div class="col-xs-10 col-sm-10">
              <input type="text" class="form-control" autocomplete="off" name="avatar_url" value="{$product->avatar_url}">
            </div>
          </div>
          <input type="hidden" name="product_uid" value="{$product->uid}">
          <input type="hidden" name="product_slug" value="{$product->slug}">

          <div class="form-group">
              <label for="update-product" class="col-xs-2 col-sm-2 control-label"></label>
              <div class="col-xs-10 col-sm-10">
                  <button class="btn btn-primary" type="submit" id="update-product">Update product</button>
              </div>
          </div>
      </form>
    </div>

  <!-- / edit=product -->
  </div>
  {/if}

    <div class="col-sm-8 col-sm-offset-0 col-xs-10 col-xs-offset-2">
      <ul class="list-group">
        {foreach $roles as $role}
        <li class="list-group-item">
          {include file="_role.tpl"}
        </li>
        {/foreach}
      </ul>


  {if isset($logged_in_user)}

      <h4>Add a maker to {$product->name}</h4>

      <form method="post" action="/add/role/" class="form-horizontal">
          <input type="hidden" name="product_uid" value="{$product->uid}">
          <input type="hidden" name="originate_slug" value="{$product->slug}">
          <input type="hidden" name="originate_uid" value="{$product->uid}">
          <input type="hidden" name="originate" value="product">
          <input type="hidden" placeholder="" name="maker_uid" id="maker-uid">
          <div class="form-group">
            <label for="maker_slug" class="col-sm-2 col-xs-2 control-label">Name:</label>
            <div class="col-sm-9 col-xs-8">
              <div class="input-group" id="remote-search-makers">
                <input type="text" class="form-control typeahead" placeholder="" name="maker_name" id="maker-name">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="role" class="col-sm-2 col-xs-2 control-label">Role:</label>
            <div class="col-sm-9 col-xs-8">
              <input type="text" class="form-control" id="role" autocomplete="off" name="role" placeholder="Herded unicorns">
            </div>
          </div>

          <div class="form-group">
            <label for="role" class="col-sm-2 col-xs-2 control-label"></label>
            <div class="col-sm-9 col-xs-8">
              <a href="#show-role-dates" data-toggle="collapse" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i> <i class="caret"></i></a>
            </div>
          </div>

          <div class="form-group collapse" id="show-role-dates">
            <label for="start_date" class="col-sm-2 col-xs-2 control-label">From:</label>
            <div class="col-sm-9 col-xs-7">
              <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control" name="start_date" id="start_date" placeholder="YYYY-MM" data-provide="datepicker" autocomplete="off"/>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control" name="end_date" id="end_date" placeholder="Leave blank if current" autocomplete="off" />
              </div>
            </div>
          </div>

          <button class="btn btn-primary col-sm-offset-2 col-xs-offset-2" type="submit">Add a maker</button>

      </form>

  {else}

      <a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm col-sm-4"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} maker</a>

  {/if}

    </div>

</div>



<div class="col-sm-8 col-sm-offset-4 col-xs-10 col-xs-offset-2">
  <!-- history -->
</div>

{include file="_footer.tpl"}
