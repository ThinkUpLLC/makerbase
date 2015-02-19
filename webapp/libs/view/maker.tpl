{include file="_head.tpl"}

<h1>Maker</h1>

<h2>{$maker->slug}</h2>

<p><a href="{$maker->url}">{$maker->url}</a></p>

<img src="{$maker->avatar_url}" />


<ul>
{foreach $roles as $role}
    <li>{$role->role}, {$role->product->name}  {$role->start} - {$role->end}</li>
{/foreach}
</ul>

{include file="_footer.tpl"}