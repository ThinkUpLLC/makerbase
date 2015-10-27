{include file="_head.tpl"}

<div class="row" id="project-info">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-left media-top">
        <img class="img-responsive" src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" alt="{$product->name|escape}">

        {if isset($product->autofill_network_username) && $product->autofill_network eq 'twitter'}
          {include file="_twitterprofile.tpl"  twitter_user_id=$product->autofill_network_id}
        {/if}

        {include file="_reportpage.tpl" object=$product object_type='project'}

      </div>
      <div class="media-body">
        {if isset($logged_in_user)}
          <button onclick="$('#project-info-edit').toggle();$('#project-info').toggle();" class="btn btn-default btn-link pull-right">Edit</button>
        {else}
          <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">Edit</a>
        {/if}
        <h1 {if $product->is_archived}class="archived"{/if}>We made <strong>{$product->name|escape}</strong></h1>
        <h3>{$product->description|escape|atnames:'/search/?q='}</h3>
        <h5><a href="{if $product->uid eq 'm348b6'}https://slack.com/?cvosrc=general%20promotion.makerbase.slack%20page&amp;utm_source=makerbase&amp;utm_medium=general%20promotion&amp;utm_campaign=slack%20page{else}{$product->url}{/if}" class="text-muted"  rel="nofollow">{$product->url}</a></h5>

        {if isset($logged_in_user)}
           {if !$product->is_archived}
            <div id="unfollow{$product->uid}" {if $logged_in_user->is_following_product eq false}style="display:none"{/if}><a class="btn btn-md btn-default btn-info btn-unfollow" uid="{$product->uid}" type="project" style="padding: 6px 12px;">Following</a></div>
            <div id="follow{$product->uid}" {if $logged_in_user->is_following_product eq true}style="display:none"{/if}><a class="btn btn-md btn-default btn-follow" uid="{$product->uid}" type="project" style="padding: 6px 12px;{if $logged_in_user->is_following_product eq true}display:none{/if}">Follow</a></div>
           {/if}
        {else}
           <a class="btn btn-md btn-default" style="padding: 6px 12px;" href="{$sign_in_with_twttr_link}">Follow</a>
        {/if}
      </div>
    </div>

  </div>
</div>

{if isset($logged_in_user)}
<div class="row" id="project-info-edit">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-left media-top">
        <img class="img-responsive" src="{if isset($product->avatar_url)}{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}{else}{$site_root_path}assets/img/blank-project.png{/if}" alt="{$product->name|escape}">

        <form method="post" action="/edit/product/" id="project-profile-archive" class="">
          <div class="form-group">
            <input type="hidden" name="uid" value="{$product->uid}" />
            <input type="hidden" name="slug" value="{$product->slug}" />
            <input type="hidden" name="archive" value="{if $product->is_archived}0{else}1{/if}"/>
            {if $product->is_archived}
            <button type="submit" class="btn btn-xs btn-success col-xs-12" id="project-profile-archive-button">
                <span class="fa fa-check"></span> <span class="hidden-xs">Unarchive</span>
            </button>
            {else}
            <button type="submit" class="btn btn-xs btn-danger col-xs-12" id="project-profile-archive-button">
                <span class="fa fa-remove"></span> <span class="">Archive</span>
            </button>
            {/if}
          </div>
        </form>

      </div>
      <div class="media-body">

        <div class="col-xs-12">
          <button onclick="$('#project-info-edit').toggle();$('#project-info').toggle();" class="btn btn-default btn-link pull-right" id="project-info-edit-cancel">Cancel</button>
        </div>
        <form method="post" action="/edit/product/" id="project-profile-edit-form">
          <div class="form-group">
            <label for="name" class="col-sm-2 hidden-xs control-label">Name</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$product->name|escape}">
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
              <button class="btn btn-info pull-right" type="submit" id="update-project">Update project</button>

            </div>
          </div>
        </form>


      </div>
    </div>

  </div>
</div>
{/if}

{if sizeof($madewiths) > 0}
<div class="row" id="made-with">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <ul class="list-inline list-unstyled">
      <li class="">{if sizeof($madewiths) < 3}{$product->name|escape} was {/if}made with </li>
      {foreach $madewiths as $madewith}
      <li class="" >
        <form method="post" action="/edit/madewith/" class="form-inline madewith-archive pull-left">
          <input type="hidden" name="madewith_uid" value="{$madewith->uid}">
          <input type="hidden" name="archive" value="1"/>
          <input type="hidden" name="originate_slug" value="{$product->slug}">
          <input type="hidden" name="originate_uid" value="{$product->uid}">
          <button class="btn btn-link btn-xs" type="submit"><i class="fa fa-close"></i></button>
        </form>
        <a href="/p/{$madewith->used_product->uid}/{$madewith->used_product->slug}" class=""><img src="{$madewith->used_product->avatar_url}"/> {$madewith->used_product->name}</a>
      </li>
      {/foreach}
    </ul>
  </div>
</div>
{/if}

{if isset($used_by_madewiths) && sizeof($used_by_madewiths) > 0}
<div class="row" id="usedby">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h2><a href="#usedby"><strong>{$product->name|escape}</strong> is used by</a></h2>

    {foreach $used_by_madewiths as $used_by_madewith}
    <div class="list-group-item col-xs-12 col-md-6" id="usedby-madewith-{$used_by_madewith->uid}">
      <div class="media-left media-top">
          <a href="/p/{$used_by_madewith->product->uid}/{$used_by_madewith->product->slug}">
          <img class="media-object" src="{insert name='user_image' image_url=$used_by_madewith->product->avatar_url image_proxy_sig=$image_proxy_sig type='p'}" alt="{$used_by_madewith->product->name} logo" width="30" height="30">
          </a>
      </div>
      <div class="media-body">
          <h3><a href="/p/{$used_by_madewith->product->uid}/{$used_by_madewith->product->uid}">{$used_by_madewith->product->name}</a></h3>
      </div>
    </div>
    {/foreach}
  </div>
</div>
{/if}

<div class="row" id="roles">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    {if isset($roles) && count($roles) > 0}
    <h2><a href="#roles"><strong>{$product->name|escape}</strong> is made by</a></h2>
    {/if}

    <ul class="list-unstyled" id="role-list">
    {foreach $roles as $role}
      <li class="">
          {include file="_role.tpl"}
      </li>
    {/foreach}
      <li>
        <div class="list-group-item style-transparent" id="role-ghost">
            <div class="media-left media-top">
              <img class="media-object" src="/assets/img/ghost-object.png" alt="logo" width="50" height="50">
            </div>
            <div class="media-body">
                <h3>What's missing?</h3>
                <div id="role-description-ghost">
                  <p>Makerbase is edited by our community, and might be incomplete&mdash;you can help fill it in!</p>
               </div>
            </div>
        </div>
      </li>

    </ul>

  </div>
</div>


<div class="row" id="makers">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

          <!-- add a maker -->
          {if isset($logged_in_user)}

            <button class="btn btn-info pull-right" type="submit" id="add-role" data-toggle="collapse" data-target="#add-role-form" onclick="$('#maker-name').focus();$('#add-role').toggle();$('#add-role-cancel').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} maker</button>

            <button class="btn btn-link pull-right" type="submit" id="add-role-cancel" data-toggle="collapse" data-target="#add-role-form" onclick="$('#add-role-cancel').toggle();$('#add-role').toggle();" > Cancel </button>

            <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse add-form" id="add-role-form">

            <h2>Who {if $roles}else {/if} made {$product->name|escape}?</h2>
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

              <div id="maker-extended-form" class="collapse">
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
                    <button class="btn btn-primary" type="submit" id="add-action">Add maker</button>
                  </div>
                </div>
              </div>

            </form>


          {else}
            <a href="{$sign_in_with_twttr_link}" class="btn btn-info pull-right" id="add-role"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} maker</a>

          {/if}
          <!-- /add a maker -->
  </div>
</div>

{if sizeof($uses_this_buttons) > 0}
<div class="row" id="use-these">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div id="use-these-actions">
      <h4>Use any of these tools to make {$product->name|escape}?</h4>
        <div class="use-these-actions-buttons" style="">
          {if isset($logged_in_user)}
              {foreach $uses_this_buttons as $uses_with_button}
              <form method="post" action="/add/madewith/" class="form-inline">
                <div class=" form-group">
                  <input type="hidden" name="product_uid" value="{$product->uid}">
                  <input type="hidden" name="product_used_uid" value="{$uses_with_button.uid}">
                  <input type="hidden" name="originate_slug" value="{$product->slug}">
                  <input type="hidden" name="originate_uid" value="{$product->uid}">
                  <button class=" btn btn btn-default" type="submit" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="{$uses_with_button.name} sponsors Makerbase" data-content="If {$product->name|escape} uses {$uses_with_button.name}, you can support Makerbase by saying so."><img src="{$uses_with_button.avatar_url}" style="width: 16px;"/>&nbsp;{$uses_with_button.name}</button>
                </div>
              </form>
              {/foreach}

          {else}

            {foreach $uses_this_buttons as $uses_with_button}
              <a class=" btn btn-sm btn-default" href="{$sign_in_with_twttr_link}" style="margin-right: 10px;" data-toggle="popover" data-placement="bottom" data-trigger="hover" title="{$uses_with_button.name} sponsors Makerbase" data-content="If {$product->name|escape} uses {$uses_with_button.name}, you can support Makerbase by saying so."><img src="{$uses_with_button.avatar_url}" style="width: 16px"/>{$uses_with_button.name}</a>
            {/foreach}
          {/if}
        </div>
    </div>
  </div>
</div>
{/if}

  {include file="_actions.tpl"}

{include file="_footer.tpl"}
