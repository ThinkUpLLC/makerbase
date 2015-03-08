{include file="_head.tpl"}

<div class="row">
  <div class="col-xs-5">
    <h3>Make Makerbase</h3>

  {if isset($logged_in_user)}
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
  {else}
    <p><a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a maker</a></p>
    <p><a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a project</a></p>
  {/if}
  </div>
{if isset($actions)}
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