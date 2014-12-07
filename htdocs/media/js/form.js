$(function () {
    var manualuploader = $('#manual-fine-uploader').fineUploader({
        autoUpload: false,
        text: {
            uploadButton: '<a href="#" class="btn btn-block btn-default">Select Files</a>'
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

