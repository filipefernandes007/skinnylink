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
    var __getSkinnyLink;
    var __this = this;

    return {
        init: function(obj) {
            __host          = obj.host;
            __resultDivId   = obj.resultDivId;
            __buttonLoadId  = obj.buttonLoadId;
            __goButtonId    = obj.goButtonId;
            __copyLinkId    = obj.copyLinkId;
            __redirectUrl   = obj.redirectUrl;
            __getSkinnyLink = obj.getSkinnyLink;
        },
        new: function(url) {
            if (url === '') {
                return false;
            }

            var $goButton   = $(__goButtonId);
            var $skinnyLink = $(__resultDivId);
            var $loader     = $(__buttonLoadId);
            var $copyLink   = $(__copyLinkId);

            $skinnyLink.text('');

            $goButton.hide();
            $copyLink.hide();
            $('.result').hide();
            $loader.show();

            $.ajax({
                url:        '/api',
                type:       'POST',
                dataType:   'json',
                async:      true,
                data: {'url' : url},
                success: function(data, status) {
                    if (!!data.id) {
                        var _url = __redirectUrl.replace('!123abc', data.skinnyUrl);

                        $skinnyLink.attr('href', _url);
                        $skinnyLink.text(__host + _url);

                        $(__getSkinnyLink).attr('href', __host + '/' + data.id);

                        $('.result').show();
                        $copyLink.show();
                        alertify.success('SkinnyLink created!');
                    } else if (!!data.error) {
                        alertify.error(data.error);
                    }

                    console.log(data);

                    $loader.hide();
                    $goButton.show();
                },
                error : function(xhr, textStatus, errorThrown) {
                    alertify.error(textStatus);

                    console.log(textStatus);

                    $loader.hide();
                    $goButton.show();
                }
            });
        }
    };
}());