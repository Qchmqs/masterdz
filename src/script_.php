<?php
// phpinfo();
ini_set('display_errors', '1'); 
ini_set('memory_limit', '256M');

require_once( 'wp-load.php' );

$args = array(
    'status' => 'publish',
    'limit' => -1,
);
$products = wc_get_products($args);
foreach($products as $product) {

    $attributes = $product->get_attributes();
    $variations_attribute = "";
    foreach($attributes as $attribue) {
      if($attribue->get_variation())
        $variations_attribute = $attribue->get_taxonomy();
    }

    $attributes_count = count(explode(",",$product->get_attribute($variations_attribute)));
    $variations_count = count($product->get_children());

    if(
        $attributes_count == $variations_count 
        || $variations_count == 0 && $attributes_count == 1
      )
      continue;
    echo "<br>";
    echo "attr:".$attributes_count;
    echo "<br>";
    echo "var:".$variations_count;
    echo "<br>";

    echo "title:".$product->get_name();
    echo "<br>";
    echo "sku:".$product->get_sku();
    echo "<br>";
    echo "ID:".$product->get_id();
    echo $product->list_attributes();

    $variations = $product->get_children();
    foreach($variations as $variation) {
    echo "<br>";
    $p = wc_get_product($variation);
    echo $p->get_name();
    echo "<br>";
    echo $p->get_id();
    echo "<br>";
    echo "publié:".$p->get_status();
    }
    echo "<br>";
    echo "<br>";
}
