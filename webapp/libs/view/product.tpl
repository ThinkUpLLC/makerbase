{include file="_head.tpl"}

<div class="row" id="project-profile">
  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($product->avatar_url)}{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}/assets/img/blank-product.png{/if}" alt="{$product->name}" width="100%">

  </div>
  <div class="col-xs-10">

    {if isset($logged_in_user)}
      <button onclick="$('#project-profile-edit').toggle();$('#project-profile').toggle();" class="btn btn-default btn-link pull-right">edit</button>
    {else}
      <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">edit</a>
    {/if}

    <h1 {if $product->is_archived}class="archived"{/if}>We made <strong>{$product->name}</strong></h1>
    <h3>{$product->description}</h3>
    <h5><a href="{$product->url}" class="text-muted">{$product->url}</a></h5>

  </div>
</div>



<div class="row" id="project-profile-edit">


  <div class="col-xs-2">
    <img class="img-responsive" src="{if isset($product->avatar_url)}{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='m'}{else}http://makerba.se/assets/img/blank-maker.png{/if}" alt="{$product->name}" width="100%">

    <form method="post" action="/edit/product/" id="project-profile-archive" class="">
      <div class="form-group">
        <input type="hidden" name="uid" value="{$product->uid}" />
        <input type="hidden" name="archive" value="{if $product->is_archived}0{else}1{/if}"/>
        {if $product->is_archived}
        <button type="submit" class="btn btn-xs btn-success col-xs-12" id="project-profile-archive-button">
            <span class="fa fa-check"></span> <span class="hidden-xs">Unarchive</span>
        </button>
        {else}
        <button type="submit" class="btn btn-xs btn-danger col-xs-12" id="project-profile-archive-button">
            <span class="fa fa-remove"></span> <span class="hidden-xs">Archive</span>
        </button>
        {/if}
      </div>
    </form>

  </div>
  <div class="col-xs-10">

  <form method="post" action="/edit/product/" id="project-profile-edit-form">


          <div class="form-group">
            <label for="name" class="col-sm-2 hidden-xs control-label">Name</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$product->name}">
            </div>
          </div>

          <div class="form-group">
            <label for="description" class="col-sm-2 hidden-xs control-label">Description</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control" autocomplete="off" name="description" value="{$product->description}">
            </div>
          </div>

          <div class="form-group">
            <label for="url" class="col-sm-2 hidden-xs control-label">Web site</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="url" id="url" value="{$product->url}" placeholder="https://www.example.com/">
            </div>
          </div>

          <div class="form-group">
            <label for="avatar_url" class="col-sm-2 hidden-xs control-label">Project avatar</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="avatar_url" id="avatar_url" value="{$product->avatar_url}" placeholder="https://www.example.com/image.png">
            </div>
          </div>

          <input type="hidden" name="product_uid" value="{$product->uid}">
          <input type="hidden" name="product_slug" value="{$product->slug}">

          <div class="form-group">
            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
              <button class="btn btn-primary" type="submit" id="update-project">Update project</button>
            </div>
          </div>
  </form>

    <btn type="button" class="btn btn-default btn-link pull-right" onclick="$('#project-profile').toggle();$('#project-profile-edit').toggle();">cancel</a>
  </div>

</div>


<div class="row">
  <div class="col-xs-12">

    <div id="makers">

          <ul class="list-group list-unstyled col-xs-12" id="role-list">
          {foreach $roles as $role}
            <li class="">
                {include file="_role.tpl"}
            </li>
          {/foreach}
          </ul>


          <!-- add roles -->


          {if isset($logged_in_user)}

            <button class="btn btn-primary" type="submit" id="add-maker-action" data-toggle="collapse" data-target="#add-maker-form" onclick="$('#add-maker-action').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} maker</button>


            <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse" id="add-maker-form">

            <h2>Who {if $roles}else {/if} made {$product->name}?</h2>
              <input type="hidden" name="product_uid" value="{$product->uid}">
              <input type="hidden" name="originate_slug" value="{$product->slug}">
              <input type="hidden" name="originate_uid" value="{$product->uid}">
              <input type="hidden" name="originate" value="product">
              <input type="hidden" name="maker_uid" id="maker-uid">

              <div class="form-group col-xs-12">
                <label for="product_name" class="col-sm-1 control-label hidden-xs">Name</label>
                <div class="col-xs-12 col-sm-10" id="remote-search-makers">
                  <input type="text" class="typeahead form-control input-lg" placeholder="Maker's name" name="maker_name" id="maker-name">
                  <small>{$maker_guidance}</small>
                </div>
              </div>

              <div class="form-group col-xs-12">
                <label for="role" class="col-sm-1 control-label hidden-xs">Role</label>
                <div class="col-xs-12 col-sm-10">
                  <input type="text" class="form-control" autocomplete="off" id="role" name="role" placeholder="{$placeholder}">
                  <small>{$role_guidance}</small>
                </div>
              </div>

              {include file="_dates.tpl" start_m='' start_Y='' end_m='' end_Y='' dates_id='maker'}

              <div class="form-group col-xs-12">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                  <a class="btn btn-link btn-sm pull-right" data-toggle="collapse" data-target="#add-maker-form" onclick="$('#add-maker-action').toggle();" >cancel</a>
                  <button class="btn btn-primary" type="submit">Add maker</button>
                </div>
              </div>

            </form>


          {else}

            <a href="{$sign_in_with_twttr_link}" class="btn btn-primary col-offset-xs-1 col-offset-sm-3" id="add-maker-action"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</a>

          {/if}

          <!-- /add roles -->


    </div>


    <div id="history" class="history-muted">

      <h4><a href="#history">History</a></h4>

      {if sizeof($actions) > 0}
      <ul class="list-group">
      {foreach $actions as $action}
          <li class="list-group-item col-xs-12">
          {include file="_action.tpl"}
          </li>
      {/foreach}
      </ul>
      {/if}
    </div>

    <nav id="pager" class="col-xs-12">
      <ul class="pager">
        {if isset($next_page)}
          <li class="previous"><a href="/p/{$product->uid}/{$product->slug}/{$next_page}#history"><span aria-hidden="true">&larr;</span> Older</a></li>
        {/if}
        {if isset($prev_page)}
          <li class="next"><a href="/p/{$product->uid}/{$product->slug}{if $prev_page neq 1}/{$prev_page}{/if}#history">Newer <span aria-hidden="true">&rarr;</a></li>
        {/if}
      </ul>
    </nav>
  </div>
</div>



{include file="_footer.tpl"}
