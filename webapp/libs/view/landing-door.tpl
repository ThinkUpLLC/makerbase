{if $is_waitlisted}
{include file="_head.tpl" suppress_search='true'}


<div class="row">
   <div class="page-header col-sm-8 col-sm-offset-2 col-xs-12">
    <h1>
      Great! You're on the waitlist.
    </h1>

    <h2>
      <small>Wanna skip the line? Ask a friend who's already in Makerbase to add you.</small>
    </h2>
  </div>
</div>

{else}
{include file="_head.tpl" suppress_navbar='true'}

<div class="row landing-row" id="landing-header">
   <div class="col-sm-8 col-sm-offset-2 col-xs-12">
    <h3>You make great things. Tell the world what you made.</h3>
    <h1 class="col-xs-7">
      Makerbase
    </h1>
    <div class="col-xs-5">
      <a class="btn btn-primary btn-lg" href="/twittersignin/" id="join-button">Are you in?</a>
      <h6>(sign in with Twitter)</h6>
    </div>
  </div>
</div>

<div class="row landing-row" id="landing-company">
  <div class="col-sm-8 col-sm-offset-2 col-xs-12">
    <div class="col-xs-12">
      <h2>Coming Soon</h2>
      <p>Makerbase is in preview, with <strong>limited access</strong> while we get ready.</p>
      <p>In the meantime, <a href="/twittersignin/">get on the waiting list</a>. (If someone has added you as a maker already, you can get a sneak peek.) We won't spam you.</p>
    </div>
  </div>
</div>

<div class="row landing-row" id="landing-makers">
  <div class="col-sm-8 col-sm-offset-2 col-xs-12">
    <div class="col-xs-6" id="landing-makers-illustration">
      &nbsp;
    </div>
    <div class="col-xs-6">
      <h2>Makers</h2>
      <div class="col-xs-12">
        <ul class="feature-list">
          <li> List projects you helped create, like apps, websites, podcasts and more</li>
          <li>Show off weekend projects or the work you do at your day job&mdash;anything you want</li>
          <li>Find out who made projects that inspire you</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="row landing-row" id="landing-projects">
  <div class="col-sm-8 col-sm-offset-2 col-xs-12">
    <div class="col-xs-6">
      <h2>Projects</h2>
      <div class="col-xs-12">
        <ul class="feature-list">
          <li>Makerbase is for every project&mdash;even the ones that didn’t work out or that have ended</li>
          <li>List everyone who worked on your project, and describe what they did</li>
          <li>Let the world know what tools you used to get the job done</li>
        </ul>
      </div>
    </div>
    <div class="col-xs-6" id="landing-projects-illustration">
    </div>
  </div>
</div>

<div class="row landing-row" id="landing-makerbase-different">
  <div class="col-sm-8 col-sm-offset-2 col-xs-12">
    <h2>Makerbase is <strong>different</strong></h2>
    <div class="col-xs-12">
      <ul class="feature-list ">
        <li><strong>Anyone can edit Makerbase.</strong> You don’t have to be “Wikipedia-worthy” to have a great Makerbase page. And you can add the people that inspire you.</li>
       <li><strong>It’s not a resume.</strong> We don’t care what your job title was, or even what company you were working at. It’s about what you actually did.</li>
       <li><strong>It’s like “IMDB for apps”.</strong> Movie fans can look at IMDB and see who made their favorite films. Music fans look at liner notes. All of us use apps and websites, and now we'll know who made them!</li>
      </ul>
    </div>
  </div>
</div>

{/if}

{include file="_footer.tpl"}

    {if $is_waitlisted}
      {literal}
      <script>
        $.getJSON( "/requestinvites/{/literal}{$waitlisted_twitter_id}{literal}/", function( data ) {
          var items = [];
          $.each( data, function( key, val ) {
            items.push( "<li class='list-group-item'>" +
              "<div class='media-left media-middle'>" +
                "<img class='media-object' src='http://avatars.io/twitter/" +
                val.follower_username +
                "' alt='" +
                val.follower_username +
                "' width='40' height='40'>" +
              "</div><div class='media-body'>" +
                "<a href='https://twitter.com/share?text=Hey%20@" + val.follower_username +
                "%20can%20you%20add%20me%20to%20%40makerbase%20so%20I%20can%20add%20my%20projects%3F%20Thanks!&amp;url=http://makerba.se/add/maker/?q=%2540{/literal}{$waitlisted_username}{literal}'" +
                "class='btn btn-primary btn-lg' onclick=\"javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\">" +
                "Ask @" + val.follower_username +
               "</a>" +
              "</div>"+
              "</li>" );
          });

          $( "<ul/>", {
            "class": "list-group",
            html: items.join( "" )
          }).appendTo( "#beg_invites" );
        });
      </script>
      {/literal}
    {/if}
