<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Angular Material style sheet -->
    <link rel="stylesheet"
          href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.css">

    <link href="{{asset('assets/mfb/mfb.min.css')}}" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">

    <script type="text/javascript" src="{{asset('assets/mfb/lib/modernizr.touch.js')}}"></script>
    <link href="{{asset('css/custom2.css')}}" rel="stylesheet">
</head>
<body ng-app="Survey" ng-controller="SurveyCtrl" class="docs-body" layout="row" ng-cloak>
<md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2"
            md-component-id="left" hide-print
            md-is-locked-open="$mdMedia('gt-sm')">

    <header class="nav-header">
        <a ng-href="#index" class="docs-logo">
            {{--<img src="{{asset('assets/img/icons/angular-logo.svg')}}" alt=""/>--}}
            <h1 class="docs-logotype md-heading">ชุดที่ X</h1>
        </a>
    </header>

    <md-content flex role="navigation">
        <ul class="docs-menu">
            <li ng-repeat="section in menu.sections" class="parent-list-item @{{section.className || ''}}" ng-class="{'parentActive' : isSectionSelected(section)}">
            <h2 class="menu-heading md-subhead" ng-if="section.type === 'heading'" id="heading_@{{ section.name }}">
            @{{section.name}}
            </h2>
            <menu-link section="section" ng-if="section.type === 'link'"></menu-link>

            <menu-toggle section="section" ng-if="section.type === 'toggle'"></menu-toggle>
            </li>
        </ul>
    </md-content>
</md-sidenav>

<div layout="column" tabIndex="-1" role="main" flex>
    <md-toolbar class="md-whiteframe-glow-z1 site-content-toolbar">

        <div class="md-toolbar-tools docs-toolbar-tools" tabIndex="-1">
            <md-button class="md-icon-button" ng-click="openMenu()" hide-gt-sm aria-label="Toggle Menu">
                <md-icon md-svg-src="{{asset('assets/img/icons/ic_menu_24px.svg')}}"></md-icon>
            </md-button>
            <div layout="row" flex class="fill-height">
                <h2 class="md-toolbar-item md-breadcrumb md-headline">
            <span ng-if="menu.currentPage.name">
              <span hide-sm hide-md>@{{menu.currentPage.name}}</span>
              <span class="docs-menu-separator-icon" hide-sm hide-md style="transform: translate3d(0, 1px, 0)">
                <span class="md-visually-hidden">-</span>
                  {{--<md-icon--}}
                  {{--aria-hidden="true"--}}
                  {{--md-svg-src="img/icons/ic_chevron_right_24px.svg"--}}
                  {{--style="margin-top: -2px"></md-icon>--}}
              </span>
            </span>
                    <span class="md-breadcrumb-page">Sub Section</span>
                </h2>

                <span flex></span> <!-- use up the empty space -->

                {{--<div class="md-toolbar-item docs-tools" layout="row">--}}
                    {{--<md-button class="md-icon-button"--}}
                               {{--aria-label="Install with Bower"--}}
                               {{--ng-if="!currentComponent.docs.length && !currentComponent.isService"--}}
                               {{--target="_blank"--}}
                               {{--ng-href="https://github.com/angular/bower-material">--}}
                        {{--<md-tooltip md-autohide>Install with Bower</md-tooltip>--}}
                        {{--<md-icon md-svg-src="{{asset('assets/img/icons/bower-logo.svg')}}"></md-icon>--}}
                    {{--</md-button>--}}
                    {{--<md-button class="md-icon-button"--}}
                               {{--aria-label="Install with NPM"--}}
                               {{--ng-if="!currentComponent.docs.length && !currentComponent.isService"--}}
                               {{--target="_blank"--}}
                               {{--ng-href="https://www.npmjs.com/package/angular-material">--}}
                        {{--<md-tooltip md-autohide>Install with NPM</md-tooltip>--}}
                        {{--<md-icon md-svg-src="{{asset('assets/img/icons/npm-logo.svg')}}"--}}
                                 {{--style="transform: scale(1.3)"></md-icon>--}}
                    {{--</md-button>--}}
                    {{--<md-button class="md-icon-button"--}}
                               {{--aria-label="View Source on Github"--}}
                               {{--ng-if="!currentComponent.docs.length && !currentComponent.isService"--}}
                               {{--target="_blank"--}}
                               {{--ng-href="#1234">--}}
                        {{--<md-tooltip md-autohide>View Source on Github</md-tooltip>--}}
                        {{--<md-icon md-svg-src="{{asset('assets/img/icons/github.svg')}}"--}}
                                 {{--style="color: rgba(255,255,255,0.97);"></md-icon>--}}
                    {{--</md-button>--}}
                {{--</div>--}}
            </div>
        </div>
    </md-toolbar>

    <md-content md-scroll-y layout="column" flex>
        <div ng-view layout-padding flex="noshrink" class="docs-ng-view"></div>

        {{--Content goes here--}}
        <md-content layout-padding>
            <a id="top" style="padding: 0;"></a>
            <div>
                <md-content>
                    <md-button type="submit" class="md-primary" ng-click="submit()">Submit</md-button>
                </md-content>

                <form ng-submit="submit()" name="myForm">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="section" value="{{$section}}">
                    <input type="hidden" name="sub_section" value="{{$sub_section}}">
                    {{--for test--}}
                    <input type="hidden" name="main_id" value="{{$main_id}}">

                    @include('partials.children4',[
                        'questions'=>$grouped,
                        'parentQuestions'=>null
                        ,'parent_parent_id'=>null
                        ,'parent_parent_option_id'=>null
                        ,'margin'=>0
                        ,'parent_id'=>''
                        ,'parent_option_id'=>''
                    ])
                    
                    <md-content>
                        <md-button type="submit" class="md-primary" ng-click="submit()" ng-disabled="myForm.$valid && myForm.$submitted">Submit</md-button>
                    </md-content>
                </form>
            </div>
            <a id="bottom" style="padding: 0;"></a>
        </md-content>

        {{--<div layout="row" flex="noshrink" layout-align="center center">--}}
            {{--<div id="license-footer" flex>--}}
                {{--Powered by Google &copy;2014&#8211;@{{thisYear}}.--}}
                {{--Code licensed under the <a ng-href="./license" class="md-accent">MIT License</a>.--}}
                {{--Documentation licensed under--}}
                {{--<a href="http://creativecommons.org/licenses/by/4.0/" target="_blank"--}}
                   {{--class="md-default-theme md-accent">CC BY 4.0</a>.--}}
            {{--</div>--}}
        {{--</div>--}}
    </md-content>
</div>

<nav mfb-menu position="br" effect="zoomin"
     active-icon="ion-close-round" resting-icon="ion-plus-round"
     ng-mouseenter="hovered()" ng-mouseleave="hovered()"
     toggling-method="click" menu-state="ctrl.menuState" main-action="ctrl.mainAction()">
    <button mfb-button icon="@{{button.icon}}" ng-click="loc(button.href)"
            label="@{{button.label}}" ng-repeat="button in buttons"></button>
</nav>

<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
<!-- Angular Material requires Angular.js Libraries -->
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-aria.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-messages.min.js"></script>

<!-- Angular Material Library -->
<script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.js"></script>

<!-- Your application bootstrap  -->
<script type="text/javascript">
    /**
     * You must include the dependency on 'ngMaterial'
     */
    var myApp = angular.module('Survey', ['ngMaterial', 'ng-mfb','ngMessages']);
    myApp.constant('submitUrl', '{{url('test-post-2')}}');
    myApp.constant('siteBaseUrl', '{{url('/')}}');

    myApp.factory('question', function () {

    });
    myApp.directive("initFromForm", function ($parse) {
        return {
            link: function (scope, element, attrs) {
                var attr = attrs.initFromForm || attrs.ngModel || element.attrs('name'),
                        val = attrs.value;
                $parse(attr).assign(scope, val)
            }
        };
    });
    myApp.filter('humanizeDoc', function () {
        return function (doc) {
            if (!doc) return;
            if (doc.type === 'directive') {
                return doc.name.replace(/([A-Z])/g, function ($1) {
                    return '-' + $1.toLowerCase();
                });
            }
            return doc.label || doc.name;
        };
    });
    myApp.filter('nospace', function () {
        return function (value) {
            return (!value) ? '' : value.replace(/ /g, '');
        };
    });
</script>
<script src="{{asset('js/directives.js')}}"></script>
<script src="{{asset('js/factory.js')}}"></script>
<script>
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
        function ($scope, $http, $mdDialog, $mdSidenav, $timeout, $window, $location, $anchorScroll,submitUrl, siteBaseUrl, menu) {
            var self = this;

            $scope.question = {};
            $scope.openMenu = openMenu;

            $scope.loc = loc;
            $scope.isSectionSelected = isSectionSelected;

            @foreach($scopeParameters as $aScope)
            {!! $aScope !!}
            @endforeach

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
                    console.log('key: ',key,', value:',value);
                    if (value && value != '' && value != 0) {
                        this[key] = value;
                    }
                }, submitItems);

                if (submitItems.length<=0){
                    $scope.showError();
                    return;
                }

                submitItems["_token"] = $('[name="_token"]').val();
                submitItems["section"] = $('[name="section"]').val();
                submitItems["sub_section"] = $('[name="sub_section"]').val();
                submitItems["main_id"] = $('[name="main_id"]').val();

                console.log(submitItems);
                this.myForm.$setSubmitted();

                if (!this.myForm.$valid){
                    $scope.showError();
                    return;
                }

                $http({
                    method: 'POST',
                    url: postURL,
                    data: $.param(submitItems),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
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

            $scope.showError = function (ev) {
                $mdDialog.show(
                        $mdDialog.alert()
                                .parent(angular.element(document.querySelector('#popupContainer')))
                                .clickOutsideToClose(true)
                                .title('โปรดตรวจสอบข้อมูลก่อนบันทึก')
                                .textContent('โปรดตรวจสอบข้อมูลก่อนบันทึก')
                                .ok('ตกลง')
                                .targetEvent(ev)
                );
            };

            $scope.buttons = [{
                label: 'Submit',
                icon: 'ion-android-done',
                href: 'submit'
            }, {
                label: 'Go to Bottom',
                icon: 'ion-arrow-down-a',
                href: '#bottom'
            }, {
                label: 'Go to Top',
                icon: 'ion-arrow-up-a',
                href: '#top'
            }];

            function loc(href) {
                if (href==='submit')
                    $scope.submit();
                else if(href.indexOf("#") > -1){
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
</script>
{{--<script src="{{asset('js/controller.js')}}"></script>--}}

<!-- ng-material-floating-button -->
<script src="{{asset('assets/mfb/mfb-directive.js')}}"></script>

</body>
</html>