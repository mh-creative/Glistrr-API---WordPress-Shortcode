# Enhanced Conversions - Google Tag Manager (using the DataLayer)

## Project Description
This project initializes a `dataLayer` array if it doesn't already exist and sets up a jQuery function that runs when the document is ready. It checks for the presence of a form with the ID (e.g. `contact`). If the form exists, it defines a function to extract data from the form fields and push this data to the `dataLayer`. It then attaches a submit event listener to the form to prevent the default submission, serialize the form data, and call the function to send the data to the `dataLayer`.

For a detailed configuration tutorial using Google Tag Manager, please refer to this [YouTube video](https://www.youtube.com/watch?v=QbFMSEXEt5g).

## Installation Instructions

### Step 1
Upload `js/enhanced-conversions.js` to your website project.

### Step 2
Include the script in your website header or footer. In this example, the `functions.php` file includes `wp_enqueue` to include the file in a WordPress project.

`
<?php

/** Register Enhanced Conversions - Custom JS File - Footer **/

function enqueue_custom_script() {
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/enhanced-conversions.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');

?>
`
## Authors
Michael Hayes
