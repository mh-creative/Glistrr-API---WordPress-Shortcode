<?php
// html-functions.php

function generate_events_html($events, $page_prev, $page_next, $total_events) {
    ob_start(); // Start output buffering
    ?>
    <div class="container filters mt-4">
        <div class="row">
			<div class="col-md-3 mb-3">
				<label for="client-brand-filter" class="fw-bold">Business Stage:</label>
				<select id="client-brand-filter" name="client-brand-filter" class="form-control">
					<option value="">All Business Stages</option>
					<option value="1">Start</option>
					<option value="2">Grow</option>
					<option value="3">Scale</option>
					<!-- Add more options as needed -->
				</select>
			</div>
			<div class="col-md-3 mb-3">
				<label for="client-id-filter" class="fw-bold">Council:</label>
				<select id="client-id-filter" name="client-id-filter" class="form-control">
					<option value="">All Councils</option>
					<option value="3591">Antrim and Newtownabbey Borough Council</option>
					<option value="3590">Ards and North Down Borough Council</option>
					<option value="3593">Armagh City, Banbridge and Craigavon Borough Council</option>
					<option value="3594">Belfast City Council</option>
					<option value="3587">Causeway Coast and Glens Borough Council</option>
					<option value="3589">Derry City and Strabane District Council</option>
					<option value="3588">Fermanagh and Omagh District Council</option>
					<option value="3592">Lisburn and Castlereagh City Council</option>
					<option value="3595">Mid and East Antrim Borough Council</option>
					<option value="3596">Mid Ulster District Council</option>
					<option value="3597">Newry, Mourne and Down District Council</option>				
					<!-- Add more options as needed -->
				</select>
			</div>
            <div class="col-md-2 mb-2">
                <label for="date-filter-from" class="fw-bold">From:</label>
                <input type="date" id="date-filter-from" name="date-filter-from" class="form-control">
            </div>
            <div class="col-md-2 mb-2">
                <label for="date-filter-to" class="fw-bold">To:</label>
                <input type="date" id="date-filter-to" name="date-filter-to" class="form-control">
            </div>
			<div class="col-md-2 mb-2 d-flex align-items-center">
				<button id="reset-filter" class="btn btn-secondary">Reset</button>
				<span id="loading-spinner" class="d-none"> Loading...</span>
			</div>
        </div>
    </div>
	<div class="container mt-1">
		<div class="row events-container">
			<?php
			// Display event count below filters
			echo '<label class="events-count"> <span class="fw-bold">Total Events:</span> ' . $total_events . '</label>';
	
			if (empty($events)) {
				echo "<p class='error-message'>Check back soon for updates on events in this category</p>";
			} else {
				foreach ($events as $event) {
					$domain = "https://www.glistrr.com/events/e"; // Replace this with your actual domain
					$slug = $event['slug'];
					$url = $domain . '/' . $slug;
					?>
					<div class='col-md-4'>
						<div class='event mb-4 no-link-styling'>
							<a href="<?php echo $url; ?>" target="_blank"> <!-- Change: added target="_blank" -->
								<div class="event-container">
									<img src='<?php echo $event['image_url']; ?>' alt='Event Image' class='img-fluid event-image'>
									<div class="event-content">
										<div class="event-date">
										<?php
										// Set the default timezone to Europe/London
										date_default_timezone_set('Europe/London');

										// Assuming $event['start'] contains the event start time in UTC or the correct timezone
										$start_time_utc = strtotime($event['start']);

										// Convert the UTC start time to the Europe/London timezone
										$timezone = new DateTimeZone('Europe/London');
										$start_time = new DateTime('@' . $start_time_utc);
										$start_time->setTimezone($timezone);

										// Display the event time
										echo $start_time->format('D, d M Y g:i a');
										?>

										</div>
										<div class="event-title"><?php echo $event['name']; ?></div>
										<!--?php
										// Get the event description from the API response
										$eventDescription = $event['description'];

										// Use str_replace to find all occurrences of '<p>' and replace them with '<p class="event-description">'
										$eventDescriptionWithClass = str_replace('<p>', '<p class="event-description">', $eventDescription);

										// Output the modified event description with the added class
										echo $eventDescriptionWithClass;
										?-->
										<div class="venue-name"><?php echo $event['venue_name']; ?></div>, <div class="venue-location"><?php echo $event['venue_location']; ?></div>
										<div class="brand-id">
											Step:
											<?php
											$brandId = $event['client_brand_id'];

											switch ($brandId) {
												case 1:
													echo 'Start';
													break;
												case 2:
													echo 'Grow';
													break;
												case 3:
													echo 'Scale';
													break;
												default:
													echo 'Other';
											}
											?>
										</div>
										<div class="client-id">
											<?php
											$clientId = $event['client_id'];

											switch ($clientId) {
												case 3586:
													echo 'by Go Succeed';
													break;
												case 3587:
													echo 'by Causeway Coast and Glens Borough Council';
													break;
												case 3588:
													echo 'by Fermanagh and Omagh District Council';
													break;
												case 3589:
													echo 'by Derry City and Strabane District Council';
													break;
												case 3590:
													echo 'by Ards and North Down Borough Council';
													break;
												case 3591:
													echo 'by Antrim and Newtownabbey Borough Council';
													break;
												case 3592:
													echo 'by Lisburn and Castlereagh City Council';
													break;
												case 3593:
													echo 'by Armagh City, Banbridge and Craigavon Borough Council';
													break;
												case 3594:
													echo 'by Belfast City Council';
													break;
												case 3595:
													echo 'by Mid and East Antrim Borough Council';
													break;
												case 3596:
													echo 'by Mid Ulster District Council';
													break;
												case 3597:
													echo 'by Newry, Mourne and Down District Council';
													break;
												case 3669:
													echo 'by Go Succeed';
													break;
												default:
													echo 'Other';
											}
											?>
									
										</div>										
									</div>
									<div class="more-info">
										<span>Book Now</span>
									</div>
								</div>
							</a>
						</div>
					</div>
					<?php
				}

				echo "<div class='d-flex'>";
					if($page_prev) {
						echo "<button type='button' class='btn btn-lg btn-primary text-white me-2'><a class='text-white no-text-decoration' href='#' id='prev-events'>Prev</a></button>";
					}

					if($page_next) {
                        echo "<button type='button' class='btn btn-lg btn-primary text-white me-2'><a class='text-white no-text-decoration' href='#' id='next-events'>Next</a></button>";
					}
				echo "</div>";
			}
			?>
		</div>
	</div>
    <?php
    $html = ob_get_clean(); // Get the buffered output and clear the buffer
    return $html;
}


function events_link($atts) {
	$client_ids = [3586,3587,3588,3589,3590,3591,3592,3593,3594,3595,3596,3597,3669];
	$limit = 12;
	$offset = 0;
    $data = get_events_data($client_ids, null, null, null, null);

    if ($data && isset($data['status']) && $data['status'] == 1) {
        $events = $data['data']['events'];
        $totalCount = count($events);
        $slicedEvents = array_slice($events, $offset, $limit);
    } else {
        $events = [];
    	$totalCount = 0;
    }

    $page_next = ($totalCount - $offset - $limit > 0);

    return generate_events_html($slicedEvents, false, $page_next, $totalCount);
}
?>