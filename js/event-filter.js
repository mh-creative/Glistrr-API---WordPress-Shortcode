// Event Filter jQuery
jQuery(document).ready(function($) {
    var page_size = 12;
    var offset = 0;

    function triggerAjaxWithFilters() {
        var fromDate = $('#date-filter-from').val();
        var toDate = $('#date-filter-to').val();
        var brandId = $('#client-brand-filter').val();
        var clientId = $('#client-id-filter').val();
        triggerAjaxRequest(fromDate, toDate, brandId, clientId);
    }

    // Event listener for date and filter inputs
    $(document).on('input change', '#date-filter-from, #date-filter-to, #client-brand-filter, #client-id-filter', function() {
        // Reset the offset to 0
        offset = 0;

        triggerAjaxWithFilters();
    });

    // Event listener for reset button
    $(document).on('click', '#reset-filter', function() {
        // Clear date and filter values
        $('#date-filter-from, #date-filter-to, #client-brand-filter, #client-id-filter').val('');

        // Reset the offset to 0
        offset = 0;

        // Trigger AJAX request with empty filter values
        triggerAjaxRequest('', '', '', '');
    });

    $(document).on('click', '#next-events, #prev-events', function(e) {
        e.preventDefault();

        if($(this).hasClass('disabled')) return false;

        // If next page, increase offset by page size, otherwise decrease
        if($(this).is("#next-events")) {
            offset += page_size;
        } else {
            offset -= page_size;
        }
        triggerAjaxWithFilters();

        $('html,body').animate({
           scrollTop: $(".events-hero-image").offset().top
        });
    });

    // Function to trigger AJAX request with filter values
    function triggerAjaxRequest(fromDate, toDate, brandId, clientId) {
        // Show loading spinner
        $('#loading-spinner').removeClass('d-none');

        // Make AJAX request to fetch events HTML based on the filter values
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'filter_events',
                from_date: fromDate,
                to_date: toDate,
                brand_id: brandId,
                client_id: clientId,
                limit: page_size,
                offset: offset
            },
            success: function(response) {
                if (response && response.success) {
                    // Create a new events container and replace the old one
                    var newContainer = $('<div>').html(response.data.eventsHtml);
                    $('.events-container').replaceWith(newContainer.find('.events-container'));
                } else {
                    // Handle errors or no data
                    console.error('Error updating events container');
                }
            },
            complete: function() {
                // Hide loading spinner
                $('#loading-spinner').addClass('d-none');
            },
        });
    }

});
