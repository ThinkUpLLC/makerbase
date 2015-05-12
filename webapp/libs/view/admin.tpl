{include file="_head.tpl"}

<script src="//platform.twitter.com/widgets.js"></script>
<div class="row">
<div class="col-xs-12">
{if isset($waitlisters)}
    <h2>Waitlist ({$total_waitlisters|number_format})</h2>
    <ul>
    {foreach $waitlisters as $waitlister}
    <li><a href="/add/maker/?q={$waitlister.network_username}">Add</a> - <a href="https://twitter.com/intent/user?user_id={$waitlister.network_id}">@{$waitlister.network_username}</a> - {$waitlister.follower_count|number_format} - <a href="/requestinvites/{$waitlister.network_id}/">check followers</a> - {$waitlister.creation_time|relative_datetime} ago</li>
    {/foreach}
    </ul>
    <p>order by <a href="/s3cr3t/follower_count">follower_count</a> or <a href="/s3cr3t/creation_time">creation_time</a></p>
{/if}
</div>
</div>

{include file="_footer.tpl"}