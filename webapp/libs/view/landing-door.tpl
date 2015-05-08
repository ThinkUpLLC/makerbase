{include file="_head.tpl" suppress_search='true'}

{if $is_waitlisted}

<div class="row">
   <div class="page-header col-sm-8 col-sm-offset-2 col-xs-12">
    <h1>
      Great! You're on the waitlist.<br />
      <small>Wanna get in now? Ask a current user to add you as a maker.</small>
    </h1>
  </div>
</div>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
      {assign var="tweet_body" value="Hey can anybody add me as a maker on @makerbase?"}
      <a class="btn btn-primary btn-lg" href="https://twitter.com/share?text={$tweet_body|urlencode}" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Ask your friends</a>
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
		<p class="lead">Who makes the apps you use? What side project are you really proud of? Who made that one really cool feature on your favorite site? With Makerbase, you can find out. List every project you've made, and name all the makers you collaborated with.</p>

		<a class="btn btn-primary btn-lg" href="/twittersignin/">Are you in?</a>
	</div>

</div>

<div class="row">

    <div class="col-sm-8 col-sm-offset-2 col-xs-12">

        <p>Makerbase is in limited preview while we get things ready for everyone to to see. To access Makerbase, someone who's already in has to list you as a maker. If you're not in yet, we'll add you to our waitlist.</p>
    </div>

</div>
{/if}

{include file="_footer.tpl"}
