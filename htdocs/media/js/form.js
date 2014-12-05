$(function () {
    $('form').on('submit', function (e) {
        var _this = $(this);
        e.preventDefault();
        $.ajax({
            method: _this.attr('method'),
            url: _this.attr('action'),
            data: _this.serialize()
        }).success(function (response) {
            console.log(response);
        });

    });
});