{include file="_head.tpl"}

{if isset($query)}
<h1>{$query} returned {$return_document.hits.total} result{if $return_document.hits.total neq 1}s{/if}</h1>
{/if}

{if !isset($return_document) || $return_document.hits.total eq 0}
<p><form><input type='text' name='q'> <input type='Submit' value='Search again'></form></p>
{/if}

{if isset($return_document) }
    {foreach $return_document.hits.hits as $hit}
        <li><a href="/{if $hit._source.type eq 'maker'}m{else}p{/if}/{$hit._source.slug}">{$hit._source.name}</a></li>
    {/foreach}
{/if}


{include file="_footer.tpl"}
