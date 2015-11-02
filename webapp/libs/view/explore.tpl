
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
maker_section_title='trending makers'
product_section_title='trending projects'
users_section_title='trending contributors'
makers=$trending_makers
products=$trending_products
users=$trending_users
}

{include file="_featured.tpl"
maker_section_title='newest makers'
product_section_title='newest projects'
users_section_title='newest contributors'
makers=$newest_makers
products=$newest_products
users=$newest_users
}


<div class="row" id="promo-boxes">

    <div class="feature-box col-xs-12 col-sm-10 col-sm-offset-1" id="landing-sponsors" style="min-height: 75px;">
      <h3 class="col-xs-12">brought to you by sponsors that makers <em>really love</em>.</h3>
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
maker_section_title='featured makers'
product_section_title='featured projects'
users_section_title='top contributors'
makers=$featured_makers
products=$featured_products
users=$featured_users
}


{include file="_footer.tpl"}
