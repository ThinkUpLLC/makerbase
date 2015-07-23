{include file="_head.tpl"}

<script src="//platform.twitter.com/widgets.js"></script>
<div class="row">
<div class="col-xs-12">

<div class="page-header">
  <ul class="nav nav-pills nav-justified">
    <li><h2><small><i class="fa fa-child"></i> Users:</small> {$total_users|number_format}</h2></li>
    <li><h2><small><i class="fa fa-users"></i> Makers:</small> {$total_makers|number_format}</h2></li>
    <li><h2><small><i class="fa fa-cubes"></i> Projects:</small> {$total_products|number_format}</h2></li>
    <li><h2><small><i class="fa fa-link"></i> Roles:</small> {$total_roles|number_format}</h2></li>
    <li><h2><small><i class="fa fa-edit"></i> Actions:</small> {$total_actions|number_format}</h2></li>
  </ul>
</div>

<h3 class="pull-right"><small><i class="fa fa-inbox"></i> Email addresses:</small> {$total_emails|number_format}</h3>
<h3><small><i class="fa fa-clock-o"></i> Waitlist:</small> {$total_waitlisters|number_format}</h3>

    <ul class="nav nav-tabs">
      <li role="presentation" {if isset($sort_view)}{if $sort_view eq 'waitlist_followers'}class="active"{/if}{/if}><a href="/s3cr3t/waitlist_followers">Waitlist Most Followers</a></li>
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'waitlist_newest'}class="active"{/if}{/if}><a href="/s3cr3t/waitlist_newest">Waitlist Newest</a></li>
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'actions'}class="active"{/if}{/if}><a href="/s3cr3t/actions">Admin Actions</a></li>
    </ul>

{if isset($waitlisters)}
    <ul class=" list-group media-list">
    {foreach $waitlisters as $waitlister}
      <li class=" list-group-item">
        <div class="media-left">
          <a href="/add/maker/?q=%40{$waitlister.network_username}" class="btn btn-sm btn-default {if $waitlister.follower_count gt 1000} bg-success{/if}" target="_blank"><i class="fa fa-plus"></i> Add</a>
        </div>

        <div class="media-body">

          <h5 class="pull-right">
            <a href="/requestinvites/{$waitlister.network_id}/" class=""><i class="fa fa-file-code-o"></i>&nbsp;</a>
            {$waitlister.creation_time|relative_datetime} ago
          </h5>

          <h3 class="pull-left">
            <a href="https://twitter.com/intent/user?user_id={$waitlister.network_id}">@{$waitlister.network_username}</a>
            <small>{$waitlister.follower_count|number_format}</small>
          </h3>

        </div>

      </li>
    {/foreach}
    </ul>

  <nav id="pager">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/s3cr3t/{$sort_view}/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/s3cr3t/{$sort_view}/{$prev_page}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>

{/if}

{if isset($actions)}

      {if sizeof($actions) > 0}
      <ul class="list-group">
      {foreach $actions as $action}
          <li class="list-group-item col-xs-12">
          {include file="_action.tpl"}
          </li>
      {/foreach}
      </ul>
      {/if}

{/if}
</div>
</div>

{include file="_footer.tpl"}