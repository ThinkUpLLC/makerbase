{include file="_head.tpl"}

<h1>we made {$product->name} <button type="button" class="btn btn-success pull-right">Hey, I helped make {$product->name}!</button></h1>

<div class="row">
  <div class="col-xs-2">
  	<img src="{insert name='user_image' image_url=$product->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" class="img-responsive" width="100%" />
    <p>{$product->description}</p>
	<p><a href="{$product->url}">{$product->url}</a></p>
  </div>
  <div class="col-xs-10">
  	<ul class="list-group">
  		{foreach $roles as $role}
		<li class="list-group-item">
			<span class="badge">{if isset($role->years) && $role->years > 0}{$role->years} year{if $role->years neq 1}s{/if}{else}{$role->start_MY}{/if}</span>
  			<div class="media-left">
				<img class="media-object" src="{insert name='user_image' image_url=$role->maker->avatar_url image_proxy_sig=$image_proxy_sig type='maker'}" alt="maker" width="100">
			</div>
			<div class="media-body">
				<h3><a href="/m/{$role->maker->slug}">{$role->maker->name}</a></h3>
				{$role->role}
			</div>
		</li>
  		{/foreach}
	</ul>

  {if isset($logged_in_user)}
    <br>
    <form method="post" action="/add/role/">
          <label>Maker:</label>
          <input type="hidden" name="product_slug" value="{$product->slug}">
        <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">@</span>
          <input type="text" class="form-control" placeholder="Twitter username" name="maker_slug">
        </div>
        <div class="input-group">
          <label>Start date (required):</label>
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="start_date">
        </div>
        <div class="input-group">
          <label>End date (optional):</label>
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="end_date">
        </div>
        <div class="input-group">
          <label>Role:</label>
          <input type="text" class="form-control" placeholder="Herded unicorns" name="role">
        </div>
        <button class="btn btn-default" type="submit">Add role</button>
    </form>
{else}
    <p><a href="{$sign_in_with_twttr_link}">Sign in</a> to add someone here.</p>
{/if}

  </div>

</div>


{include file="_footer.tpl"}