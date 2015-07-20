{if isset($field)}


<nav class="navbar" id="navbar-alert">

  {if isset($success_msgs.$field)}
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      {$success_msgs.$field}
    </div>
  {/if}
  {if isset($error_msgs.$field)}
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {$error_msgs.$field}
    </div>
  {/if}
  {if isset($info_msgs.$field)}
    {if isset($success_msgs.$field) OR isset($error_msgs.$field)}<br />{/if}
    <div class="alert alert-info alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {$info_msgs.$field|filter_xss}
    </div>
  {/if}

</nav>

{else}

<nav class="navbar" id="navbar-alert">

  {if isset($success_msg)}
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {$success_msg}
    </div>
  {/if}
  {if isset($error_msg)}
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {$error_msg}
    </div>
  {/if}
  {if isset($info_msg)}
    {if isset($success_msg) OR isset($error_msg)}<br />{/if}
    <div class="alert alert-info alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
      {$info_msg}
    </div>
  {/if}
  
</nav>

{/if}
