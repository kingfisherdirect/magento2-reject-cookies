define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    return function (options, element) {
        var $element = $(element)

        if ($.mage.cookies.get(options.cookieName)) {
            $element.hide();
            return
        } else {
            $element.show();
        }

        var buttons = element.querySelectorAll(options.buttonSelectors);

        Array.prototype.forEach.call(buttons, function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();

                var cookieExpires = new Date(new Date().getTime() + options.cookieLifetime * 1000);
                var isAllow = event.currentTarget.classList.contains('allow')

                $.mage.cookies.set(options.cookieName, JSON.stringify(isAllow ? options.cookieValue : {}), {
                    expires: cookieExpires
                });

                if ($.mage.cookies.get(options.cookieName)) {
                    $element.hide();

                    if (isAllow) {
                        $(document).trigger('user:allowed:save:cookie');
                    }

                    return
                }

                window.location.href = options.noCookiesUrl;
            })
        })
    }
});
