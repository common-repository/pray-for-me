(function($){

    /**
     * Initialize the settings tabs.
     */
    $('#settings-tabs').tabs();

    /**
     * This event fires when the 'Show Field Name' checkboxes are clicked.
     * When we hide a field, we need to make sure that it is no longer required either.
     */
    $('#show_first_name, #show_last_name, #show_email').click(function(){
        var field_name = $(this).attr('id').substr(5);
        var is_checked = $(this).prop('checked');

        if (!is_checked) {
            $('#require_' + field_name).prop('checked', is_checked);
        }
    });

    /**
     * This event fires when the 'Require Field Name' checkboxes are clicked.
     * When a field is required, we need to make sure that we are showing the field.
     */
    $('#require_first_name, #require_last_name, #require_email').click(function(){
        var field_name = $(this).attr('id').substr(8);
        var is_checked = $(this).prop('checked');

        if (is_checked) {
            $('#show_' + field_name).prop('checked', is_checked);
        }
    });

})(jQuery);