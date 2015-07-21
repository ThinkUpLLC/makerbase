{*
Show form to change a user's email address.

Expected vars:

$is_collapsed Default to false
*}
<form id="user-email-capture" class="row{if isset($is_collapsed) && $is_collapsed} collapse{/if}" method="post">
  <input type="email" class="input-lg col-xs-7 col-md-6 col-md-offset-1" placeholder="yourname@example.com" name="email" autocomplete="on" id="user-email">
  <button type="submit" class="btn btn-lg btn-primary col-xs-5 col-md-3">That's me!</button>
</form>
