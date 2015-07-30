<?php

/**********************************************/
/***  MAKERBASE FEATURED MAKERS & PROJECTS  ***/
/**********************************************/

//** Featured Makers **//

// First featured Maker
$featured_maker_1_array = array(
    'name' => 'Ayah Bdeir',
    'avatar_url' => 'http://pbs.twimg.com/profile_images/1251593795/ayah_bdeir_cropped_lowres.jpg',
    'uid' => 'g4414b',
    'slug' => 'ayahbdeir'
);

$featured_maker_1_products = array();
$featured_maker_1_products[] = array(
    'name' => 'littleBits',
    'uid' => '1e2224',
    'slug' => 'littlebits'
);

// Second featured Maker
$featured_maker_2_array = array(
    'name' => 'Buster Benson',
    'avatar_url' => 'https://pbs.twimg.com/profile_images/596779047134199808/IBpBry99_400x400.jpg',
    'uid' => 'u438n4',
    'slug' => 'buster'
);

$featured_maker_2_products = array();
$featured_maker_2_products[] = array(
    'name' => '750 Words',
    'uid' => '3v53xr',
    'slug' => '750words'
);

$featured_maker_2_products[] = array(
    'name' => 'Peabrain',
    'uid' => 'jx3208',
    'slug' => 'peabrain'
);

$featured_maker_2_products[] = array(
    'name' => 'Health Month',
    'uid' => 'dk2x9t',
    'slug' => 'healthmonth'
);

$featured_maker_2_products[] = array(
    'name' => 'Locavore',
    'uid' => '2dd6xe',
    'slug' => 'locavore'
);

// Third featured Maker
$featured_maker_3_array = array(
    'name' => 'Marques Brownlee',
    'avatar_url' => 'http://pbs.twimg.com/profile_images/590305718151970816/kk7QHF2q.jpg',
    'uid' => '8b04i1',
    'slug' => 'baratunde'
);

$featured_maker_3_products = array();
$featured_maker_3_products[] = array(
    'name' => 'MKBHD',
    'uid' => '7kviq5',
    'slug' => 'mkbhd'
);

//** Featured Products **//

// First featured Product
$featured_product_1_array = array(
    'name' => 'Detroit Water Project',
    'avatar_url' => 'http://pbs.twimg.com/profile_images/513416804165619712/YiMgw-U3.png',
    'uid' => '104y4n',
    'slug' => 'detwaterproject'
);

$featured_product_1_makers = array();
$featured_product_1_makers[] = array(
    'name' => 'Tiffani Ashley Bell',
    'uid' => '8b04i1',
    'slug' => 'tiffani'
);

$featured_product_1_makers[] = array(
    'name' => 'Kristy Tillman',
    'uid' => '8vciz8',
    'slug' => 'kristyt'
);

// Second featured Product
$featured_product_2_array = array(
    'name' => 'What is Code?',
    'avatar_url' => 'http://www.bloomberg.com/graphics/2015-paul-ford-what-is-code/images/emotes/angry.gif',
    'uid' => '15js4s',
    'slug' => 'whatiscode'
);

$featured_product_2_makers = array();
$featured_product_2_makers[] = array(
    'name' => 'Paul Ford',
    'uid' => '5lav62',
    'slug' => 'ftrain'
);

$featured_product_2_makers[] = array(
    'name' => 'Jim Aley',
    'uid' => 'btdsg5',
    'slug' => 'jimaley'
);

$featured_product_2_makers[] = array(
    'name' => 'Toph Tucker',
    'uid' => 'bjb48n',
    'slug' => 'tophtucker'
);

// Third featured Product
$featured_product_3_array = array(
    'name' => 'The Standesk 2200',
    'avatar_url' => 'http://www.wired.com/images_blogs/wiredscience/2012/09/mf-standing-desk_ikeab.jpg',
    'uid' => '227405',
    'slug' => 'thestandesk2200'
);

$featured_product_3_makers = array();
$featured_product_3_makers[] = array(
    'name' => 'Colin Nederkoorn',
    'uid' => '2k53m4',
    'slug' => 'alphacolin'
);

$featured_product_3_makers[] = array(
    'name' => 'Ryan Witt',
    'uid' => 'z49b24',
    'slug' => 'onecreativenerd'
);



class FeatureHelper {

    public static function getFeaturedMakers($featured_maker_1_array, $featured_maker_1_products,
        $featured_maker_2_array, $featured_maker_2_products, $featured_maker_3_array, $featured_maker_3_products) {

        $featured_makers = array();

        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0);
        $product_defaults = array_merge(array('avatar_url'=>null, 'description'=>null), $defaults);

        $featured_maker_1 = new Maker(array_merge($featured_maker_1_array, $defaults));
        $featured_maker_1->products = array();
        foreach($featured_maker_1_products as $product_array) {
            $featured_maker_1->products[] = new Product(array_merge($product_array, $product_defaults));
        }

        $featured_maker_2 = new Maker(array_merge($featured_maker_2_array, $defaults));
        $featured_maker_2->products = array();
        foreach($featured_maker_2_products as $product_array) {
            $featured_maker_2->products[] = new Product(array_merge($product_array, $product_defaults));
        }

        $featured_maker_3 = new Maker(array_merge($featured_maker_3_array, $defaults));
        $featured_maker_3->products = array();
        foreach($featured_maker_3_products as $product_array) {
            $featured_maker_3->products[] = new Product(array_merge($product_array, $product_defaults));
        }

        $featured_makers[] = $featured_maker_1;
        $featured_makers[] = $featured_maker_2;
        $featured_makers[] = $featured_maker_3;
        return $featured_makers;
    }

    public static function getFeaturedProducts($featured_product_1_array, $featured_product_1_makers,
        $featured_product_2_array, $featured_product_2_makers, $featured_product_3_array, $featured_product_3_makers) {

        $featured_products = array();

        $defaults = array('id'=>null, 'creation_time'=>null, 'url'=>null, 'is_archived'=>0, 'is_frozen'=>0,
            'description'=>null);
        $maker_defaults = array_merge(array('avatar_url'=>null), $defaults);

        $featured_product_1 = new Product(array_merge($featured_product_1_array, $defaults));
        $featured_product_1->makers = array();
        foreach($featured_product_1_makers as $maker_array) {
            $featured_product_1->makers[] = new Maker(array_merge($maker_array, $defaults, $maker_defaults));
        }

        $featured_product_2 = new Product(array_merge($featured_product_2_array, $defaults));
        $featured_product_2->makers = array();
        foreach($featured_product_2_makers as $maker_array) {
            $featured_product_2->makers[] = new Maker(array_merge($maker_array, $defaults, $maker_defaults));
        }

        $featured_product_3 = new Product(array_merge($featured_product_3_array, $defaults));
        $featured_product_3->makers = array();
        foreach($featured_product_3_makers as $maker_array) {
            $featured_product_3->makers[] = new Maker(array_merge($maker_array, $defaults, $maker_defaults));
        }

        $featured_products[] = $featured_product_1;
        $featured_products[] = $featured_product_2;
        $featured_products[] = $featured_product_3;
        return $featured_products;
    }
}

$ISOSCELES_CFG['featured_makers'] = FeatureHelper::getFeaturedMakers($featured_maker_1_array,
    $featured_maker_1_products, $featured_maker_2_array, $featured_maker_2_products, $featured_maker_3_array,
    $featured_maker_3_products);

$ISOSCELES_CFG['featured_products'] = FeatureHelper::getFeaturedProducts($featured_product_1_array,
    $featured_product_1_makers, $featured_product_2_array, $featured_product_2_makers, $featured_product_3_array,
    $featured_product_3_makers);

$ISOSCELES_CFG['featured_users'] = array('2r068g', 'xrqyy7', 'lkq49x', '55lz73');
