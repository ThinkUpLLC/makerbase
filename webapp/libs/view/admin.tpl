{include file="_head.tpl"}

<script src="//platform.twitter.com/widgets.js"></script>
<div class="row">
<div class="col-xs-12">
<h2>{$total_users|number_format} users - {$total_products|number_format} projects - {$total_roles|number_format} roles - {$total_actions|number_format} actions</h2>

{if isset($waitlisters)}
    <h3>Waitlist ({$total_waitlisters|number_format})</h3>
    <ul>
    {foreach $waitlisters as $waitlister}
    <li><a href="/add/maker/?q={$waitlister.network_username}">Add</a> - <a href="https://twitter.com/intent/user?user_id={$waitlister.network_id}">@{$waitlister.network_username}</a> - {$waitlister.follower_count|number_format} - <a href="/requestinvites/{$waitlister.network_id}/">check followers</a> - {$waitlister.creation_time|relative_datetime} ago</li>
    {/foreach}
    </ul>
    <p>order by <a href="/s3cr3t/follower_count">follower_count</a> or <a href="/s3cr3t/creation_time">creation_time</a></p>

  <nav id="pager">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/s3cr3t/{$smarty.get.sort}/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/s3cr3t/{$smarty.get.sort}/{$prev_page}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>

{/if}
</div>
</div>

{include file="_footer.tpl"}