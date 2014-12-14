define(['jquery'], function ($) {
    jasmine.getFixtures().fixturesPath = "/base/tests/js/testsuites/fixtures/";
    "use strict";
    describe('Post Form Suite', function () {
        "use strict";
        function spyAjax(response) {
            var d = $.Deferred();
            d.resolve(response);
            spyOn($, 'ajax').and.returnValue(d.promise());
        }

        function arrayOfErrorMessagesResponse(data) {
            var singleErrorResponse = {
                error: true,
                message: {
                    post_tile : 'Title is required to fill in.',
                    post_slug_tag : 'Slug Tag is required to fill in.'
                }
            };
            spyAjax(singleErrorResponse);
            $.ajax('http://url').done(function (response) {
                data = response;
            });
            return data;
        }

        var responseData, form, alertDanger, alertDangerMsg;

        beforeEach(function () {
            loadFixtures('postFixture.html');
            responseData = arrayOfErrorMessagesResponse({});
            form = $('form');
        });

        afterEach(function () {
            responseData = form = alertDanger = alertDangerMsg = null;
        });

        it('Testing an ajax response with array of error messages', function () {
            expect(responseData).toBeDefined();
            expect(responseData.error).toBeDefined();
            expect(responseData.error).toBe(true);
            expect(responseData.message).toBeDefined();
            expect(responseData.message).toBeObject();
        });

    });
});