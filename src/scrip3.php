<?php

// Function to create variations for a product
function create_product_variations($product_id) {
    // Get the product object
    $product = wc_get_product($product_id);
    
    // Check if the product is a variable product
    if($product->is_type('variable')) {
        // Get an array of all attributes for the product
        $attributes = $product->get_attributes();
        
        // Initialize an array to store all possible combinations of attributes
        $all_combinations = array(array());
        
        // Loop through each attribute
        foreach($attributes as $attribute) {
            // Get the terms (attribute values) for the current attribute
            $terms = wc_get_product_terms($product_id, $attribute['name'], array('fields' => 'names'));
            
            // Initialize an array to store the combinations for the current attribute
            $attribute_combinations = array();
            
            // Loop through each combination from the previous iteration
            foreach($all_combinations as $combination) {
                // Loop through each term for the current attribute
                foreach($terms as $term) {
                    // Add the term to the current combination
                    $attribute_combinations[] = array_merge($combination, array($attribute['name'] => $term));
                }
            }
            
            // Replace the all_combinations array with the newly created attribute combinations
            $all_combinations = $attribute_combinations;
        }
        
        // Get the existing variations for the product
        $existing_variations = $product->get_children();
        
        // Initialize a counter to keep track of the new variations created
        $new_variations_created = 0;
        
        // Loop through each combination of attributes
        foreach($all_combinations as $combination) {
            // Check if a variation with the same attributes already exists
            if(!in_array($combination, $existing_variations)) {
                // Create a new variation
                $variation_id = $product->create_variation($combination);
                
                // Increment the counter
                $new_variations_created++;
            }
        }
        
        // Return the number of new variations created
        return $new_variations_created;
    }
}

?>
