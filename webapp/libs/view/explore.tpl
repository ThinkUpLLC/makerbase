
{include file="_head.tpl" body_class='landing' title="Explore makers and projects on Makerbase"}

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
