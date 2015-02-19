{include file="_head.tpl"}

<h1>Welcome</h1>

<form action="search.php"><input type='text' name='q'> <input type='Submit' value='Search'></form>

<h2>Makers</h2>
<ul>
{foreach $makers as $maker}
    <li><a href="/m/{$maker->slug}">{$maker->slug}</a></li>
{/foreach}
</ul>

<h2>Products</h2>
<ul>
{foreach $products as $product}
    <li><a href="/p/{$product->slug}">{$product->slug}</a></li>
{/foreach}
</ul>

{include file="_footer.tpl"}