
<nav class="navbar navbar-fixed-bottom" style="background-color: #666; border-top: 4px solid black; padding-top: 6px;">
  <div class="container-fluid">
    <div class="navbar-header">
      <h3><a style="color:white" href="/s3cr3t">Admin</a></h3>
    </div>

	<ul class="nav navbar-nav navbar-right">
        {if isset($maker)}<li><button class="btn btn-warning btn-sm" style="margin-right: 30px;">Freeze maker</button></li>{/if}
        {if isset($product)}<li><button class="btn btn-warning btn-sm" style="margin-right: 30px;">Freeze project</button></li>{/if}
        {if isset($user)}<li><button class="btn btn-warning btn-sm" style="margin-right: 30px;">Freeze user</button></li>{/if}
		{if isset($maker) || isset($product)}<li><button href="#" class="btn btn-danger btn-sm" style="margin-right: 30px;">DELETE</button></li>{/if}
	</ul>

  </div>
</nav>