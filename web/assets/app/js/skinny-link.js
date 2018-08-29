/**
 * Invoke this to deal with API request
 *
 * @type {{init, new}}
 */
var SkinnyLinkModule = (function() {
    'use strict';

    var __host;
    var __resultDivId;
    var __buttonLoadId;
    var __goButtonId;
    var __redirectUrl;
    var __copyLinkId;
    var __this = this;

    return {
        init: function(obj) {
            __host          = obj.host;
            __resultDivId   = obj.resultDivId;
            __buttonLoadId  = obj.buttonLoadId;
            __goButtonId    = obj.goButtonId;
            __copyLinkId    = obj.copyLinkId;
            __redirectUrl   = obj.redirectUrl;
        },
        new: function(url) {
            if (url === '') {
                return false;
            }

            var $goButton   = $(__goButtonId);
            var $skinnyLink = $(__resultDivId);
            var $loader     = $(__buttonLoadId);
            var $copyLink   = $(__copyLinkId);

            $goButton.hide();
            $loader.show();

            $.ajax({
                url:        '/api/new',
                type:       'POST',
                dataType:   'json',
                async:      true,
                data: {'url' : url},
                success: function(data, status) {
                    var _url = __redirectUrl.replace('!123abc', data.data.skinnyUrl);

                    $skinnyLink.attr('href', _url);
                    $skinnyLink.text(__host + _url);

                    console.log(data);

                    $loader.hide();
                    $goButton.show();
                    $copyLink.show();
                },
                error : function(xhr, textStatus, errorThrown) {
                    $skinnyLink.text(data.data.error);

                    console.log(textStatus);

                    $loader.hide();
                    $goButton.show();
                }
            });
        }
    };
}());