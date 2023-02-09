<?php
// Get all products with children (variations)
$args = array(
  'post_type' => 'product',
  'post_status' => 'any',
  'post_parent' => 0,
  'meta_query' => array(
    array(
      'key' => '_has_child',
      'value' => 'yes',
    ),
  ),
);
$products = wc_get_products($args);

// Iterate through each product
foreach ($products as $product) {

  // Get all attributes for the product
  $attributes = $product->get_attributes();

  // Get all possible attribute values for each attribute
  $attribute_values = array();
  foreach ($attributes as $attribute) {
    $attribute_values[] = $attribute->get_options();
  }

  // Get all variations for the product
  $variations = $product->get_children();
  $variation_attributes = array();
  foreach ($variations as $variation) {
    $variation_product = wc_get_product($variation);
    $variation_attributes[] = $variation_product->get_attributes();
  }

  // Check if the number of variations is less than the number of possible attribute values
  if (count($variations) < count($attribute_values)) {

    // Get all possible combinations of attribute values
    $attribute_combinations = $this->get_attribute_combinations($attribute_values);

    // Iterate through each attribute combination
    foreach ($attribute_combinations as $attribute_combination) {

      // Check if this combination already exists as a variation
      $exists = false;
      foreach ($variation_attributes as $variation_attribute) {
        if ($attribute_combination === $variation_attribute) {
          $exists = true;
          break;
        }
      }

      // If the combination does not exist, create a new variation
      if (!$exists) {
        $variation_id = wc_create_product_variation($product->get_id(), $attribute_combination);
        $variations[] = $variation_id;
        $variation_attributes[] = $attribute_combination;
      }
    }
  }
}

// Helper function to get all possible combinations of attribute values
function get_attribute_combinations($attribute_values) {
  $combinations = array();
  $attribute_count = count($attribute_values);
  $combination_count = pow(2, $attribute_count);
  for ($i = 1; $i < $combination_count; $i++) {
    $combination = array();
    for ($j = 0; $j < $attribute_count; $j++) {
      if (pow(2, $j) & $i) {
        $combination[] = $attribute_values[$j];
      }
    }
    $combinations[] = $combination;
  }
  return $combinations;
}
