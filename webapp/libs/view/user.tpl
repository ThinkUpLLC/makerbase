{*
Display user's account page.

Expected vars:

$user User object
$logged_in_user Currently logged-in user object
$email_capture_state either 'need email', 'confirmation_pending' or 'confirmation_success'

*}
{include file="_head.tpl"}

<div class="row" id="maker-profile">
  <div class="col-xs-2 col-sm-2 col-sm-offset-1">
    <img class="img-responsive" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$user->name}" width="100%">
  </div>
  <div class="col-xs-8 col-sm-8">
    <h1><strong>{$user->twitter_username}</strong> makes Makerbase</h1>
    <h5><a href="{$user->url}">{$user->url}</a></h5>
  </div>
</div>

{if isset($logged_in_user)}
  {if $logged_in_user->twitter_user_id eq $user->twitter_user_id}

<div class="row">
   <div class="page-header col-sm-10 col-sm-offset-1 col-xs-12">
      <div class="jumbotron">

      {if $email_capture_state eq 'need_email'}

        <h1 class="">We're asking for your email, but don't worry.</h1>

          {include file="_user.email-form.tpl" is_collapsed=false}

        <ul class="list col-md-offset-1">
          <li>We'll only email you when your stuff changes (like updates to your Maker page)</li>
          <li>We <strong>won't spam you</strong></li>
        </ul>

      {elseif $email_capture_state eq 'confirmation_pending'}

        <h1>Hey, check your email!</h1>

        <p>We sent a confirmation email to <strong>{$user->email}</strong>. Click the link in that message to verify your address.</p>

        <div class="row">
        <form id="resend-confirm" method="post">
          <input type="hidden" name="resend" value="1">
          <button type="submit" class="btn btn-sm btn-primary col-xs-offset-1">Re-send email</button>
        </form>
          <a href="#" onClick="$('#user-email-capture').toggle();" class="btn btn-sm btn-link pull-right">Whoops! Wrong address.</a>

          {include file="_user.email-form.tpl" is_collapsed=true}
        </div>

      {elseif $email_capture_state eq 'confirmation_success'}

        <h2>Your Makerbase settings</h2>
        <h3>These settings are visible only to you.</h3>

        <p>Email: <strong>{$user->email}</strong></p>

          <a href="#" onClick="$('#user-email-capture').toggle();" class="btn btn-sm btn-link pull-right">Change your email address</a>

          {include file="_user.email-form.tpl" is_collapsed=true}

      {/if}

      </div>
   </div>
</div>

  {/if}
{/if}

<div class="row">
  <div id="history" class="col-sm-10 col-sm-offset-1 col-xs-12 history-vivid">

    <h3>Recent activity</h3>

    {if sizeof($actions) > 0}
    <ul class="list-group">
    {foreach $actions as $action}
        <li class="list-group-item col-xs-12">
        {include file="_action.tpl"}
        </li>
    {/foreach}
    </ul>
    {/if}
  </div>

  <nav id="pager" class="col-sm-10 col-sm-offset-1 col-xs-12">
    <ul class="pager">
      {if isset($next_page)}
        <li class="previous"><a href="/u/{$user->uid}/{$next_page}"><span aria-hidden="true">&larr;</span> Older</a></li>
      {/if}
      {if isset($prev_page)}
        <li class="next"><a href="/u/{$user->uid}{if $prev_page neq 1}/{$prev_page}{/if}">Newer <span aria-hidden="true">&rarr;</a></li>
      {/if}
    </ul>
  </nav>

  </div>
</div>

{include file="_footer.tpl"}