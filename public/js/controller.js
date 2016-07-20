// myApp.controller('SurveyCtrl', [
//     '$scope',
//     '$http',
//     '$mdDialog',
//     '$mdSidenav',
//     '$timeout',
//     '$window',
//     '$location',
//     '$anchorScroll',
//     'submitUrl',
//     'siteBaseUrl',
//     'menu',
//     function ($scope, $http, $mdDialog, $mdSidenav, $timeout, $window, $location, $anchorScroll, submitUrl, siteBaseUrl, menu) {
//         var self = this;
//
//         $scope.question = {};
//         $scope.openMenu = openMenu;
//
//         $scope.loc = loc;
//         $scope.isSectionSelected = isSectionSelected;
//
//         // Methods used by menuLink and menuToggle directives
//         this.isOpen = isOpen;
//         this.isSelected = isSelected;
//         this.toggleOpen = toggleOpen;
//         this.autoFocusContent = false;
//
//         $scope.menu = menu;
//
//         var submitItems = [];
//         $scope.formData = {};
//         var postURL = submitUrl;
//
//         menu.get().then(function(response){
//             // console.log(response);
//             menu.sections = response.data;
//             // $scope.$apply();
//         });
//
//         $scope.submit = function () {
//             submitItems = {};
//             angular.forEach($scope.question, function (value, key) {
//                 console.log('key: ',key,', value:',value);
//                 if (value && value != '' && value != 0) {
//                     this[key] = value;
//                 }
//             }, submitItems);
//
//             if (!submitItems.length){
//                 $scope.showError();
//                 return;
//             }
//
//             submitItems["_token"] = $('[name="_token"]').val();
//             submitItems["section"] = $('[name="section"]').val();
//             submitItems["sub_section"] = $('[name="sub_section"]').val();
//             submitItems["main_id"] = $('[name="main_id"]').val();
//
//             console.log(submitItems);
//
//             $http({
//                 method: 'POST',
//                 url: postURL,
//                 data: $.param(submitItems),
//                 headers: {'Content-Type': 'application/x-www-form-urlencoded'}
//             }).success(function (data) {
//                 $scope.showAlert();
//             }).error(function (data) {
//                 console.log(data);
//             });
//         };
//
//         $scope.showAlert = function (ev) {
//             // Appending dialog to document.body to cover sidenav in docs app
//             // Modal dialogs should fully cover application
//             // to prevent interaction outside of dialog
//             $mdDialog.show(
//                 $mdDialog.alert()
//                     .parent(angular.element(document.querySelector('#popupContainer')))
//                     .clickOutsideToClose(true)
//                     .title('บันทึกข้อมูลสำเร็จ')
//                     .textContent('ข้อมูลถูกบันทึกลงฐานข้อมูลเรียบร้อย')
//                     .ok('ตกลง')
//                     .targetEvent(ev)
//             );
//         };
//
//         $scope.showError = function (ev) {
//             $mdDialog.show(
//                 $mdDialog.alert()
//                     .parent(angular.element(document.querySelector('#popupContainer')))
//                     .clickOutsideToClose(true)
//                     .title('โปรดตรวจสอบข้อมูลก่อนบันทึก')
//                     .textContent('โปรดตรวจสอบข้อมูลก่อนบันทึก')
//                     .ok('ตกลง')
//                     .targetEvent(ev)
//             );
//         };
//
//         $scope.buttons = [{
//             label: 'Submit',
//             icon: 'ion-android-done',
//             href: 'submit'
//         }, {
//             label: 'Go to Bottom',
//             icon: 'ion-arrow-down-a',
//             href: '#bottom'
//         }, {
//             label: 'Go to Top',
//             icon: 'ion-arrow-up-a',
//             href: '#top'
//         }];
//
//         function loc(href) {
//             if (href==='submit')
//                 $scope.submit();
//             else if(href.indexOf("#") > -1){
//                 $location.hash(href.replace("#",""));
//                 $anchorScroll();
//             }
//             else{
//                 $window.location.href = href;
//             }
//         }
//
//         function openMenu() {
//             $timeout(function () {
//                 $mdSidenav('left').open();
//             });
//         }
//
//         function isSectionSelected(section) {
//             var selected = false;
//             var openedSection = menu.openedSection;
//             if (openedSection === section) {
//                 selected = true;
//             }
//             else if (section.children) {
//                 section.children.forEach(function (childSection) {
//                     if (childSection === openedSection) {
//                         selected = true;
//                     }
//                 });
//             }
//             return selected;
//         }
//
//         function path() {
//             return $location.path();
//         }
//
//         function isSelected(page) {
//             return menu.isPageSelected(page);
//         }
//
//         function isOpen(section) {
//             return menu.isSectionSelected(section);
//         }
//
//         function toggleOpen(section) {
//             menu.toggleSelectSection(section);
//         }
//     }]);