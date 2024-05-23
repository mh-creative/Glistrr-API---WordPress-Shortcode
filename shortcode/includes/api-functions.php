<?php
// api-functions.php

function get_events_data($client_ids=null, $from_date=null, $to_date=null, $brand_id=null) {
    // Generate a unique key based on the function parameters
    $cache_key = 'events_data_' . md5(serialize([$client_ids, $from_date, $to_date, $brand_id]));

    // Try to get cached data
    $cached_data = get_transient($cache_key);

    // If cached data exists, return it
    if ($cached_data !== false) {
        return $cached_data;
    }

    $params = [
        "client_ids" => implode(",", $client_ids),
        "limit" => 250,
        "offset" => 0
    ];

    if(!empty($from_date)) $params["date_from"] = $from_date;
    if(!empty($to_date)) $params["date_to"] = $to_date;
    if(!empty($brand_id)) $params["brands"] = $brand_id;

    // API URL
    $api_url = "https://boris.glistrr.com/v1/widgets/events?".http_build_query($params);

    // Make the GET request to the API endpoint using wp_remote_get
    $response = wp_remote_get($api_url);

    $data = is_array($response) ? json_decode(wp_remote_retrieve_body($response), true) : false;

    // Removing below -- currently gives no user-facing error
    // Check if there are no events
    // if ($data && isset($data['status']) && $data['status'] == 1 && empty($data['data']['events'])) {
    //     $data = ['error' => 'Check back soon for updates on events in this category'];
    // }

    // Cache the data for 1 hour (3600 seconds)
    set_transient($cache_key, $data, 3600);

    return $data;
}
?>
