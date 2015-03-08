{include file="_head.tpl"}

{if isset($query)}
<h1>{$query} returned {$return_document.hits.total} result{if $return_document.hits.total neq 1}s{/if}</h1>
{/if}

{if !isset($return_document) || $return_document.hits.total eq 0}

<div class="row">
  <div class="col-xs-6">
          <form class="form" role="search" action="/search.php">
            <div class="input-group input-group-sm">
              <input type="search" class="form-control" placeholder="Search" value="{$query}" name="q">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default">Search again</button>
              </span>
            </div>
          </form>
  </div>
</div>

{/if}

{if isset($return_document) }
<div class="row">
  <div class="col-xs-12">
  	<div class="list-group">
	{foreach $return_document.hits.hits as $hit}
		<a class="list-group-item" href="/{if $hit._source.type eq 'maker'}m{else}p{/if}/{$hit._source.slug}">
  			<div class="media-left">
            <img class="media-object" src="{insert name='user_image' image_url=$hit._source.avatar_url image_proxy_sig=$image_proxy_sig type=$hit._source.type}" alt="{$hit._source.name}" width="100">
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
