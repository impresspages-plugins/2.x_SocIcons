/*jslint browser: true
nomen: true*/
/*global ip, $, jQuery*/
(function ($) {

    "use strict";

    var methods = {
        init : function () {
            return this.each(function () {

                $(this).ipModuleInlineManagementControls({
                    'Manage' : function () {
                        methods.openEditPopup();
                    }
                });

            });
        },

        openEditPopup: function () {

            var $this = this,
                data = {},
                urlParts = window.location.href.split('#'),
                postUrl = urlParts[0];

            data.g = 'navigation';
            data.m = 'social_icons';
            data.aa = 'showDialog';
            data.securityToken = ip.securityToken;


            $.ajax({
                type : 'POST',
                url : postUrl,
                data : data,
                context : $this,
                dataType : 'json',
                success: function (json) {
    // Data processing code
                    $this.popup(json);
                }

            });
        },

        popup: function (json) {

            var $this = this,
                $popup = $('.ipModuleInlineManagementPopup.ipmSocIcons'),
                $iconSize = json.size,
                $isWidget = json.is_widget;


            $popup.dialog({width: 450, height : 'auto', modal: true, title: "Social icons", minWidth: 450, maxWidth: 450});
            $popup.html(json.popUpHtml);

            if (!$($popup).length){
                $('body').append('<div class="ipModuleInlineManagementPopup ipmSocIcons" ></div>');
            }

            $('.ipsSocialIconsEdit').sortable();
            $('.ipsSocialIconsEdit').disableSelection();


            $("#ipsSocialIconsEdit li img").click(function () {

                $(this).closest('li').attr('data-enabled',  $(this).closest('li').attr('data-enabled') === '1'
                    ? '0'
                    : '1');

            });

            $("#ipsSocialIconsEdit li #ipaFieldOnOff").click(function () {

                $(this).closest('li').attr('data-enabled',  $(this).closest('li').attr('data-enabled') === '1'
                    ? '0'
                    : '1');

            });

            $("#ipsSocialIconsEdit li .ipaFieldRemove").click(function () {
                var $this = $(this);
                methods._remove($this);
            });

            // add jQuery UI slider

            function refreshSwatch() {
                var sliderData =  $('.ipsSocialIconsSlider').slider("option", "value");
                $('#ipsSocialIconsEdit li .ipSocIconsFile img').height(sliderData);
            }

            $(function () {
                $('.ipsSocialIconsSlider').slider({
                    orientation: "horizontal",
                    min: 15,
                    max: 40,
                    value:  $iconSize,
                    slide: refreshSwatch,
                    change: refreshSwatch
                });
            });


            $('.ipModuleInlineManagementPopup').find('.ipaUpload').bind('click', jQuery.proxy(methods._upload, $this));

            $('.ipModuleInlineManagementPopup').find('.ipaConfirm').bind('click', jQuery.proxy(methods._confirm, $this));
            $('.ipModuleInlineManagementPopup').find('.ipaCancel').bind('click', jQuery.proxy(methods._cancel, $this));

            $.proxy(methods.refresh, $this)();
        },

        _upload: function ($iconData) {


            var repository = new ipRepository({preview: 'thumbnails', filter: 'image'});
                repository.bind('ipRepository.filesSelected', $.proxy(methods._fileUploaded, $iconData));


        },


         _fileUploaded: function (event, files) {
            var $this = $(this),
                data = {},
                urlParts = window.location.href.split('#'),
                postUrl = urlParts[0];

             /* save to db */
             data.g = 'navigation';
             data.m = 'social_icons';
             data.aa = 'uploadIcons';
             data.securityToken = ip.securityToken;


             data.size = $('.ipsSocialIconsSlider').slider("option", "value");
             data.newIcons = [];

            for (var index in files) {

              data.newIcons.push(files[index].file);

            }

             $.ajax({
                 type : 'POST',
                 url : postUrl,
                 data : data,
                 context : $this,
                 success : methods._refreshAfterUpload,
                 dataType : 'json'
             });
        },

        _remove: function (icon) {

            var $this = $(this),
                data = {},
                urlParts = window.location.href.split('#'),
                postUrl = urlParts[0];

            data.g = 'navigation';
            data.m = 'social_icons';
            data.aa = 'removeIcon';
            data.securityToken = ip.securityToken;


            data.id = icon.data('id');

            $.ajax({
                type : 'POST',
                url : postUrl,
                data : data,
                context : $this,
                success : methods._removeFromPopup,
                error: methods.refresh,
                dataType : 'json'
            });




        },

        _removeFromPopup: function (response) {

            if (response.status == "success") {

                var  id = response.id,
                     iconToRemove = $('.ipModuleInlineManagementPopup.ipmSocIcons li[data-id='+id+']');

                iconToRemove.remove();

            }

        },

        refresh: function () {

            var $this = this,
                data = {},
                urlParts = window.location.href.split('#'),
                postUrl = urlParts[0];

            data.g = 'navigation';
            data.m = 'social_icons';
            data.aa = 'getIconData';
            data.securityToken = ip.securityToken;
//            data.key = $this.data('ipInlineManagementText').key;
//            data.defaultValue = $this.data('ipInlineManagementText').defaultValue;


            $.ajax({
                type : 'POST',
                url : postUrl,
                data : data,
                context : $this,
                success : methods._refreshResponse,
                dataType : 'json'
            });

        },

        _refreshResponse: function (response) {

        },

        _refreshAfterUpload: function (response) {
            $('.ipModuleInlineManagementPopup').dialog('destroy');
            methods.openEditPopup();
        },

        _confirm : function (event) {
            event.preventDefault();
            var $this = $(this),
                data = {},
                icon = {},
                urlParts = window.location.href.split('#'),
                postUrl = urlParts[0];

            data.g = 'navigation';
            data.m = 'social_icons';
            data.aa = 'saveIcons';
            data.securityToken = ip.securityToken;

            data.size =  $('.ipsSocialIconsSlider').slider("option", "value");
            data.icons = [];

            $('.ipsSocialIconsEdit').find('li').each(function () {

                icon = {
                    'filename' : $(this).data('filename'),
                    'url' : $(this).find('.ipsSocialIconsLink').val(),
                    'enabled':  $(this).data('enabled')
                };

                data.icons.push(icon);

            });

            //SAVE
            $.ajax({
                type : 'POST',
                url : postUrl,
                data : data,
                context : $this,
                success : methods._confirmResponse,
                dataType : 'json'
            });
        },

        _confirmResponse : function (answer) {
            var $this = this;
            if (answer && answer.status == 'success') {
                if (answer.stringHtml) {
                    var $newElement = $(answer.stringHtml);
                    $('.ipModuleInlineManagement.ipmSocIcons').replaceWith($newElement);
                    $newElement.ipModuleInlineSocIcon();
                }

                $('.ipModuleInlineManagementPopup').remove();
            }
        },


        _cancel : function (event) {
            event.preventDefault();
            $('.ipModuleInlineManagementPopup').remove();
        }

    };

    $.fn.ipModuleInlineSocIcon = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ipModuleInlineSocIcon');
        }
    };

}(jQuery));

$(document).ready(function () {
    'use strict';
    $('.ipModuleInlineManagement.ipmSocIcons').ipModuleInlineSocIcon();
});
