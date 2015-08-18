{include file="_head.tpl"}

{if isset($query)}
  {if $return_document.hits.total eq 0 && isset($search_type)}
    <h1>{$query|escape} isn't a {if $search_type eq 'product'}project{else}{$search_type}{/if} yet.</h1>
  {else}
    {if isset($search_type)}
      <h1>{$query|escape} returned {$return_document.hits.total} {if $search_type eq 'product'}project{else}{$search_type}{/if}{if $return_document.hits.total neq 1}s{/if}</h1>
    {else}
      <h1>Your search for {$query|escape} returned {$return_document.hits.total} result{if $return_document.hits.total neq 1}s{/if}.</h1>
    {/if}
  {/if}
{/if}

{if !isset($return_document) || $return_document.hits.total eq 0}

<div class="row">
  <div class="col-xs-6">
    {if isset($search_type)}
        <h3><a href="/add/{$search_type}/?q={$query|urlencode}" class="btn btn-xl btn-primary">Add this {if $search_type eq 'product'}project{else}{$search_type}{/if}</a></h3>
    {else}
    <p>Please try again.</p>
    {/if}
  </div>
</div>

{/if}

{if isset($return_document) }
<div class="row">
  <div class="col-xs-12">
  	<div class="list-group">
	{foreach $results as $hit}
		<a class="list-group-item" href="/{$hit.type}/{$hit.uid}/{$hit.slug}">
  			<div class="media-left">
            <img class="media-object" src="{$hit.avatar_url}" alt="{$hit.name}" width="100">
			</div>
			<div class="media-body">
				<h3>{$hit.name}</h3>
				{if isset($hit.description) && $hit.description neq ''}{$hit.description}{/if}
			</div>
		</a>
	{/foreach}
	</div>
  </div>
</div>
{/if}


{include file="_footer.tpl"}
