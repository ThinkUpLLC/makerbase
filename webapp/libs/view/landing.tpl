{include file="_head.tpl" suppress_search='true'}

<div class="row">
   <div class="page-header col-sm-10 col-sm-offset-1 col-xs-12">

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

   <div class="page-header col-sm-10 col-sm-offset-1 col-xs-12">
      <div class="jumbotron">
        <h1><strong>Makerbase</strong> is:</h1>
        <ul class="list">
          <li>A place to tell the world what you made, and to find out who made the things you love.</li>
          <li>Edited by <strong>everyone</strong>. You can change and update anything that you see.</li>
          <li>A way to see <strong>projects</strong>: Stuff like apps and websites and art projects. (Not a list of companies or employers.)</li>
          <li>A list of <strong>makers</strong>: People who contributed to or created projects, described by what they've <em>done</em>, not just job titles.</li>
          <li><strong>Limited access</strong>, for now. Let your friends in by adding them as makers.</li>
        </ul>
      </div>
   </div>

</div>

<div class="row">

{if isset($actions)}
  {if sizeof($actions) > 0}


  <div id="history" class="col-sm-10 col-sm-offset-1 col-xs-12 history-vivid">

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

  <nav id="pager" class="col-sm-10 col-sm-offset-1 col-xs-12">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/activity/{$next_page}#history"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/{if $prev_page neq 1}activity/{$prev_page}{/if}#history">Newer <span aria-hidden="true">&rarr;</a></li>
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
