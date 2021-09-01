
(function($) {
    "use strict";

    $.mnotify = function(textOptions, options) {
        var stackContainer, messageWrap, messageBox, messageBody, messageTextBox, closeButton, messagePicture, image, messageHeader;

        textOptions = $.extend({
            title: undefined,
            message: undefined,
            image: undefined
        }, textOptions);

        options = $.extend({
            lifetime: 3000,
            click: undefined
        }, options);

        stackContainer = $('#notifier-box');
        if (!stackContainer.length) {
            stackContainer = $('<div>', {id: 'notifier-box'}).prependTo(document.body);
        }

        messageWrap = $('<div>').addClass('message-wrap').css('display', 'none');
        messageBox = $('<div>').addClass('message-box');

        messageHeader = $('<div>', {
            text: textOptions.title
        }).addClass('message-header');

        messageBody = $('<div>').addClass('message-body');

        messageTextBox = $('<span>');
        messageTextBox.append(textOptions.message);

        closeButton = $('<a>', {
            href: '#',
            title: 'Close notify',
            click: function(event) {
                $(this).parent().parent().fadeOut(300, function() {
                    $(this).remove();
                });
                event.preventDefault();
                return false;
            }
        }).addClass('message-close');

        if (textOptions.image != undefined) {
            messagePicture = $('<div>').addClass('thumb');
            image = $('<img>', {
                src: textOptions.image
            });
        }

        messageWrap.appendTo(stackContainer).fadeIn();
        messageBox.appendTo(messageWrap);
        closeButton.appendTo(messageBox);
        messageHeader.appendTo(messageBox);
        messageBody.appendTo(messageBox);

        if (messagePicture != undefined) {
            messagePicture.appendTo(messageBody);
            image.appendTo(messagePicture);
        }
        messageTextBox.appendTo(messageBody);

        if (options.lifetime > 0) {
            setTimeout(function() {
                $(messageWrap).fadeOut(300, function() {
                    $(this).remove();
                });
            }, options.lifetime);
        }

        if (options.click != undefined) {
            messageWrap.click(function(e) {
                if (!jQuery(e.target).is('.message-close')) {
                    options.click.call(this);
                }
            });
        }

        return this;
    }
})(jQuery);