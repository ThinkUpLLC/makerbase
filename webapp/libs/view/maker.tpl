{include file="_head.tpl"}

<div class="row" id="maker-info">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-left media-top">
        <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name|escape}">

        {if isset($maker->autofill_network_username) && $maker->autofill_network eq 'twitter'}
          {include file="_twitterprofile.tpl"  twitter_user_id=$maker->autofill_network_id}
        {/if}
        {include file="_reportpage.tpl"  object=$maker object_type='maker'}

      </div>
      <div class="media-body">
        <div id="maker-info-profile">
            {if isset($logged_in_user)}
              <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right">Edit</button>
            {else}
              <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">Edit</a>
            {/if}
          <h1 {if $maker->is_archived}class="archived"{/if}>
            <strong>{$maker->name|escape}</strong> is a maker
          </h1>
          <h5><a href="{$maker->url}" class="text-muted" rel="nofollow">{$maker->url}</a></h5>
          {if isset($maker->user)}
            <p class="text-muted"><span class="fa fa-certificate"></span> {$maker->user->twitter_username|escape} also <a class="text-muted" href="/u/{$maker->user->uid}">edits Makerbase</a></p>
          {else}
            <p class="text-muted">Our community added {$maker->name|escape} to Makerbase.</p>
          {/if}
        </div>
      </div>
    </div>

  </div>
</div>


{if isset($logged_in_user)}
<div class="row" id="maker-info-edit">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-left media-top">
        <img class="img-responsive" src="{if isset($maker->avatar_url)}{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='p'}{else}{$site_root_path}assets/img/blank-maker.png{/if}" alt="{$maker->name|escape}">

        <form method="post" action="/edit/maker/" id="maker-profile-archive" class="add-form">
          <div class="form-group">
            <input type="hidden" name="uid" value="{$maker->uid}" />
            <input type="hidden" name="slug" value="{$maker->slug}" />
            <input type="hidden" name="archive" value="{if $maker->is_archived}0{else}1{/if}"/>
            {if $maker->is_archived}
            <button type="submit" class="btn btn-xs btn-success col-xs-12" id="maker-profile-archive-button">
                <span class="fa fa-check"></span> <span class="hidden-xs">Unarchive</span>
            </button>
            {else}
            <button type="submit" class="btn btn-xs btn-danger col-xs-12" id="maker-profile-archive-button">
                <span class="fa fa-remove"></span> <span class="">Archive</span>
            </button>
            {/if}
          </div>
        </form>

      </div>
      <div class="media-body">
        <div class="col-xs-12">
          <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right" id="maker-info-edit-cancel">Cancel</button>
        </div>

        <form method="post" action="/edit/maker/" id="maker-profile-edit-form">
          <div class="form-group">
            <label for="name" class="col-sm-2 hidden-xs control-label">Name</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$maker->name|escape}">
            </div>
          </div>

          <div class="form-group">
            <label for="url" class="col-sm-2 hidden-xs control-label">Web site</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="url" id="url" value="{$maker->url}" placeholder="https://www.example.com/">
            </div>
          </div>

          <div class="form-group">
            <label for="avatar_url" class="col-sm-2 hidden-xs control-label">Maker avatar</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-sm" autocomplete="off" name="avatar_url" id="avatar_url" value="{$maker->avatar_url}" placeholder="https://www.example.com/image.png">
            </div>
          </div>

          <input type="hidden" name="maker_uid" value="{$maker->uid}">
          <input type="hidden" name="maker_slug" value="{$maker->slug}">

          <div class="form-group">
            <div class="col-xs-12 col-sm-10 col-sm-offset-2">
              <button class="btn btn-info pull-right" type="submit" id="update-maker">Update maker</button>
            </div>
          </div>
        </form>

      </div>
    </div>

  </div>
</div>
{/if}

<div class="row" id="maker-tabs">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <ul class="btn-group btn-group-justified" role="group tablist" id="maker-tab-set">
      <li role="presentation" class="btn-group{if $active_tab eq "projects"} active{/if}">
        <a href="/m/{$maker->uid}/{$maker->slug}/projects" class="btn btn-default" role="tab">Projects</a>
      </li>
      <li role="presentation" class="btn-group{if $active_tab eq "collaborators"} active{/if}">
        <a href="/m/{$maker->uid}/{$maker->slug}/collaborators" class="btn btn-default" role="tab">Collaborators</a>
      </li>
      <li role="presentation" class="btn-group{if $active_tab eq "inspirations"} active{/if}">
        <a href="/m/{$maker->uid}/{$maker->slug}/inspirations" class="btn btn-default" role="tab">Inspirations</a>
      </li>
    </ul>

  </div>
</div>

{** PROJECTS **}

{if isset($roles)}
<div class="tab-content">
  <div role="tabpanel" class="tab-pane in active" id="projects-tab">

    <div class="row" id="roles">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

      {if count($roles) > 0}
        <h3>{$maker->name|escape} made:</h3>
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
                    <h3>What {if count($roles) > 0}else {/if}did {$maker->name|escape} make?</h3>
                    <div id="role-description-ghost">
                      <p>Makerbase is edited by our community, and might be incomplete&mdash;you can help fill it in!</p>
                   </div>
                </div>
            </div>
          </li>

        </ul>

      </div>
    </div>


    <div class="row" id="projects">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">
        <div>

              {if isset($logged_in_user)}

                <button class="btn btn-info pull-right" type="submit" id="add-role" data-toggle="collapse" data-target="#add-role-form" onclick="$('#product-name').focus();$('#add-role').toggle();$('#add-role-cancel').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</button>

                <button class="btn btn-link pull-right" type="submit" id="add-role-cancel" data-toggle="collapse" data-target="#add-role-form" onclick="$('#add-role-cancel').toggle();$('#add-role').toggle();" > Cancel </button>

                <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse add-form" id="add-role-form">

                <h3>What {if $roles}else {/if}did {$maker->name|escape} make?</h3>
                  <input type="hidden" name="maker_uid" value="{$maker->uid}">
                  <input type="hidden" name="originate_slug" value="{$maker->slug}">
                  <input type="hidden" name="originate_uid" value="{$maker->uid}">
                  <input type="hidden" name="originate" value="maker">
                  <input type="hidden" name="product_uid" id="product-uid">

                  <div class="form-group col-xs-12">
                    <label for="product_name" class="col-sm-1 control-label hidden-xs">Project</label>
                    <div class="col-xs-12 col-sm-10" id="remote-search-products">
                      <input type="text" class="typeahead form-control input-lg" placeholder="Project name" name="product_name" id="product-name">
                      <small>{$project_guidance}</small>
                    </div>
                  </div>

                  <div id="product-extended-form" class="collapse">
                    <div class="form-group col-xs-12">
                      <label for="role" class="col-sm-1 control-label hidden-xs">Role</label>
                      <div class="col-xs-12 col-sm-10">
                        <input type="text" class="form-control" autocomplete="off" id="role" name="role" placeholder="{$role_placeholder}">
                        <small>{$role_guidance}</small>
                      </div>
                    </div>

                    {include file="_dates.tpl" start_m='' start_Y='' end_m='' end_Y='' dates_id='project'}

                    <div class="form-group col-xs-12">
                      <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                        <button class="btn btn-primary" type="submit" id="add-action">Add project</button>
                      </div>
                    </div>
                  </div>
                </form>


              {else}

                <a href="{$sign_in_with_twttr_link}" class="btn btn-info pull-right" id="add-role"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</a>

              {/if}


            </div>
      </div>
    </div>

    {include file="_actions.tpl"}

  </div>
{/if}
{* /PROJECTS *}


{* COLLABORATORS *}
{if isset($collaborators)}
<div role="tabpanel" class="tab-pane" id="collaborators">

    <div class="row" id="">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

{if sizeof($collaborators) > 0}
        <h3>{$maker->name|escape} collaborated with:</h3>
        <ul class="list-group" id="collaborator-list">
          {foreach $collaborators as $collaborator}
          <li class="list-group-item">
            <div class="media-left media-top">
              <a href="/m/{$collaborator.uid}/{$collaborator.slug}"><img class="media-object" src="{insert name='user_image' image_url=$collaborator.avatar_url image_proxy_sig=$image_proxy_sig type='m'}" width="50" height="50"></a>
            </div>

            <div class="media-body">
              <h3><a href="/m/{$collaborator.uid}/{$collaborator.slug}" style="padding:0">{$collaborator.name|escape}</a> on {$collaborator.total_collaborations} projects</h3>

              {foreach $collaborator.projects as $project name="collaborated_projects"}
                <a href="/p/{$project->uid}/{$project->slug}">

                <img src="{insert name='user_image' image_url=$project->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">

                {$project->name|escape}</a>
              {/foreach}
            </div>
          </li>
          {/foreach}
        </ul>
{else}
    {if isset($collaborated_projects) && count($collaborated_projects) > 0}
      <p>Who collaborated with {$maker->name|escape}? Add other makers to {foreach $collaborated_projects as $role}{if $role@last} or {else}{if !$role@first}, {/if}{/if}<a href="/p/{$role->product->uid}/{$role->product->slug}">{$role->product->name|escape}</a>{/foreach}.</p>
    {else}
      <p>Who collaborated with {$maker->name|escape}? <a href="/m/{$maker->uid}/{$maker->slug}/projects">Add {$maker->name|escape}'s projects</a> to find out.</p>
    {/if}
{/if}
      </div>
    </div>
  </div>
{/if}
{* /COLLABORATORS *}


{* INSPIRATIONS *}

{if isset($inspirations) }
  <div role="tabpanel" class="tab-pane" id="inspirations">

    {if sizeof($inspired_makers) > 0}
    <div class="row" id="inspirations" style="margin-bottom:2em;">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

            <h3>{$maker->name|escape} inspires:</h3>
            {foreach $inspired_makers as $inspired_maker}

                {capture name="hideform"}
                  {if $is_maker_user eq true}
                  <form method='post' action='/edit/inspiration/' class='form-inline pull-right' id='hide-inspiration-form'>
                    <input type='hidden' name='originate_slug' value='{$maker->slug}'>
                    <input type='hidden' name='originate_uid' value='{$maker->uid}'>
                    <input type='hidden' name='uid' value='{$inspired_maker->inspiration_uid}'>
                    <input type='hidden' name='hide' value='1'>
                    <button type='submit' class='btn btn-sm btn-link pull-right'>Hide</button>
                  </form>
                  {/if}
                {/capture}

              <div class="inspiree">
                <div class="">
                  <a tabindex="0" role="button"
                   data-toggle="popover" data-placement="bottom" data-trigger="focus" data-html="true" data-content="{$smarty.capture.hideform} {$inspired_maker->inspiration_description|escape}"
                    data-title="<a href='/m/{$inspired_maker->uid}/{$inspired_maker->slug}'>{$inspired_maker->name|escape}</a>">
                    <img  src="{insert name='user_image' image_url=$inspired_maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" width="50">
                  </a>
                </div>

              </div>
            {/foreach}
      </div>
    </div>
      {/if}

    <div class="row" id="">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">





        {if sizeof($inspirations) > 0}
          <h3>{$maker->name|escape} is inspired by:</h3>

        <ul class="list-group" id="inspiration-list">

          {foreach $inspirations as $inspiration}
          <li class="list-group-item">

            <div class="media-left media-top">
              <a href="/m/{$inspiration->uid}/{$inspiration->slug}">
                <img class="media-object" src="{insert name='user_image' image_url=$inspiration->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" width="50" height="50">
              </a>
            </div>

            <div class="media-body">
              {if $is_maker_user eq true}
                <form method="post" action="/edit/inspiration/" class="form-inline col-xs-12 add-form" id="archive-inspiration-form">
                  <input type="hidden" name="originate_slug" value="{$maker->slug}">
                  <input type="hidden" name="originate_uid" value="{$maker->uid}">
                  <input type="hidden" name="uid" value="{$inspiration->inspiration_uid}">
                  <input type="hidden" name="delete" value="1">
                  <button type="submit" class="btn btn-sm btn-link pull-right" name="Delete">Archive</button>
                {/if}

                <h3>
                  <a href="/m/{$inspiration->uid}/{$inspiration->slug}">{$inspiration->name|escape}</a>
                </h3>
                <p>{$inspiration->description|escape}</p>

                {if $is_maker_user eq true}
                  </form>
                {/if}
              </div>
          </li>
          {/foreach}
        {/if}
        </ul>


        {if isset($logged_in_user)}
          {if $is_maker_user eq true}

            <button class="btn btn-info pull-right" type="submit" id="add-inspiration" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#product-name').focus();$('#add-inspiration').toggle();$('#add-inspiration-cancel').toggle();" ><i class="fa fa-plus"></i> Add an{if $inspirations}other{/if} inspiration</button>

            <button class="btn btn-link pull-right" type="submit" id="add-inspiration-cancel" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#add-inspiration-cancel').toggle();$('#add-inspiration').toggle();"> Cancel </button>
            {assign var="inspired_maker_uid" value=$maker->uid}

          {elseif isset($logged_in_user->maker)}

            <button class="btn btn-info pull-right" type="submit" id="add-inspiration" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#add-inspiration').toggle();$('#add-inspiration-cancel').toggle();" ><i class="fa fa-plus"></i> Does {$maker->name|escape} inspire you?</button>

            <button class="btn btn-link pull-right" type="submit" id="add-inspiration-cancel" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#add-inspiration-cancel').toggle();$('#add-inspiration').toggle();"> Cancel </button>

            {assign var="inspired_maker_uid" value=$logged_in_user->maker->uid}
          {else}
            <p>Is {$maker->name|escape} an inspiration to you? To say so, <a href="/add/maker/?q={'@'|urlencode}{$logged_in_user->twitter_username|urlencode}">add yourself as a maker</a>.</p>
          {/if}

        {else}
            <p>Does {$maker->name|escape} inspire you? <a href="{$sign_in_with_twttr_link}">Sign in</a> to say so.</p>
        {/if}

        {if isset($logged_in_user->maker)}

            <div id="role-description-ghost">
              <form method="post" action="/add/inspiration/" class="form-horizontal col-xs-12  add-form collapse" id="add-inspiration-form">

                {if $is_maker_user eq true}
                  <h3>Who {if isset($inspirations) && count($inspirations) > 0}else {/if}inspires you?</h3>
                {else}
                  <h3>How did {$maker->name|escape} inspire you?</h3>
                {/if}

                <input type="hidden" name="originate_slug" value="{$maker->slug}">
                <input type="hidden" name="originate_uid" value="{$maker->uid}">
                <input type="hidden" name="inspired_maker_uid" value="{$inspired_maker_uid}">

                {if $is_maker_user eq true}
                <div class="form-group col-xs-12">
                  <label for="product_name" class="col-sm-1 control-label hidden-xs">Name</label>
                  <div class="col-xs-12 col-sm-10" id="remote-search-makers">
                    <input type="text" class="typeahead form-control input-sm" placeholder="Maker's name" name="maker_name" id="maker-name" autocomplete="off">
                    <small>{$inspiring_maker_guidance}</small>
                  <input type="hidden" name="maker_uid" id="maker-uid">
                  </div>
                </div>
                {else}

                <input type="hidden" name="maker_name" id="maker-name" autocomplete="off" value="{$maker->name}">
                <input type="hidden" name="maker_uid" value="{$maker->uid}">

                {/if}

                <div id="inspiration-extended-form"{if $is_maker_user eq true} class="collapse"{/if}>
                  <div class="form-group col-xs-12">
                    <label for="description" class="col-sm-1 control-label hidden-xs">How</label>
                    <div class="col-xs-12 col-sm-10">
                      <input type="text" class="form-control input-sm" autocomplete="off" id="inspiration-description" name="inspiration_description" placeholder="{$inspiration_placeholder}">
                      <small>{$inspiration_guidance}</small>
                    </div>
                  </div>

                  <div class="form-group col-xs-12">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                      <button class="btn btn-primary" type="submit" id="add-action">Save inspiration</button>
                    </div>
                  </div>
              </form>
            </div>
            {/if}
      </div>
    </div>

{/if}
{* /INSPIRATIONS *}

  </div>
</div>

{include file="_footer.tpl"}