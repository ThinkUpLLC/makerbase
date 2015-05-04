{include file="_head.tpl"}

<div class="row">
<div class="col-xs-12">
{if isset($waitlisters)}
    <h2>Waitlist</h2>
    <ul>
    {foreach $waitlisters as $waitlister}
    <li><a href="/add/maker/?q={$waitlister.network_username}">{$waitlister.network_username}</a> {$waitlister.creation_time|relative_datetime} ago</li>
    {/foreach}
    </ul>
{/if}
</div>
</div>

{include file="_footer.tpl"}