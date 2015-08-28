
{include file="_head.tpl" body_class='landing'}

<div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <div class="jumbotron style-pea">
      <h3>
        Explore Makerbase<br />
        <small>Meet the makers who build the projects that make the internet go.</small>
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
maker_section_title='featured makers'
product_section_title='featured projects'
users_section_title='top contributors'
makers=$featured_makers
products=$featured_products
users=$featured_users
}

{include file="_featured.tpl"
maker_section_title='newest makers'
product_section_title='newest projects'
users_section_title='newest contributors'
makers=$newest_makers
products=$newest_products
users=$newest_users
}


{include file="_footer.tpl"}
