{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-5 col-xs-12">

  <div class="page-header">
    <h1>
      Makerbase<br />
      <small>A directory of people who make things.</small>
    </h1>
  </div>

  {if isset($logged_in_user)}
	<form method="post" action="/add/maker/" class="form-horizontal">
		<div class="form-group">
			<label class="col-xs-3">Add a Maker:</label>
			<div class="input-group input-group-sm col-xs-8">
			  <span class="input-group-addon" id="basic-addon1">@</span>
			  <input type="text" class="form-control" placeholder="Twitter username" aria-describedby="basic-addon1" name="twitter_username" autocomplete="off">
				<span class="input-group-btn">
          <button class="btn btn-success" type="submit">Go</button>
        </span>
			</div>
      <span class="help-block col-xs-offset-3">A human being who builds things out of bits and bytes. <a href="/add/maker/manual/">No Twitter?</a></span>
		</div>
	</form>
    <form method="post" action="/add/product/" class="form-horizontal">
    	<div class="form-group">
        <label class="col-xs-3">Add a Product:</label>
        <div class="input-group input-group-sm col-xs-8">
          <span class="input-group-addon" id="basic-addon1">@</span>
          <input type="text" class="form-control" placeholder="Twitter handle" aria-describedby="basic-addon1" name="twitter_username" autocomplete="off">
            <span class="input-group-btn">
              <button class="btn btn-primary" type="submit">Go</button>
            </span>
        </div>
        <span class="help-block col-xs-offset-3">A digital work, like a web site, app, or service. <a href="/add/product/manual/">No Twitter?</a></span>
      </div>
    </form>
  {else}
    <p><a href="{$sign_in_with_twttr_link}" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-user"></i> Add a maker</a></p>
          <p>A human being who builds things out of bits and bytes.</p>

    <p><a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a product</a></p>
    <p>A digital work, like an app, web site, or service.</p>
  {/if}
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