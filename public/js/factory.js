myApp.factory('menu', [
    '$location',
    '$rootScope',
    '$http',
    '$window',
    'siteBaseUrl',
    'section_name',
    'subsection_name',
    function( $location, $rootScope, $http, $window, siteBaseUrl, section_name, subsection_name) {
        var self;
        var sections = [];
        var path = $location.path();

        $rootScope.$on('$locationChangeSuccess', onLocationChange);

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
            selectPage: function(section, page) {
                self.currentSection = section;
                self.currentPage = page;
            },
            isSectionSelected: function(section) {
                return self.openedSection === section;
            },
            isPageSelected: function(page) {
                return self.currentPage === page;
            },
            matchPage: function (section, page) {
                if (section.id === parseInt(section_name)) {
                    if (section===page){
                        self.selectSection(section);
                        self.selectPage(section, page);
                        return;
                    }

                    console.log(subsection_name);
                    if (section!==page && subsection_name && page.id===parseInt(subsection_name)){
                        self.selectSection(section);
                        self.selectPage(section, page);
                        return;
                    }
                }
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
        }
    }]);