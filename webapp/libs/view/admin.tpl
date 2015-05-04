{include file="_head.tpl"}

<div class="row">
<div class="col-xs-12">
{if isset($waitlisters)}
    <h2>Waitlist</h2>
    <ul>
    {foreach $waitlisters as $waitlister}
    <li><a href="/add/maker/?q={$waitlister.twitter_username}">{$waitlister.twitter_username}</a> {$waitlister.creation_time}</li>
    {/foreach}
    </ul>
{/if}
</div>
</div>

{include file="_footer.tpl"}