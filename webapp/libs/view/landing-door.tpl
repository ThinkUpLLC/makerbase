{include file="_head.tpl" suppress_search='true'}

{if $is_waitlisted}

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

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12" id="beg_invites">
    </div>
</div>

{else}

<div class="row">
   <div class="page-header col-sm-8 col-sm-offset-2 col-xs-12">
    <h1>
      Makerbase is coming soon.<br />
      <small>You make great things. Tell the world what you made.</small>
    </h1>
  </div>
</div>

<div class="row">

	<div class="col-sm-8 col-sm-offset-2 col-xs-12 jumbotron">

        <h1><strong>Makerbase</strong> is:</h1>
        <ul class="list">
          <li>A place to tell the world what you made, and to find out who made the things you love.</li>
          <li>Edited by <strong>everyone</strong>. You can change and add to anything that you see.</li>
          <li>A way to see <strong>projects</strong>: Stuff like apps and web sites and digital works of art. (Not companies or employers.)</li>
          <li>A list of <strong>makers</strong>: People who create projects, described by what they've <em>done</em>, not their job titles.</li>
          <li><strong>Limited access</strong>, for now, while we get things ready for everyone. We'll add you to the waitlist if someone hasn't already added you as a maker.</li>
        </ul>

		<a class="btn btn-primary btn-lg" href="/twittersignin/" id="history">Are you in?</a>
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
                "%20can%20you%20add%20me%20to%20%40makerbase%20so%20I%20can%20add%20my%20projects%3F%20Thanks!&amp;url=http://makerba.se/add/maker/?q={/literal}{$waitlisted_username}{literal}'" +
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
