{include file="_head.tpl" suppress_search='true'}

<div class="row">
   <div class="page-header col-sm-8 col-sm-offset-2 col-xs-12">
    <h1>
      Makerbase<br />
      <small>A directory of people who make things.</small>
    </h1>

    <form class="navbar-form" role="search" action="/search/" id="homepage-search">
      <div class="" id="remote-search">
        <div class="">
          <input type="search" class="form-control typeahead" placeholder="Search..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
      </div>
     </form>
  </div>
</div>

<div class="row">

{if isset($actions)}
  {if sizeof($actions) > 0}
  <div class=" col-sm-8 col-sm-offset-2 col-xs-12">
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