define(['jquery'], function ($) {

    describe("Form suite", function () {
        it('jQuery must be defined', function () {
            expect($).toBeDefined();
        });

        it('Testing jQuery Ajax Call', function () {

            var d = $.Deferred();
            var simulateResponse = {
                Test: 'Message',
                Second: 'Response'
            };

            d.resolve(simulateResponse);

            spyOn($, 'ajax').and.returnValue(d.promise());

            var data = {};

            $.ajax('http://').done(function (response) {
                data = response;
            });

            expect(data.Test).toBe('Message');
            expect(data.Second).toBe('Response');
        })
    });
});