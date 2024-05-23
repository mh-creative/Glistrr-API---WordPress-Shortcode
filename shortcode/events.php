<?php
// events.php

include_once('includes/api-functions.php');
include_once('includes/html-functions.php');
include_once('includes/ajax-functions.php');

wp_enqueue_style('custom-styles', get_stylesheet_directory_uri() . '/shortcode/includes/styles.css');

// Enqueue scripts and localize for AJAX
// Function to enqueue scripts
function enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/event-filter.js', array('jquery'), '1.0.0', true);

    // Localize the script with data
    wp_localize_script('custom-scripts', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'brand_id_param' => 'brand_id', // Pass the parameter names to the JavaScript
        'client_id_param' => 'client_id',
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_scripts');

// Register the shortcode
add_shortcode('events', 'events_link');

?>