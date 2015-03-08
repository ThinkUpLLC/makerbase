{include file="_head.tpl"}

<div class="row">

{if isset($actions)}
  {if isset($logged_in_user)}
  <div class="col-xs-5">
  	<h3>Make Makerbase</h3>
	<form method="post" action="/add/maker/" class="form-horizontal">
		<div class="form-group">
			<label class="col-xs-2">Maker:</label>
			<div class="input-group input-group-sm">
			  <span class="input-group-addon" id="basic-addon1">@</span>
			  <input type="text" class="form-control" placeholder="Twitter username" aria-describedby="basic-addon1" name="twitter_username">
				<span class="input-group-btn">
			        <button class="btn btn-success" type="submit">Go</button>
			    </span>
			</div>
		</div>
	</form>
    <form method="post" action="/add/product/" class="form-horizontal">
    	<div class="form-group">
        <label class="col-xs-2">Product:</label>
        <div class="input-group input-group-sm">
          <span class="input-group-addon" id="basic-addon1">@</span>
          <input type="text" class="form-control" placeholder="Twitter handle" aria-describedby="basic-addon1" name="twitter_username">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Go</button>
            </span>
        </div>
      </div>
    </form>
  </div>
  {/if}
  {if sizeof($actions) > 0}
  <div class="col-xs-7">
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