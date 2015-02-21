{include file="_head.tpl"}

<p>Hello. Search for something. <form><input type='text' name='q'> <input type='Submit' value='Search'></form></p>


<h1>{$query} returned {$return_document.hits.total} result{if $return_document.hits.total neq 1}s{/if}</h1>

{foreach $return_document.hits.hits as $hit}
    <li><a href="/{if $hit._source.type eq 'maker'}m{else}p{/if}/{$hit._source.slug}">{$hit._source.name}</a></li>
{/foreach}


{include file="_footer.tpl"}