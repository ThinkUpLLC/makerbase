{*
Display user's account page.

Expected vars:

$user User object
$logged_in_user Currently logged-in user object
$email_capture_state either 'need email', 'confirmation_pending' or 'confirmation_success'

*}
{include file="_head.tpl"}

<div class="row" id="user-profile">
  <div class="col-xs-2 col-sm-2 col-sm-offset-1">
    <img class="img-responsive" src="{insert name='user_image' image_url=$user->avatar_url image_proxy_sig=$image_proxy_sig type='m'}" alt="{$user->name}" width="100%">
  </div>
  <div class="col-xs-8 col-sm-8">
    <h1><strong>{$user->twitter_username}</strong> makes Makerbase</h1>
    <h5><a href="{$user->url}">{$user->url}</a></h5>
    <h5><a href="https://twitter.com/intent/user?user_id={$user->twitter_user_id}">@{$user->twitter_username}</a></h5>
  </div>
</div>

{if isset($logged_in_user)}
  {if $logged_in_user->twitter_user_id eq $user->twitter_user_id}

  <div class="row" id="user-settings">
     <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="jumbotron">

        {if $email_capture_state eq 'need_email'}

          <h2 class="col-xs-12 col-sm-10 col-sm-offset-1">We're asking for your email, but don't worry!</h2>

            {include file="_user.email-form.tpl" is_collapsed=false}

          <p class="col-xs-12 col-sm-10 col-sm-offset-1">We'll only email you when your stuff changes (like updates to your Maker page), and <strong>we won't spam you</strong>.</p>

          <p></p>

        {elseif $email_capture_state eq 'confirmation_pending'}

          <h2 class="col-xs-12 col-sm-10 col-sm-offset-1">Hey, check your email!</h2>

          <p class="col-xs-12 col-sm-10 col-sm-offset-1">
            We sent a confirmation email to <strong>{$user->email}</strong>. Click the link in that message to verify your address.
          </p>

          <form id="resend-confirm" method="post" class="col-xs-12 col-sm-10 col-sm-offset-1">
            <a href="#" onClick="$('#user-email-capture').toggle();" class="btn btn-sm btn-link pull-right">Whoops! Wrong address.</a>
            <input type="hidden" name="resend" value="1">
            <button type="submit" class="btn btn-sm btn-primary">Re-send email</button>
          </form>

            {include file="_user.email-form.tpl" is_collapsed=true}

            <p></p>

        {elseif $email_capture_state eq 'confirmation_success'}

          <h2 class="col-xs-12 col-sm-10 col-sm-offset-1">
            Your Makerbase settings<br />
            <small>(These settings are visible only to you.)</small>
          </h2>

          <h3 class="col-xs-12 col-sm-10 col-sm-offset-1">
            Email: <strong>{$user->email}</strong>
            <a href="#" onClick="$('#user-email-capture').toggle();" class="btn btn-sm btn-link pull-right">Change your email address</a>
          </h3>

            {include file="_user.email-form.tpl" is_collapsed=true}

            <p></p>
        {/if}

        </div>
     </div>
  </div>

  <div class="row" id="report-this">
    <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <a type="button" class="btn btn-default btn-link pull-right" href="mailto:team@makerba.se?subject=Help!">Need help with your account?</a>

    </div>
  </div>

  {include file="_actions.tpl"}

  {else}

    {include file="_actions.tpl" object=$user object_type='user'}

  {/if}
{/if}

{include file="_footer.tpl"}