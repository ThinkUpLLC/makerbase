{include file="_head.tpl"}

<div class="row" id="maker-info">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-left media-top">
        <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name}">
      </div>
      <div class="media-body">
        <div id="maker-info-profile">
            {if isset($logged_in_user)}
              <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right">edit</button>
            {else}
              <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">edit</a>
            {/if}
          <h1 {if $maker->is_archived}class="archived"{/if}>
            <strong>{$maker->name|escape}</strong> is a maker
          </h1>
          <h5><a href="{$maker->url}" class="text-muted">{$maker->url}</a></h5>
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
        <img class="img-responsive" src="{if isset($maker->avatar_url)}{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='p'}{else}{$site_root_path}assets/img/blank-maker.png{/if}" alt="{$maker->name}">

        <form method="post" action="/edit/maker/" id="maker-profile-archive" class="">
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
          <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right" id="maker-info-edit-cancel">cancel</button>
        </div>

        <form method="post" action="/edit/maker/" id="maker-profile-edit-form">
          <div class="form-group">
            <label for="name" class="col-sm-2 hidden-xs control-label">Name</label>
            <div class="col-xs-12 col-sm-10">
              <input type="text" class="form-control input-lg" autocomplete="off" name="name" id="name" value="{$maker->name}">
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

{if isset($maker->twitter_username)}
  {assign var="tweet_body" value="@{$maker->twitter_username} Is your Makerbase page up to date?"}
  <div class="row">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
      <h4 id="maker-nudge"><a href="https://twitter.com/share?text={$tweet_body|urlencode}" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" class="btn btn-xs btn-link btn-default"><i class="fa fa-twitter"></i> Ask @{$maker->twitter_username} to update this page</a></h4>
    </div>
  </div>
{/if}


<div class="row" id="roles">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <ul class="list-unstyled" id="role-list">
    {foreach $roles as $role}
      <li class="">
          {include file="_role.tpl"}
      </li>
    {/foreach}
    </ul>

  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div id="projects">

          {if isset($logged_in_user)}

            <button class="btn btn-info pull-right" type="submit" id="add-role" data-toggle="collapse" data-target="#add-project-form" onclick="$('#add-role').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</button>


            <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse" id="add-project-form">

            <h2>What {if $roles}else {/if}did {$maker->name|escape} make?</h2>
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
                    <input type="text" class="form-control" autocomplete="off" id="role" name="role" placeholder="{$placeholder}">
                    <small>{$role_guidance}</small>
                  </div>
                </div>

                {include file="_dates.tpl" start_m='' start_Y='' end_m='' end_Y='' dates_id='project'}

                <div class="form-group col-xs-12">
                  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <a class="btn btn-link btn-sm pull-right" data-toggle="collapse" data-target="#add-project-form" onclick="$('#add-role').toggle();" >cancel</a>
                    <button class="btn btn-primary" type="submit">Add project</button>
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

{if sizeof($collaborators) > 0}
<div class="row" id="collaborators">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <h2><a href="#collaborators">{$maker->name|escape}'s <strong>Collaborators</strong></a></h2>
    <ul class="list-group" id="collaborator-list">
      {foreach $collaborators as $collaborator}
      <li class="list-group-item">
        <div class="media-left media-top">
          <a href="/m/{$collaborator.uid}/{$collaborator.slug}"><img class="media-object" src="{insert name='user_image' image_url=$collaborator.avatar_url image_proxy_sig=$image_proxy_sig type='m'}" width="50" height="50"></a>
        </div>

        <div class="media-body">
          <h3>{$collaborator.total_collaborations} projects with <strong><a href="/m/{$collaborator.uid}/{$collaborator.slug}">{$collaborator.name|escape}</a></strong></h3>

          {foreach $collaborator.projects as $project name="collaborated_projects"}
            <a href="/p/{$project->uid}/{$project->slug}">

            <img src="{insert name='user_image' image_url=$project->avatar_url image_proxy_sig=$image_proxy_sig type='p'}">

            {$project->name|escape}</a>
          {/foreach}
        </div>
      </li>
      {/foreach}
    </ul>
  </div>
</div>
{/if}


  {include file="_actions.tpl" object=$maker object_type='maker'}

{include file="_footer.tpl"}