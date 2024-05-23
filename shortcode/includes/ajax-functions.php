<?php
// ajax-functions.php

// Events AJAX request handler
// functions.php
add_action('wp_ajax_filter_events', 'filter_events');
add_action('wp_ajax_nopriv_filter_events', 'filter_events');

function filter_events() {
    $fromDate = sanitize_text_field($_POST['from_date']);
    $toDate = sanitize_text_field($_POST['to_date']);
    $brandId = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : null;
    $clientId = isset($_POST['client_id']) ? intval($_POST['client_id']) : null;
    $limit = intval(sanitize_text_field($_POST['limit']));
    $offset = intval(sanitize_text_field($_POST['offset']));

    $client_ids = (!empty($clientId)) ? [$clientId, 3586, 3669] : [3586,3587,3588,3589,3590,3591,3592,3593,3594,3595,3596,3597,3669];
    $data = get_events_data($client_ids, $fromDate, $toDate, $brandId);

    if ($data && isset($data['status']) && $data['status'] == 1) {
        $events = $data['data']['events'];

        // The below filtering logic should no longer be required, since the API will be providing filtered data in the first place

        // Reset filters if both from and to dates are empty
        if (empty($fromDate) && empty($toDate) && empty($brandId) && empty($clientId)) {
            $filteredEvents = $events;
        } else {
            $clientTagMap = (object) [
                "3587" => "Causeway Coast and Glens Borough Council",
                "3588" => "Fermanagh and Omagh District Council",
                "3589" => "Derry City and Strabane District Council",
                "3590" => "Ards and North Down Borough Council",
                "3591" => "Antrim and Newtownabbey Borough Council",
                "3592" => "Lisburn and Castlereagh City Council",
                "3593" => "Armagh City Banbridge and Craigavon Borough Council",
                "3594" => "Belfast City Council",
                "3595" => "Mid and East Antrim Borough Council",
                "3596" => "Mid Ulster District Council",
                "3597" => "Newry Mourne and Down District Council"
            ];

            // Determine the clientTag that we'll check for in the vanilla Go Succeed / Go Succeed NI accounts
            $clientTag = isset($clientTagMap->{$clientId}) ? $clientTagMap->{$clientId} : null;

            // Filter events based on the date, brand, and client filters
            $filteredEvents = array_filter($events, function ($event) use ($clientId, $clientTag) {
                // Client filter logic
                $eventClientId = $event['client_id'];

                // Check if we have events from the vanilla Go Succeed account
                $clientTagExists = false;
                if(!empty($clientId) && in_array($eventClientId, ['3586', '3669']) && $clientTag) {
                    // For each of the events, check if we have a tag matching the intented client from the mapping
                    foreach($event['tags'] as $tag) {
                        if($tag == $clientTag) {
                            $clientTagExists = true;
                            break;
                        }
                    }
                }

                return empty($clientId) || $eventClientId == $clientId || $clientTagExists;
            });
        }

        $totalCount = count($filteredEvents);
        $slicedEvents = array_slice($filteredEvents, $offset, $limit);

        // Determine if we have prev/next available pages
        $page_prev = ($offset > 0);
        $page_next = ($totalCount - $offset - $limit > 0);

        // Generate HTML
        $eventsHtml = generate_events_html($slicedEvents, $page_prev, $page_next, $totalCount);

        // Return success response with HTML
        wp_send_json_success(array('eventsHtml' => $eventsHtml));
    } else {
        // Return error response
        wp_send_json_error(array('message' => 'Error: Unable to fetch event data.'));
    }

    // Terminate the script
    wp_die();
}
?>
