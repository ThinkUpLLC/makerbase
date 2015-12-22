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
            {if $logged_in_user->twitter_user_id neq $user->twitter_user_id}{* viewing someone else *}
              <h1>
                <strong>{$user->twitter_username}</strong> edits Makerbase
              </h1>
              <h5><a href="{$user->url}" class="text-muted" rel="nofollow">{$user->url}</a></h5>

              <div id="unfollow{$user->uid}" class="follow-button" {if $logged_in_user->is_following_user eq false}style="display: none;"{/if} ><a class="btn btn-xs btn-default btn-unfollow" uid="{$user->uid}" type="user" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Unfollowing" onclick="$('follow{$user->uid}').toggle();">Following</a></div>
              <div id="follow{$user->uid}" class="follow-button" {if $logged_in_user->is_following_user eq true}style="display: none;"{/if} ><a class="btn btn-xs btn-info btn-follow" uid="{$user->uid}" type="user" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Following" onclick="$('unfollow{$user->uid}').toggle();">Follow</a></div>

            {else}{* viewing themselves *}
                <h1>
                  <strong>You</strong> edit Makerbase
                </h1>
            {/if}


          {else}{* not logged in *}
              <h1>
                <strong>{$user->twitter_username}</strong> edits Makerbase
              </h1>
              <h5><a href="{$user->url}" class="text-muted" rel="nofollow">{$user->url}</a></h5>


              <div class="follow-button"><a class="btn btn-xs btn-info" href="{$sign_in_with_twttr_link}">Follow</a></div>

          {/if}

          {include file="_twitterprofile.tpl"  twitter_user_id=$user->twitter_user_id}
          {include file="_reportpage.tpl"  object=$user object_type='user'}

          {if isset($logged_in_user)}
            {if $logged_in_user->twitter_user_id neq $user->twitter_user_id}{* viewing someone else *}
              {if isset($user->maker)}
                <h3><a href="/m/{$user->maker->uid}/{$user->maker->slug}" class="btn btn-md btn-default">
                  <img src="{$user->maker->avatar_url}" class="img-rounded" style="height: 2em; padding-right: .5em;" />
                  See what {$user->maker->name|escape} makes <i class="fa fa-arrow-right"></i>
                </a></h3>
              {else}
                <h3><a class="btn btn-md btn-default" href="/add/maker/?q={'@'|urlencode}{$user->twitter_username|urlencode}">Add this maker</a></h3>
              {/if}
            {else}
              {if isset($user->maker)}
                <h3><a href="/m/{$user->maker->uid}/{$user->maker->slug}" class="btn btn-md btn-default">
                  <img src="{$user->avatar_url}" class="img-rounded" style="height: 2em; padding-right: .5em;" />
                  See what you make <i class="fa fa-arrow-right"></i>
                </a></h3>
              {else}
                <h3><a class="btn btn-md btn-default" href="/add/maker/?q={'@'|urlencode}{$user->twitter_username|urlencode}">Add yourself as a maker</a></h3>
              {/if}
            {/if}
          {/if}

        </div>

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
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" onchange="this.form.submit()"{if ($user->is_subscribed_announcements_email eq true) } checked="true"{/if} name="announcements_email"> Email me Makerbase news &amp; announcements
              </label>
            </div>
              {**
               * Comment this in when Makerbase starts sending friend activity digests
               *
            <div class="checkbox">
              <label>
                <input type="checkbox" onchange="this.form.submit()"> Email me when my friends update their stuff
              </label>
            </div>
              **}
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
