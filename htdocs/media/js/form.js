define(['tinymce',
    'util',
    'button',
    'dnd',
    'handler.base',
    'handler.xhr',
    'handler.form',
    'uploader.basic',
    'fileuploader',
    'jquery.plugin'
], function () {
    "use strict";
    $(function () {

        var manualuploader = $('#manual-fine-uploader').fineUploader({
            autoUpload: false,
            debug : true,
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            multiple: false,
            text: {
                uploadButton: '<a href="#" class="btn btn-block btn-default">Select Files</a>'
            },
            messages : {
                typeError: "{file} has an invalid extension. Valid extension(s): {extensions}."
            }
            ,
            showMessage : function(msg) {
                alert(msg);
            }

        });


        $('form').on('submit', function (e) {
            var _this = $(this);
            e.preventDefault();

            $.ajax({
                method: _this.attr('method'),
                url: _this.attr('action'),
                data: _this.serialize()
            }).success(function (response) {

                if (response.error) {
                    return;
                }

                if ($('#manual-fine-uploader').length) {
                    manualuploader.fineUploader("setEndpoint", _this.attr("data-endpoint"));
                    manualuploader.fineUploader("setParams", {
                        postId: response.id
                    });
                    manualuploader.fineUploader('uploadStoredFiles');
                    var timeOut = setInterval(function () {
                        var progress = manualuploader.fineUploader("getInProgress");
                        if (!progress) {
                            clearInterval(timeOut);
                            if (response.redirect != null) {
                                document.location.href = response.redirect;
                            } else if (response.self != null) {
                                document.location.reload();
                            }
                        }
                    }, 1000);
                } else {
                    if (response.redirect != null) {
                        document.location.href = response.redirect;
                    } else if (response.self != null) {
                        document.location.reload();
                    }
                }

            });


        });
    });

    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code",
            "media table contextmenu paste"
        ],
        toolbar: "styleselect | bold italic | alignleft aligncenter alignright | bullist numlist  link image",
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
});


