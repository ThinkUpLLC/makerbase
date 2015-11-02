
{include file="_head.tpl" body_class='landing'}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="jumbotron style-mint">
      <h3>
        Explore Makerbase<br />
        <small>The makers. The projects. The people who make the internet go.</small>
      </h3>
    </div>
  </div>
</div>

{include file="_featured.tpl"
maker_section_title='Trending Makers'
product_section_title='Trending Projects'
users_section_title='Trending Contributors'
makers=$trending_makers
products=$trending_products
trending_inspirations=$trending_inspirations
newest_inspirations=$newest_inspirations
users=$trending_users
}

{include file="_featured.tpl"
maker_section_title='Newest Makers'
product_section_title='Newest Projects'
users_section_title='Newest Contributors'
makers=$newest_makers
products=$newest_products
trending_inspirations=null
newest_inspirations=null
users=$newest_users
}


<div class="row" id="promo-boxes">

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors" style="min-height: 75px;">
      <h3 class="col-xs-12">Brought to you by sponsors that makers <em>really love</em>.</h3>
      <p class="col-xs-12 col-sm-2"><small>See who's using these great services:</small></p>

      <ul class="list-inline col-sm-10">
        <li class="col-xs-12 col-sm-4">
          <a href="/p/9u0s6y/mailchimp" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-mailchimp.jpg" alt="MailChimp">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/m348b6/slackhq" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-slack.png" alt="Slack">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
        <li class="col-xs-12 col-sm-4">
          <a href="/p/7p97ga/hover" class="sponsor">
              <img class="img-rounded avatar pull-left" src="/assets/img/sponsors/logo-square-hover.png" alt="Hover">
              <buttton class="pull-right sponsor-projects">projects <i class="fa fa-arrow-right"></i></buttton>
            </a>
        </li>
      </ul>

    </div>
</div>

{include file="_featured.tpl"
maker_section_title='Featured Makers'
product_section_title='Featured Projects'
users_section_title='Top Contributors'
makers=$featured_makers
products=$featured_products
trending_inspirations=null
newest_inspirations=null
users=$featured_users
}


{include file="_footer.tpl"}
