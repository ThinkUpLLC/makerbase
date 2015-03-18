{include file="_head.tpl" suppress_search='true'}

<div class="row">
   <div class="page-header">
    <h1>
      Makerbase<br />
      <small>A directory of people who make things.</small>
    </h1>

    <form class="navbar-form col-xs-offset-2" role="search" action="/search/">
      <div class="" id="remote-search">
        <div class="input-group col-xs-6">
          <input type="search" class="form-control typeahead" placeholder="Search for..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
      </div>
     </form>
  </div>

{if isset($actions)}
  {if sizeof($actions) > 0}
  <div class="col-sm-7 col-xs-12">
  	<h3>Recent activity</h3>
	<ul class="list-group">
	{foreach $actions as $action}
	    <li class="list-group-item">
	    {include file="_action.tpl"}
	    </li>
	{/foreach}
	</ul>
	{else}
		{if isset($sign_in_with_twttr_link)}
		<h3>Recent activity</h3>
		<p>No activity. <a href="{$sign_in_with_twttr_link}">Sign in</a> to get started.</p>
		{/if}
  </div>
  {/if}
{/if}

</div>
</div>

{include file="_footer.tpl"}