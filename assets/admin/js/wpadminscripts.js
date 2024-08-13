jQuery(document).ready(function($) {
    "use strict";

    // Event handler for clicking on labels inside .apsw_radio_box
    $(this).on("click", ".apsw_radio_box label", function(e) {
        e.preventDefault(); // Prevent the default label click behavior if necessary

        var $label = $(this);
        var $radio = $label.find('input[type="radio"]');

        // Remove active class from all labels and add to the clicked one
        $label.closest('.apsw_radio_box').find('label').removeClass('active');
        $label.addClass('active');

        // Check the corresponding radio button
        $radio.prop('checked', true);

        // Perform additional actions, such as enabling/disabling other elements
        if ($radio.val() === 'content') {
            // Enable the 'content' option
            $('#content_option').removeAttr('disabled');
            // Disable the 'excerpt' option
            $('#excerpt_option').attr('disabled', 'disabled');
        } else if ($radio.val() === 'excerpt') {
            // Enable the 'excerpt' option
            $('#excerpt_option').removeAttr('disabled');
            // Disable the 'content' option
            $('#content_option').attr('disabled', 'disabled');
        }
    });

    // Event handler for dismissing the notice
    $(document).on('click', '.apsw-notice-nux .notice-dismiss', function() {
        $.ajax({
            url: apsw_loc.ajaxurl,
            type: 'post',
            data: {
                action: 'apsw_dismiss_notice',
                nonce: apsw_loc.nonce,
            }
        });
    });

});
