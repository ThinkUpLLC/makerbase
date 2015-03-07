{include file="_head.tpl"}

{if isset($query)}
<h1>{$query} returned {$return_document.hits.total} result{if $return_document.hits.total neq 1}s{/if}</h1>
{/if}

{if !isset($return_document) || $return_document.hits.total eq 0}
<p><form><input type='text' name='q'> <input type='Submit' value='Search again'></form></p>
{/if}

{if isset($return_document) }
<div class="row">
  <div class="col-xs-12">
  	<div class="list-group">
	{foreach $return_document.hits.hits as $hit}
		<a class="list-group-item" href="/{if $hit._source.type eq 'maker'}m{else}p{/if}/{$hit._source.slug}">
  			<div class="media-left">
			</div>
			<div class="media-body">
				<h3>{$hit._source.name}</h3>
				{if $hit._source.description neq ''}{$hit._source.description}{/if}
			</div>
		</a>
	{/foreach}
	</div>
  </div>
</div>
{/if}


{include file="_footer.tpl"}
