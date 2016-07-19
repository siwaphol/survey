myApp.factory('menu', [
    '$location',
    '$rootScope',
    '$http',
    '$window',
    'siteBaseUrl',
    function( $location, $rootScope, $http, $window, siteBaseUrl) {
        var self;
        var sections = [];

        $rootScope.$on('$locationChangeSuccess', onLocationChange);

        // $http.get("/docs.json");
        // $http({
        //     method: 'GET',
        //     url: siteBaseUrl + '/api/menus'
        // }).then(function successCallback(response) {
        //     sections = response.data;
        //     console.log(sections);
        // }, function errorCallback(response) {
        //     console.log(response);
        // });

        return self = {
            sections: sections,
            get: function () {
                return $http({
                    method: 'GET',
                    url: siteBaseUrl + '/api/menus'
                });
            },
            selectSection: function(section) {
                self.openedSection = section;
            },
            toggleSelectSection: function(section) {
                self.openedSection = (self.openedSection === section ? null : section);
            },
            selectPage: function(section) {
                self.currentSection = section;
            },
            isSectionSelected: function(section) {
                return self.openedSection === section;
            }
        };

        function onLocationChange() {
            var path = $location.path();
            var introLink = {
                name: "Introduction",
                url:  "/",
                type: "link"
            };

            if (path == '/') {
                self.selectSection(introLink);
                self.selectPage(introLink);
                return;
            }

            var matchPage = function(section, page) {
                if (path.indexOf(page.url) !== -1) {
                    self.selectSection(section);
                    self.selectPage(section, page);
                }
            };
        }
    }]);