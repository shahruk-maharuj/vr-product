<?php
/**
 * Plugin Name: Custom WooCommerce Variations
 * Description: A custom plugin to display WooCommerce product variations.
 * Version: 1.0
 * Author: Shahruk Maharuj
 */


// Function to display the plugin settings page
// Add a new settings tab in WooCommerce
add_filter('woocommerce_settings_tabs_array', 'add_custom_variations_tab', 50);
function add_custom_variations_tab($settings_tabs) {
    $settings_tabs['custom_variations'] = 'Custom Variations';
    return $settings_tabs;
}

// Display the settings fields
add_action('woocommerce_settings_tabs_custom_variations', 'custom_variations_settings_tab');
function custom_variations_settings_tab() {
    woocommerce_admin_fields(custom_variations_get_settings());
}

// Update the settings when saved
add_action('woocommerce_update_options_custom_variations', 'update_custom_variations_settings');
function update_custom_variations_settings() {
    woocommerce_update_options(custom_variations_get_settings());
}

// Define the settings fields
function custom_variations_get_settings() {
    $settings = array(
        'section_title' => array('name' => 'Custom Variations Settings', 'type' => 'title'),
        'enable_custom_variations' => array('name' => 'Enable Custom Variations', 'type' => 'checkbox', 'id' => 'enable_custom_variations'),
        'section_end' => array('type' => 'sectionend'),
    );
    return $settings;
}

add_action('woocommerce_single_product_summary', 'display_color_swatches', 30);

function display_color_swatches() {
    global $product;

    $sizes = get_terms(['taxonomy' => 'pa_size']); // replace 'pa_size' with your size attribute slug
if (!is_wp_error($sizes) && !empty($sizes)) {
    echo '<select class="size-selection">';
    foreach ($sizes as $size) {
        echo "<option value='{$size->slug}'>{$size->name}</option>";
    }
    echo '</select>';
}


    if ($product->is_type('variable')) {
        $available_variations = $product->get_available_variations();
        echo '<div class="color-swatches">';

        foreach ($available_variations as $variation) {
            $color = $variation['attributes']['attribute_pa_color'];
            $image_url = $variation['image']['url'];
            $price = $variation['display_price']; // Get price for each variation
            echo "<div class='color-swatch' data-image-url='{$image_url}' data-price='{$price}' style='background-color:{$color};'></div>";
        }

        echo '</div>';

        // Corrected code for size selection
        $size_attribute = wc_get_attribute_taxonomy_names()['pa_size']; // Adjust 'pa_size' to match your size attribute name
        $sizes = wc_get_product_terms($product->get_id(), $size_attribute, array('fields' => 'all'));
        echo '<div class="size-selection">';
        foreach ($sizes as $size) {
            echo "<label><input type='radio' name='product-size' value='{$size->slug}'>{$size->name}</label>";
        }
        echo '</div>';
    }
}

function custom_variations_scripts() {
    wp_enqueue_script('custom-variations', plugin_dir_url(__FILE__) . 'assets/js/custom-variations.js', array('jquery'), '1.0', true);
    wp_enqueue_style('custom-variations', plugin_dir_url(__FILE__) . 'assets/css/custom-variations.css', array(), '1.0');
}

add_action('wp_enqueue_scripts', 'custom_variations_scripts');
