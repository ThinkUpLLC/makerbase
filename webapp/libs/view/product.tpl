{include file="_head.tpl"}

<h1>Product</h1>

<h2>{$product->slug}</h2>

<p><a href="{$product->url}">{$product->url}</a></p>

<img src="{$product->avatar_url}" />

{include file="_footer.tpl"}