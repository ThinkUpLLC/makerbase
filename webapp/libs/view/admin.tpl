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

<h3 class="pull-right"><small><i class="fa fa-inbox"></i> Emails:</small> {$total_emails|number_format} <small><i class="fa fa-connectdevelop"></i> Friend lists:</small> {(($total_users_with_friends/$total_users) * 100)|round}%</h3>

    <ul class="nav nav-tabs">
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'all-actions'}class="active"{/if}{/if}><a href="/s3cr3t/all-actions">All Actions</a></li>
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'actions'}class="active"{/if}{/if}><a href="/s3cr3t/actions">Admin Actions</a></li>
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'top-users'}class="active"{/if}{/if}><a href="/s3cr3t/top-users">Active Users</a></li>
      <li role="presentation"{if isset($sort_view)}{if $sort_view eq 'stats'}class="active"{/if}{/if}><a href="/s3cr3t/stats">Stats</a></li>
    </ul>

{if isset($actions) && sizeof($actions) > 0}

<div class="row" id="activity">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
      {foreach $actions as $action}

      {assign var='color_num' value=($action->id|substr:-1)}
      {if $color_num eq '1'}{assign var='color' value='bubblegum'}
        {elseif $color_num eq '2'}{assign var='color' value='caramel'}
        {elseif $color_num eq '3'}{assign var='color' value='creamsicle'}
        {elseif $color_num eq '4'}{assign var='color' value='dijon'}
        {elseif $color_num eq '5'}{assign var='color' value='mint'}
        {elseif $color_num eq '6'}{assign var='color' value='pea'}
        {elseif $color_num eq '7'}{assign var='color' value='purple'}
        {elseif $color_num eq '8'}{assign var='color' value='salmon'}
        {elseif $color_num eq '9'}{assign var='color' value='sandalwood'}
        {elseif $color_num eq '0'}{assign var='color' value='sepia'}
        {else}{assign var='color' value='gray-lighter'}
      {/if}

      {if isset($maker) || isset($product)}{assign var='color' value='transparent'}{/if}

      <div class="media {if isset($color)}style-{$color}{/if}">
        {include file="_action.tpl"}
      </div>
    {/foreach}
</div>
</div>
{/if}

{if isset($top_users)}

      {if sizeof($top_users) > 0}
      <ul class="list-group">
      {foreach $top_users as $user}
          <li class="list-group-item col-xs-12">
          <a href="/u/{$user->uid}">{$user->name}</a> made {$user->total_actions} changes in last 7 days
          </li>
      {/foreach}
      </ul>
      {/if}

{/if}

{if isset($weekly_signups)}
<h2>Signups per week</h2>
  {if isset($last_six_weeks_average)}{if $last_six_weeks_average > 0}<p style="color:green">+{else}<p style="color:red">{/if}{$last_six_weeks_average}% week-over-week last six weeks</p>{/if}

<ul class="list-group">
  {foreach $weekly_signups as $weekly_signup}
    <li class="list-group-item col-xs-12">{if $weekly_signup@last}This week so far{else}{if $weekly_signup@first}Launch week{else}Week {$weekly_signup.week_number}{/if}{/if}: {$weekly_signup.user_signups_per_week|number_format} signups

    {if isset($weekly_signup.percentage_diff)}{if $weekly_signup.percentage_diff > 0}<span style="color:green">+{else}<span style="color:red">{/if}{$weekly_signup.percentage_diff|number_format}%</span>{/if}</li>
  {/foreach}
</ul>
{/if}


</div>
</div>

{include file="_footer.tpl"}