define(['jquery'],function ($) {
    jasmine.getFixtures().fixturesPath = "/base/tests/js/testsuites/fixtures/";
    "use strict";
    describe('Form Suite', function () {
        "use strict";
        beforeEach(function () {
            loadFixtures('formFixture.html');
        });

        it('Testing a form with the ajax submit', function () {
            expect($('p.divider').html()).toEqual('Hello World');
        });
    });
});