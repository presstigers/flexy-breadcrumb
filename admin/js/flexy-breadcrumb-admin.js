/**
 * Flexy Breadcrumb JS File - V 1.0.0
 *
 * @author PressTigers <support@presstigers.com>, 2016
 *
 * Actions List
 * - Toggle Settings Option's Tabs Callback
 * - Wp Color Picker Callback
 * - Accept Numbers Input Callback
 * - Save Settings Ajax Callback
 * - Font Awesome Icon Picker Callback
 */
(function ($) {
    'use strict';

    $(document).ready(function () {

        //  Toggle Settings Option's Tabs
        window.toggleDiv = function (id) {
            $(".sub-menu li").removeClass('active');
            var items = $(".sub-menu").find('a[href="' + id + '"]');
            items.parents('li').addClass('active');
            items.addClass('active');
            $('.main-content').children().not('#submit-btn').hide();
            $(id).fadeIn(200);
            location.hash = id + "-show";
        }

        // On save -> Show save option's Tab
        var hash = window.location.hash.substring(1);
        var id = hash.split("-show")[0];
        if (id) {
            $(".sub-menu li").removeClass('active');
            var items = $(".sub-menu").find('a[href="' + id + '"]');
            items.parents('li').addClass('active');
            $('.main-content').children().not('#submit-btn').hide();
            $("#" + id).show();
        }

        // Wp Color Picker        
        $('.fbc-color-picker').wpColorPicker();

        // Accept Numbers Input Only
        $(".numbers-only").keypress(function (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        });

        // Save Settings Options
        $('#fbc-options-form').submit(function () {
            $('.loading-div').fadeIn(100);
            var formData = $(this).serialize();

            $.post('options.php', formData).error(
                    function () {
                        $('.error-msg').slideDown();
                        window.location.reload(false);
                    }).success(function () {
                $('.loading').hide();
                $('.success-msg').slideDown();
                window.location.reload(false);
            });

            return false;
        });

        // Font Awesome Icon Picker
        $('.fbc-home-icon').iconpicker({placement: 'bottomRight' });
    });
})(jQuery);