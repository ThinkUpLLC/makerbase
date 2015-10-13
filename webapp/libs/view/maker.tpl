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
              <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right">edit</button>
            {else}
              <a href="{$sign_in_with_twttr_link}" class="btn btn-default btn-link pull-right">edit</a>
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
          <button onclick="$('#maker-info-edit').toggle();$('#maker-info').toggle();" class="btn btn-default btn-link pull-right" id="maker-info-edit-cancel">cancel</button>
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
      <li role="presentation" class="btn-group active">
        <a href="#projects-tab" class="btn btn-default" aria-controls="projects-tab" role="tab" data-toggle="tab">projects</a>
      </li>
      {if sizeof($collaborators) > 0}
      <li role="presentation" class="btn-group ">
        <a href="#collaborators" class="btn btn-default" aria-controls="collaborators" role="tab" data-toggle="tab">makerbaes</a>
      </li>
      {/if}
      <li role="presentation" class="btn-group ">
        <a href="#inspirations" class="btn btn-default" aria-controls="inspirations" role="tab" data-toggle="tab">inspiration</a>
      </li>
    </ul>


  </div>
</div>


<div class="tab-content">
  <div role="tabpanel" class="tab-pane fade in active" id="projects-tab">

    <div class="row" id="roles">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

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


    <div class="row" id="projects">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">
        <div>

              {if isset($logged_in_user)}

                <button class="btn btn-info pull-right" type="submit" id="add-role" data-toggle="collapse" data-target="#add-role-form" onclick="$('#product-name').focus();$('#add-role').toggle();$('#add-role-cancel').toggle();" ><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</button>

                <button class="btn btn-link pull-right" type="submit" id="add-role-cancel" data-toggle="collapse" data-target="#add-role-form" onclick="$('#add-role-cancel').toggle();$('#add-role').toggle();" > Cancel </button>

                <form method="post" action="/add/role/" class="form-horizontal col-xs-12 collapse add-form" id="add-role-form">

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

    {include file="_actions.tpl" object=$maker object_type='maker'}

  </div>

{if sizeof($collaborators) > 0}
  <div role="tabpanel" class="tab-pane fade" id="collaborators">

    <div class="row" id="">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

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

  </div>
{/if}

  <div role="tabpanel" class="tab-pane fade" id="inspirations">

    {* INSPIRATIONS *}

    <div class="row" id="">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

        {if sizeof($inspirations) > 0}
          <h3><a href="#inspirations">{$maker->name|escape}'s <strong>Inspirations</strong></a></h3>
        {/if}
        <ul class="list-group" id="inspiration-list">

        {if sizeof($inspirations) > 0}
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
              {/if}

                {if $is_maker_user eq true}
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

          {if $is_maker_user eq true}
          <li class="list-group-item style-transparent">
            <div class="" id="role-ghost">
                <div class="media-left media-top">
                  <img class="media-object" src="/assets/img/ghost-object.png" alt="logo" width="50" height="50">
                </div>
                <div class="media-body">
                  <h3>Who {if $inspirations}else {/if}inspires you?</h3>


              {if isset($logged_in_user)}
                <button class="btn btn-info pull-right" type="submit" id="add-inspiration" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#product-name').focus();$('#add-inspiration').toggle();$('#add-inspiration-cancel').toggle();" ><i class="fa fa-plus"></i> Add a{if $inspirations}nother{/if} inspiration</button>

                <button class="btn btn-link pull-right" type="submit" id="add-inspiration-cancel" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#add-inspiration-cancel').toggle();$('#add-inspiration').toggle();" > Cancel </button>

              {else}

                <a href="{$sign_in_with_twttr_link}" class="btn btn-info pull-right" id="add-inspiration"><i class="fa fa-plus"></i> Add a{if $inspirations}nother{/if} inspiration</a>

              {/if}

                  <div id="role-description-ghost">
                    <form method="post" action="/add/inspiration/" class="form-horizontal add-form collapse" id="add-inspiration-form">
                      <input type="hidden" name="originate_slug" value="{$maker->slug}">
                      <input type="hidden" name="originate_uid" value="{$maker->uid}">
                      <input type="hidden" name="maker_uid" id="maker-uid">

                      <div class="form-group col-xs-12">
                        <label for="product_name" class="col-sm-1 control-label hidden-xs">Name</label>
                        <div class="col-xs-12 col-sm-10" id="remote-search-makers">
                          <input type="text" class="typeahead form-control input-sm" placeholder="Maker's name" name="maker_name" id="maker-name" autocomplete="off">
                        </div>
                      </div>

                      <div id="inspiration-extended-form">
                        <div class="form-group col-xs-12">
                          <label for="description" class="col-sm-1 control-label hidden-xs">How</label>
                          <div class="col-xs-12 col-sm-10">
                            <input type="text" class="form-control input-sm" autocomplete="off" id="inspiration-description" name="inspiration_description" placeholder="{$inspiration_placeholder}">
                          </div>
                        </div>

                        <div class="form-group col-xs-12">
                          <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                            <button class="btn btn-primary" type="submit" id="add-action">Inspiring!</button>
                          </div>
                        </div>
                    </form>
                  </div>
                </div>
            </div>
          </li>
          {/if}

        </ul>

      </div>
    </div>

    <div class="row" id="inspirations">
      <div class="col-xs-12 col-sm-10 col-sm-offset-1">

          {if sizeof($inspired_makers) > 0}
            <h3><a href="#inspirations">{$maker->name|escape} <strong> inspires:</strong></a></h3>
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

              <div class="col-xs-3 col-sm-2 col-md-1 inspiree">
                <div class="">
                  <a tabindex="0" role="button"
                   data-toggle="popover" data-placement="bottom" data-trigger="focus" data-html="true" data-content="{$smarty.capture.hideform} {$inspired_maker->name} description of inspiration" data-title="<a href='/m/{$inspired_maker->uid}/{$inspired_maker->slug}'>{$inspired_maker->name}</a>">
                    <img  src="{insert name='user_image' image_url=$inspired_maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" width="50">
                  </a>
                </div>

              </div>
            {/foreach}
          {/if}

            {if $is_maker_user neq true}

            <div class="col-xs-9 col-sm-4 inspiree inspiree-add">
              <div class="">
                <a tabindex="0" role="button" id="add-inspiration" data-toggle="collapse" data-target="#add-inspiration-form" onclick="$('#add-inspiration-form').toggle();">
                  <h3><i class="fa fa-plus"></i> Does {$maker->name|escape} inspire you?</h3>
                </a>
              </div>
            </div>


            <div class="col-xs-12">
              <div class="">
                  <form method="post" action="/add/inspiration/" class="form-horizontal add-form collapse" id="add-inspiration-form">
                    <input type="hidden" name="originate_slug" value="{$maker->slug}">
                    <input type="hidden" name="originate_uid" value="{$maker->uid}">
                    <input type="hidden" name="maker_uid" id="maker-uid" value="{$maker->uid}">
                    <input type="hidden" class="typeahead form-control input-sm" placeholder="Maker's name" name="maker_name" id="maker-name" autocomplete="off" value="{$maker->name}">

                    <div id="inspiration-extended-form">
                      <div class="form-group col-xs-12">
                        <label for="description" class="col-sm-1 control-label hidden-xs">How</label>
                        <div class="col-xs-12 col-sm-10">
                          <input type="text" class="form-control input-sm" autocomplete="off" id="inspiration-description" name="inspiration_description" placeholder="{$inspiration_placeholder}">
                        </div>
                      </div>

                      <div class="form-group col-xs-12">
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                          <button class="btn btn-primary" type="submit" id="add-action">Inspiring!</button>
                        </div>
                      </div>
                  </form>
              </div>
            </div>

            {/if}

      </div>
    </div>


    {* /INSPIRATIONS *}

  </div>
</div>

{include file="_footer.tpl"}