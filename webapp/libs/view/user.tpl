{*
Display user's account page.

Expected vars:

$user User object
$logged_in_user Currently logged-in user object
$email_capture_state either 'need email', 'confirmation_pending' or 'confirmation_success'

*}
{include file="_head.tpl"}

<div class="row" id="user-profile">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="media">
      <div class="media-body">
        <div id="user-info">

          {if isset($logged_in_user)}
            {if $logged_in_user->twitter_user_id neq $user->twitter_user_id}
              <h1>
                <strong>{$user->twitter_username}</strong> edits Makerbase
              </h1>
              <h5><a href="{$user->url}" class="text-muted" rel="nofollow">{$user->url}</a></h5>
        </div>
              {if isset($user->maker)}
                <h3><a href="/m/{$user->maker->uid}/{$user->maker->slug}" class="btn btn-xl btn-primary">See what {$user->maker->name|escape} makes <i class="fa fa-arrow-right"></i></a></h3>
              {else}
                <p class="text-muted"><a href="/add/maker/?q={'@'|urlencode}{$user->twitter_username|urlencode}">Add this maker</a></p>
              {/if}
            {else}
                <h1>
                  <strong>You</strong> edit Makerbase
                </h1>
        </div>
                {if isset($user->maker)}
                  <h3><a href="/m/{$user->maker->uid}/{$user->maker->slug}" class="btn btn-xl btn-primary">See what you make <i class="fa fa-arrow-right"></i></a></h3>
                {else}
                  <p class="text-muted"><a href="/add/maker/?q={'@'|urlencode}{$user->twitter_username|urlencode}">Add yourself as a maker</a></p>
                {/if}
            {/if}
          {else}
              <h1>
                <strong>{$user->twitter_username}</strong> edits Makerbase
              </h1>
              <h5><a href="{$user->url}" class="text-muted" rel="nofollow">{$user->url}</a></h5>
        </div>
              {if isset($user->maker)}
                  <h3><a href="/m/{$user->maker->uid}/{$user->maker->slug}" class="btn btn-xl btn-primary">See what {$user->maker->name|escape} makes <i class="fa fa-arrow-right"></i></a></h3>
              {else}
                <p class="text-muted"><a href="/add/maker/?q={'@'|urlencode}{$user->twitter_username|urlencode}">Add this maker</a></p>
              {/if}
          {/if}

          {if isset($logged_in_user)}
            {if $logged_in_user->twitter_user_id neq $user->twitter_user_id}
              {include file="_twitterprofile.tpl"  twitter_user_id=$user->twitter_user_id}
              {include file="_reportpage.tpl"  object=$user object_type='user'}
            {/if}
          {else}
              {include file="_twitterprofile.tpl"  twitter_user_id=$user->twitter_user_id}
              {include file="_reportpage.tpl"  object=$user object_type='user'}
          {/if}

      </div>
    </div>

  </div>
</div>



{if isset($logged_in_user)}
  {if $logged_in_user->twitter_user_id eq $user->twitter_user_id}
  {** BEGIN SHOW LOGGED-IN USER THEIR OWN SETTINGS **}

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

          <form id="send-notifications" method="post" class="col-xs-12 col-sm-10 col-sm-offset-1">
            <input type="hidden" name="email_subs_updated" value="1">
            <div class="checkbox">
              <label>
                <input type="checkbox" onchange="this.form.submit()"{if ($user->is_subscribed_maker_change_email eq true) } checked="true"{/if} name="maker_change_email"> Email me when someone changes my stuff
              </label>
              {**
               * Comment this back in when Makerbase starts sending friend activity digests
               *
              <br />
              <label>
                <input type="checkbox" onchange="this.form.submit()"> Email me when my friends update their stuff
              </label>
              **}
            </div>
          </form>

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

  {** END SHOW LOGGED-IN USER THEIR OWN SETTINGS **}
  {else}

    {** LOGGED IN AS ANOTHER USER **}

    {include file="_actions.tpl"}

  {/if}

{else}

    {** NOT LOGGED IN **}

    {include file="_actions.tpl"}

{/if}

{include file="_footer.tpl"}