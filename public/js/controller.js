myApp.controller('SurveyCtrl', [
    '$scope',
    '$http',
    '$mdDialog',
    '$mdSidenav',
    '$timeout',
    '$window',
    '$location',
    '$anchorScroll',
    'submitUrl',
    'siteBaseUrl',
    'menu',
    function ($scope, $http, $mdDialog, $mdSidenav, $timeout, $window, $location, $anchorScroll, submitUrl, siteBaseUrl, menu) {
        var self = this;

        $scope.question = {};
        $scope.openMenu = openMenu;

        $scope.loc = loc;
        $scope.isSectionSelected = isSectionSelected;

        // Methods used by menuLink and menuToggle directives
        this.isOpen = isOpen;
        this.isSelected = isSelected;
        this.toggleOpen = toggleOpen;
        this.autoFocusContent = false;

        $scope.menu = menu;

        var submitItems = [];
        $scope.formData = {};
        var postURL = submitUrl;

        menu.get().then(function(response){
            // console.log(response);
            menu.sections = response.data;
            // $scope.$apply();
        });

        $scope.submit = function () {
            submitItems = {};
            angular.forEach($scope.question, function (value, key) {
                if (value && value != '' && value != 0) {
                    this[key] = value;
                }
            }, submitItems);

            submitItems["_token"] = $('[name="_token"]').val();
            submitItems["section"] = $('[name="section"]').val();
            submitItems["sub_section"] = $('[name="sub_section"]').val();
            submitItems["main_id"] = $('[name="main_id"]').val();

            console.log(submitItems);

            $http({
                method: 'POST',
                url: postURL,
                data: $.param(submitItems),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (data) {
                console.log(data);
                $scope.showAlert();
            }).error(function (data) {
                console.log(data);
            });
        };

        $scope.showAlert = function (ev) {
            // Appending dialog to document.body to cover sidenav in docs app
            // Modal dialogs should fully cover application
            // to prevent interaction outside of dialog
            $mdDialog.show(
                $mdDialog.alert()
                    .parent(angular.element(document.querySelector('#popupContainer')))
                    .clickOutsideToClose(true)
                    .title('บันทึกข้อมูลสำเร็จ')
                    .textContent('ข้อมูลถูกบันทึกลงฐานข้อมูลเรียบร้อย')
                    .ok('ตกลง')
                    .targetEvent(ev)
            );
        };

        $scope.buttons = [{
            label: 'Submit',
            icon: 'ion-android-done',
            href: '#done'
        }, {
            label: 'Go to Bottom',
            icon: 'ion-arrow-down-a',
            href: '#bottom'
        }, {
            label: 'Go to Top',
            icon: 'ion-arrow-up-a',
            href: '#top'
        }];

        // $scope.goto = function (aHashTag) {
        //     aHashTag = aHashTag.replace("#","");
        //     $location.hash(aHashTag);
        //
        //     $anchorScroll();
        // };

        function loc(href) {
            if(href.indexOf("#") > -1){
                $location.hash(href.replace("#",""));
                $anchorScroll();
            }
            else{
                $window.location.href = href;
            }
        }

        function openMenu() {
            $timeout(function () {
                $mdSidenav('left').open();
            });
        }

        function isSectionSelected(section) {
            var selected = false;
            var openedSection = menu.openedSection;
            if (openedSection === section) {
                selected = true;
            }
            else if (section.children) {
                section.children.forEach(function (childSection) {
                    if (childSection === openedSection) {
                        selected = true;
                    }
                });
            }
            return selected;
        }

        function path() {
            return $location.path();
        }

        function isSelected(page) {
            return menu.isPageSelected(page);
        }

        function isOpen(section) {
            return menu.isSectionSelected(section);
        }

        function toggleOpen(section) {
            menu.toggleSelectSection(section);
        }
    }]);
// myApp.controller('NavCtrl', [
//     '$scope',
//     '$timeout',
//     '$mdSidenav',
//     '$log',
//     function ($scope, $timeout, $mdSidenav, $log) {
//         $scope.toggleLeft = buildToggler('left');
//         $scope.isOpenLeft = function () {
//             return $mdSidenav('left').isOpen();
//         };
//
//         function buildToggler(navID) {
//             return function () {
//                 // Component lookup should always be available since we are not using `ng-if`
//                 $mdSidenav(navID)
//                     .toggle()
//                     .then(function () {
//                         $log.debug("toggle " + navID + " is done");
//                     });
//             }
//         }
//     }]);
// myApp.controller('LeftCtrl', [
//     '$scope',
//     '$timeout',
//     '$mdSidenav',
//     '$log',
//     function ($scope, $timeout, $mdSidenav, $log) {
//         $scope.close = function () {
//             // Component lookup should always be available since we are not using `ng-if`
//             $mdSidenav('left').close()
//                 .then(function () {
//                     $log.debug("close LEFT is done");
//                 });
//         };
//     }]);