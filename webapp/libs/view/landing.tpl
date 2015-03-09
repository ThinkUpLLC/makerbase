{include file="_head.tpl"}

<div class="row">
  <div class="col-sm-5 col-xs-12">

    <h3>Makerbase</h3>
    <p>A directory of people who make things.</p>

    <h4>Make Makerbase</h4>
  {if isset($logged_in_user)}
  <strong>Add a maker</strong>
  <p>A human being who builds things out of bits and bytes.</p>
	<form method="post" action="/add/maker/" class="form-horizontal">
		<div class="form-group">
			<label class="col-xs-3">Maker:</label>
			<div class="input-group input-group-sm col-xs-8">
			  <span class="input-group-addon" id="basic-addon1">@</span>
			  <input type="text" class="form-control" placeholder="Twitter username" aria-describedby="basic-addon1" name="twitter_username">
				<span class="input-group-btn">
			        <button class="btn btn-success" type="submit">Go</button>
			    </span>
			</div>
		</div>
	</form>
    <div>
      <div class="pull-right"><a href="#">Or enter maker by hand</a></div>
    </div>
    <br><br><br>
  <strong>Add a product</strong>
    <p>A digital work, like a web site, app, or service.</p>
    <form method="post" action="/add/product/" class="form-horizontal">
    	<div class="form-group">
        <label class="col-xs-3">Product:</label>
        <div class="input-group input-group-sm col-xs-8">
          <span class="input-group-addon" id="basic-addon1">@</span>
          <input type="text" class="form-control" placeholder="Twitter handle" aria-describedby="basic-addon1" name="twitter_username">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Go</button>
            </span>
        </div>
      </div>
    </form>
    <div>
      <div class="pull-right"><a href="#">Or enter product by hand</a></div>
    </div>
  {else}
    <p><a href="{$sign_in_with_twttr_link}" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-plus"></i> Add a maker</a></p>
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