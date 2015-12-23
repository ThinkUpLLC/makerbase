{if isset($logged_in_user)}

{** BEGIN SIGNED-IN LANDING PAGE **}
{include file="_head.tpl" suppress_search='true' body_class='landing'}


{if !$logged_in_user->has_added_product || !$logged_in_user->has_added_maker || !$logged_in_user->has_added_role}

  <div class="row">
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
      <div class="jumbotron style-seabreeze">
        <h1><strong>Welcome to Makerbase!</strong> Let's make amazing things.</h1>
        <ul>
          <li {if $logged_in_user->has_added_maker}class="completed"{/if}><strong>Create a maker.</strong> Enter your name, or the name of anyone who makes cool stuff.</li>
          <li {if $logged_in_user->has_added_product}class="completed"{/if}><strong>Create a project.</strong> Enter anything on the internet&mdash;even old stuff from the past.</li>
          <li {if $logged_in_user->has_added_role}class="completed"{/if}>Describe the tasks a maker completed on a project, including when the work was done.</li>
        </ul>
        {if !$logged_in_user->has_added_product && !$logged_in_user->has_added_maker}
        <p>Everything starts with search. Find what you're looking for, and press the "Create" button for anything that's missing.</p>
        {/if}
      </div>
    </div>
  </div>

{/if}


<div class="row">
   <div class="col-sm-10 col-sm-offset-1 col-xs-12">

    <form class="navbar-form" role="search" action="/search/" id="homepage-search">
      <div class="" id="remote-search">
          <input type="search" class="form-control typeahead" placeholder="search or add..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
    </form>
  </div>
</div>

{include file="_actions.tpl"}

{include file="_featured.tpl"
maker_section_title='Featured Makers'
product_section_title='Featured Projects'
users_section_title='Top Contributors'
makers=$featured_makers
products=$featured_products
users=$featured_users
}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <br />
    <a class="btn btn-info pull-right" href="/explore/"> Explore &nbsp;<img src="/assets/img/arrow-right-white.svg"></a>
  </div>
</div>

{** END SIGNED-IN LANDING PAGE **}

{else}

{** BEGIN NON-SIGNED-IN LANDING PAGE **}


{include file="_head.tpl" suppress_navbar='true' body_class='landing-not-signed-in'}

<div class="row landing-row" id="landing-header">
   <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <h3 class="col-xs-12">You make great things. Tell the world what you made.</h3>
    <a href="/"><h1 class="col-xs-12 col-sm-8">
      Makerbase
    </h1></a>
    <div class="col-xs-12 col-sm-4">
      <a class="btn btn-success btn-lg pull-right" href="/twittersignin/" id="join-button">Sign in with Twitter </a>
      <h6 class="pull-right">It's free!</h6>
    </div>
  </div>
</div>

<div class="row">
   <div class="col-xs-12 col-sm-10 col-sm-offset-1">

    <form class="navbar-form" role="search" action="/search/" id="homepage-search">
      <div class="" id="remote-search">
        <input type="search" class="form-control input-lg typeahead" placeholder="search by maker or project..." name="q" autocomplete="off" id="nav-typeahead">
      </div>
    </form>

  </div>
</div>

<!-- begin quote -->
</div>
<div class="container-fluid">

  <div class="row" id="testimonial-quote">
    <h2 class="col-xs-12"><a href="/explore/">Explore Makerbase! <img src="/assets/img/arrow-right-white.svg" style="padding-left: .25em; margin-top: -.25em;"></a><br />
    <small><i><a href="/explore/">Connect with people and projects that will inspire you.</a></i></small></h2>
  </div>

</div>

<div class="container">

<!-- end quote -->

{include file="_featured.tpl"
maker_section_title='Featured Makers'
product_section_title='Featured Projects'
users_section_title='Top Contributors'
makers=$featured_makers
products=$featured_products
users=$featured_users
}

{include file="_actions.tpl"}

<div class="row" id="promo-boxes">

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors">
      <h1 class="col-xs-12">brought to you by sponsors that makers <em>love to use</em>.</h1>
      <p class="col-xs-12 col-sm-3 col-lg-4">See which projects that inspire you rely on these great products.</p>

      <ul class="list-inline col-sm-9 col-lg-8">
        <li class="col-xs-12 col-sm-4">
          <a href="/p/9u0s6y/mailchimp" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-mailchimp.jpg" alt="MailChimp">
              <button class="btn pull-right sponsor-projects">projects </button>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/m348b6/slackhq" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-slack.png" alt="Slack">
              <button class="btn pull-right sponsor-projects">projects </button>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/7p97ga/hover" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-hover.png" alt="Hover">
              <button class="btn pull-right sponsor-projects">projects </button>
            </a>
        </li>
      </ul>

    </div>

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-makers">
      <h1 class="col-xs-12">makers.</h1>
      <p class="col-xs-12 col-sm-5">List projects you helped create at work and in your free time. Or find out who made the apps, websites, podcasts and other projects that inspire you every day.</p>
    </div>

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-projects">
      <h1 class="col-xs-12">projects.</h1>
      <p class="col-xs-12 col-sm-5">Makerbase is for every project you created &mdash; even the ones that didn't work out. List everyone who worked with you on projects, describe what they did, and reveal what tools you all used to get the job done.</p>
    </div>

</div>

{** END NON-SIGNED-IN LANDING PAGE **}
{/if}

{include file="_footer.tpl"}
