{include file="_head.tpl"}

{include file="_isosceles.usermessage.tpl"}

<div class="row">

{if isset($actions)}
  <div class="col-xs-6">
  {if isset($logged_in_user)}
  <br><br>
	<form method="post" action="/add/maker/">
		<label>Add a maker:</label>
		<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">@</span>
		  <input type="text" class="form-control" placeholder="Twitter username" aria-describedby="basic-addon1" name="twitter_username">
			<span class="input-group-btn">
		        <button class="btn btn-default" type="submit">Go</button>
		    </span>
		</div>
	</form>
  <br><br>
    <form method="post" action="/add/product/">
        <label>Add a product:</label>
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">@</span>
          <input type="text" class="form-control" placeholder="Twitter handle" aria-describedby="basic-addon1" name="twitter_username">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Go</button>
            </span>
        </div>
    </form>
  <br><br>
  {/if}
  	{if sizeof($actions) > 0}
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
  	{/if}
  </div>
{/if}

</div>

{include file="_footer.tpl"}