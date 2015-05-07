{include file="_head.tpl" suppress_search='true'}

<div class="row">
   <div class="page-header col-sm-8 col-sm-offset-2 col-xs-12">

    <form class="navbar-form" role="search" action="/search/" id="homepage-search">
      <div class="" id="remote-search">
        <div class="">
          <input type="search" class="form-control typeahead" placeholder="Search or add..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
      </div>
     </form>
  </div>
</div>

<div class="row">

{if isset($actions)}
  {if sizeof($actions) > 0}


  <div id="history" class="col-xs-12">

    <h3>Recent activity</h3>

    {if sizeof($actions) > 0}
    <ul class="list-group">
    {foreach $actions as $action}
        <li class="list-group-item col-xs-12">
        {include file="_action.tpl"}
        </li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <nav id="homepage-pager" class="col-xs-12">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/activity/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/{if $prev_page neq 1}activity/{$prev_page}{/if}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>

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
