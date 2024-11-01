jQuery(document).ready(function ($) {
    $('.sv-admin').prepend($('div.updated, div.e-message'));
    $('div.updated, div.e-message').delay(3000).queue(function () { $(this).remove(); });

    $('.sv-sortable-table tbody').sortable({
        placeholder: 'sv-sortable-placeholder',
        handle: '.sv-sortable-handle',
        opacity: .8,
        containment: 'parent',
        stop: function (event, ui) {

            var ordering = $('.sv-sortable-table tbody').sortable('toArray').toString();

            var data =
            {
                action: 'sliderview_update_order',
                order: ordering
            };

            $.post(ajaxurl, data, function (response) { });

        }
    }).disableSelection();

    $('.sv-delete-slider').click(function () {

        if (!confirm(svJSData.translations.confirmSliderDelete))
            return false;

        var item = $(this).parents('tr');
        var key = item.attr('id');

        var data =
        {
            action: 'sliderview_delete_slider',
            key: key,
            nonce: svJSData.nonces.delete_slider
        };

        $.post(ajaxurl, data, function (response) {

            if (response)
                item.fadeOut(400, function () { item.remove(); });

        });

        return false;

    });

    $('.sv-delete-item').click(function () {

        if (!confirm(svJSData.translations.confirmItemDelete))
            return false;

        var item = $(this).parents('tr');
        var key = item.attr('id');

        var data =
        {
            action: 'sliderview_delete_item',
            key: key,
            nonce: svJSData.nonces.delete_item
        };

        $.post(ajaxurl, data, function (response) {

            if (response)
                item.fadeOut(400, function () { item.remove(); });


        });

        return false;

    });

    $('#itemType').change(function () {

        var type = this.value;

        //hide all boxes by default
        $('#videoBox, #linkBox').css('display', 'none');

        if (type == 'video')
            $('#videoBox').css('display', 'block');
        else if (type == 'link') {
            $('#linkBox').css('display', 'block');
            $('#videoBox, #vimeoURLBox, #youtubeURLBox').css('display', 'none');
        }

    });

    $('#videoType').change(function () {

        var type = this.value;

        //hide all boxes by default
        $('#vimeoURLBox, #youtubeURLBox, #resBox').css('display', 'none');

        if (type == 'vimeo')
            $('#vimeoURLBox').css('display', 'block');
        else if (type == 'youtube')
            $('#youtubeURLBox').css('display', 'block');

    });

    var _custom_media = true;
    _orig_send_attachment = wp.media.editor.send.attachment;

    $('#itemPhoto').click(function (e) {

        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        var id = button.attr('id').replace('_button', '');
        _custom_media = true;

        wp.media.editor.send.attachment = function (props, attachment) {

            if (_custom_media) {
                if (attachment.sizes.large) {
                    $('#' + id).val(attachment.sizes.large.url);
                    $('#photo-preview img').attr('src', attachment.sizes.large.url);
                }
                else {
                    $('#' + id).val(attachment.sizes.full.url);
                    $('#photo-preview img').attr('src', attachment.sizes.full.url);
                }
            }

            else
                return _orig_send_attachment.apply(this, [props, attachment]);

        };

        wp.media.editor.open(button);
        return false;

    });

    $('.add_media').on('click', function () {

        _custom_media = false;

    });

    $('#resetHeight').click(function () {

        $('#displayHeight').val('400');
        return false;
    });

    $('#svAddSlider, #svEditSlider').click(function () {
        var errors = false;

        $('#svAddSliderFrm input').removeClass('sv-error-field');
        $('#svEditSliderFrm input').removeClass('sv-error-field');

        if ($('#sliderName').val() == '') {
            $('#sliderName').addClass('sv-error-field');
            errors = true;
        }

        if (($('#displayHeight').val() == '')||(Number($('#displayHeight').val()) < 400)) {
            $('#displayHeight').addClass('sv-error-field');
            errors = true;
        }

        if (errors)
            return false;

    });

    $('#svAddItem, #svEditItem').click(function () {

        var errors = false;

        $('#saveItemForm input, #saveItemForm select').removeClass('sv-error-field');
        $('#saveItemFormEdit input, #saveItemFormEdit select').removeClass('sv-error-field');

        if ($('#itemTitle').val() === '') {
            $('#itemTitle').addClass('sv-error-field');
            errors = true;
        }
        if ($('#itemPhoto').val() === '') {
            $('#itemPhoto').addClass('sv-error-field');
            errors = true;
        }

        if ($('#itemType option:selected').val() === '') {
            $('#itemType').addClass('sv-error-field');
            errors = true;
        }

        if ($('#itemType option:selected').val() === 'link') {
            const regex = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/;
            if(($('#itemLink').val() === '') || (!regex.test($('#itemLink').val()))){
                $('#itemLink').addClass('sv-error-field');
                errors = true;
            }
        }

        if (($('#itemType option:selected').val() === 'video') && !$('#videoType option:selected').val()) {
            $('#videoType').addClass('sv-error-field');
            errors = true;
        }

        if ($('#videoType option:selected').val() === 'vimeo') {
            const regex = /https:\/\/vimeo.com\/([0-9]+)/gm;
            if(($('#vimeoID').val() === '') || !regex.test($('#vimeoID').val())){
                $('#vimeoID').addClass('sv-error-field');
                errors = true;
            }
        }

        if ($('#videoType option:selected').val() === 'youtube') {
            const regex = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
            if(($('#youtubeID').val() === '') || (!regex.test($('#youtubeID').val()))){
                $('#youtubeID').addClass('sv-error-field');
                errors = true;
            }
        }

        if (errors)
            return false;

    });

});

