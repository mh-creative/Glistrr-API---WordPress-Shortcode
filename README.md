# Glistrr API - WordPress Shortcode

## Project Description
This project querys the API https://boris.glistrr.com/v1/widgets/events for specific client ID's to obtain event data. The data is presented on the website front and is controlled by filter controls and pagination. 


## Installation Instructions

### Step 1
Upload `shortcode` to your WordPress website project theme, e.g. child theme.

### Step 2
Include the script in your website header or footer. In this example, the `functions.php` file includes `wp_enqueue` to include the file in a WordPress project.

```
<?php

// functions.php
// include_once(get_template_directory() . '/shortcode/events.php'); // Use this if referencing parent theme
include_once(get_stylesheet_directory() . '/shortcode/events.php'); // Use this if referencing child theme

?>
```

### Step 3
Embed the shortcode `[events]` onto your desired web page.

## Authors
Michael Hayes
