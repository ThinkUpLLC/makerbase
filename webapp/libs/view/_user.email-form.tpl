{*
Show form to change a user's email address.

Expected vars:

$is_collapsed Default to false
*}
<form id="user-email-capture" class="row{if isset($is_collapsed) && $is_collapsed} collapse{/if}" method="post">
	<div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="input-group">
      <input type="email" class="form-control" placeholder="yourname@example.com" name="email" autocomplete="on" id="user-email">
      <span class="input-group-btn">
        <button class="btn btn-primary" type="submit">That's me!</button>
      </span>
    </div><!-- /input-group -->
  </div>
</form>
