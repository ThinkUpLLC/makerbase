{include file="_head.tpl"}

<div class="row">
    <div class="col-sm-12 col-xs-12">
    <h1{if $maker->is_archived} class="archived"{/if}>{$maker->name} is a maker {if !isset($logged_in_user)}<button type="button" class="btn btn-sm btn-success pull-right"><i class="fa fa-user"></i> Hey, I'm {$maker->name}!</button>{/if}
    </h1>
  </div>
</div>

<div class="row">
  <div class="col-sm-4 col-xs-2">
    <img class="img-responsive" src="{insert name='user_image' image_url=$maker->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$maker->name}" width="100%">
  <div>
</div>


    <div class="history hidden-xs">
      {if isset($maker->twitter_username)}
        {assign var="tweet_body" value="@{$maker->twitter_username} Is your Makerbase page up to date?"}
      <a  class="btn btn-xs btn-info" href="https://twitter.com/share?text={$tweet_body|urlencode}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Ask @{$maker->twitter_username} to update this page</a><br><br>
      {/if}
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
  <nav>
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/m/{$maker->uid}/{$maker->slug}/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/m/{$maker->uid}/{$maker->slug}{if $prev_page neq 1}/{$prev_page}{/if}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>
  </div>

  <div class="col-sm-8 col-xs-10">


    <a {if isset($logged_in_user)}href="#edit-maker" data-toggle="collapse"{else}href="{$sign_in_with_twttr_link}"{/if} type="button" class="btn btn-default btn-xs btn-link pull-right" aria-label="Center Align">
    <span class="fa fa-pencil" aria-hidden="true"></span> Edit
    </a>
    <p><a href="{$maker->url}">{$maker->url}</a></p>

    {if isset($logged_in_user)}
    <!-- edit form -->
    <div class="form-horizontal collapse" id="edit-maker">


      <form method="post" action="/edit/maker/" class="edit-maker-form">
        <div class="form-group">
          <input type="hidden" name="uid" value="{$maker->uid}" />
          <input type="hidden" name="archive" value="{if $maker->is_archived}0{else}1{/if}"/>
          <div class="col-xs-12">
            <button type="submit" class="btn btn-danger btn-xs pull-right">
                <span class="fa fa-remove"></span> {if $maker->is_archived}Unarchive{else}Archive{/if}
            </button>
          </div>

        </div>
      </form>

      <form method="post" action="/edit/maker/">
          <div class="form-group">
            <label for="full_name" class="col-xs-3 control-label">Name</label>
            <div class="col-xs-9">
              <input type="text" class="form-control" autocomplete="off" name="name" value="{$maker->name}">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-xs-3 control-label">Web site url</label>
            <div class="col-xs-9">
              <input type="text" class="form-control col-xs-6" autocomplete="off" name="url" value="{$maker->url}">
            </div>
          </div>
          <div class="form-group">
            <label for="url" class="col-xs-3 control-label">Avatar url</label>
            <div class="col-xs-9">
              <input type="text" class="form-control col-xs-6" autocomplete="off" name="avatar_url" value="{$maker->avatar_url}">
            </div>
          </div>
          <input type="hidden" name="maker_uid" value="{$maker->uid}">
          <div class="form-group">
              <label for="update-maker" class="col-xs-3 control-label"></label>
              <div class="col-xs-9">
                  <button class="btn btn-primary" type="submit" id="update-maker">Update maker</button>
              </div>
          </div>
      </form>

    </div>
    {/if}

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
        <input type="hidden" name="product_uid" id="product-uid">
        <div class="form-group">
          <label for="maker_slug" class="col-sm-3 control-label">Project:</label>
          <div class="col-sm-9">
            <div class="input-group" id="remote-search-products">
              <input type="text" class="typeahead form-control" placeholder="" name="product_name" id="product-name">
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="role" class="col-sm-3 control-label">Role:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" autocomplete="off" id="role" name="role" placeholder="{$placeholder}">
          </div>
        </div>

        <div class="form-group">
          <label for="role" class="col-sm-3 control-label"></label>
          <div class="col-sm-9">
            <a href="#show-role-dates" data-toggle="collapse" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i> <i class="caret"></i></a>
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

        <button class="btn btn-primary col-sm-offset-3" type="submit">Add a project</button>
      </form>
  {else}
    <a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm col-sm-4"><i class="fa fa-plus"></i> Add a{if $roles}nother{/if} project</a>
  {/if}

   {if sizeof($collaborators) > 0}
    <div id="collaborators">
        <h4>Collaborators</h4>

        <ul class="list-group list-unstyled col-xs-12" id="collaborator-list">
        {foreach $collaborators as $collaborator}
        <li class="list-group-item col-xs-12">
            <div class="col-xs-2 col-sm-2">
              <a href="/m/{$collaborator.uid}/{$collaborator.slug}"><img class="img-responsive" src="{insert name='user_image' image_url=$collaborator.avatar_url image_proxy_sig=$image_proxy_sig type='m'}"></a>
            </div>

            <div class="col-xs-10 col-sm-10">
            {$maker->name} made {$collaborator.total_collaborations} projects with <a href="/m/{$collaborator.uid}/{$collaborator.slug}">{$collaborator.name}</a>:<br />
              {foreach $collaborator.projects as $project name="collaborated_projects"}{if !$smarty.foreach.collaborated_projects.first}{if sizeof($collaborator.projects) > 2}, {/if}{/if}{if $smarty.foreach.collaborated_projects.last} and {/if}<a href="/p/{$project->uid}/{$project->slug}">{$project->name}</a>{/foreach}
            </div>
        </li>
        {/foreach}
      </ul>
    </div>
  {/if}

  </div>
</div>

{include file="_footer.tpl"}