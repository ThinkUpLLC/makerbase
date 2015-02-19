<?php

echo "TRUNCATE makers; TRUNCATE roles; TRUNCATE products;

";

foreach (glob("makers/*.json") as $filename) {
    $maker_json = file_get_contents($filename);
    $maker = json_decode($maker_json);
    echo "INSERT INTO makers (slug, username, name, url, avatar_url) VALUES ('"
        .$maker->id."', '".$maker->id."', '".$maker->name."', '"
        .$maker->html_url."', '".$maker->avatar_url."');

";
}


foreach (glob("projects/*.json") as $filename) {
    $product_json = file_get_contents($filename);
    $product = json_decode($product_json);
    echo "INSERT INTO products (slug, name, description, url, avatar_url) VALUES ('"
        .$product->id."', '".mysql_escape($product->name)."', '"
        .mysql_escape($product->description)."', '"
        .$product->html_url."', '".$product->avatar_url."');
";
    echo "SELECT LAST_INSERT_ID() INTO @product_id;
";
    foreach ($product->participations as $part) {
        echo "SELECT id INTO @maker_id FROM makers WHERE slug = '".$part->maker."';
";
        echo "INSERT INTO roles (product_id, maker_id, role, start, end) VALUES "
            ."(@product_id, @maker_id, '".mysql_escape($part->role)."', '"
            .$part->start."', ".(isset($part->end)?"'".$part->end."'":'NULL').");
";
    }
    echo "

";
}

function mysql_escape($string) {
    return str_replace( "'", "''", $string );
}