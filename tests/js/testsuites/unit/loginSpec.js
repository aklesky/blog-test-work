define(['jquery'], function ($) {
    jasmine.getFixtures().fixturesPath = "/base/tests/js/testsuites/fixtures/";
    "use strict";
    describe('Login Form Suite', function () {
        "use strict";
        function spyAjax(response) {
            var d = $.Deferred();
            d.resolve(response);
            spyOn($, 'ajax').and.returnValue(d.promise());
        }

        function singleErrorMessageResponse(data) {
            var singleErrorResponse = {
                error: true,
                message: 'The Username or password is incorrect.'
            };
            spyAjax(singleErrorResponse);
            var _this = $('form');
            $.ajax('http://url').done(function (response) {
                data = response;
                if (typeof response.error != 'undefined') {
                    var danger = _this.find('.alert.alert-danger');
                    danger.show();
                    danger.find('.alert-danger-msg').text(response.message);
                }
            });
            return data;
        }

        var responseData, form, alertDanger, alertDangerMsg;

        beforeEach(function () {
            loadFixtures('loginFixture.html');
            responseData = singleErrorMessageResponse({});
            form = $('form');
            alertDanger = form.find('.alert.alert-danger');
            alertDangerMsg = alertDanger.find('.alert-danger-msg');
        });

        afterEach(function () {
            responseData = form = alertDanger = alertDangerMsg = null;
        });

        it('Testing an ajax call with single error message response', function () {
            expect(responseData).toBeDefined();
            expect(responseData.error).toBeDefined();
            expect(responseData.error).toBe(true);
            expect(responseData.message).toBeDefined();
            expect(responseData.message).not.toBeNull();
        });

        it('Testing than alert message is visible', function () {
            expect(alertDanger).not.toBeHidden();
            expect(alertDanger).toBeMatchedBy('.hidden');
        });

        it('Testing an ajax call response message and view it in the form',
            function () {
                var currentMessage = alertDangerMsg.text();
                expect(responseData).toBeDefined();
                expect(currentMessage).toEqual('The Username or password is incorrect.');
            });

    });
});