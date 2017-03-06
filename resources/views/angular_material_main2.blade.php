<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Angular Material style sheet -->
    {{--<link rel="stylesheet"--}}
          {{--href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.css">--}}
    <link rel="stylesheet" href="{{asset('css/angular-material.min.css')}}">
    <link href="{{asset('assets/mfb/mfb.min.css')}}" rel="stylesheet"/>

    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css">--}}
    <link rel="stylesheet" href="{{asset('css/normalize.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{--<link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">

    <script type="text/javascript" src="{{asset('assets/mfb/lib/modernizr.touch.js')}}"></script>
    <link href="{{asset('css/custom2.css')}}" rel="stylesheet">
</head>
<body ng-app="Survey" ng-controller="SurveyCtrl" class="docs-body" layout="row" ng-cloak>
<md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2"
            md-component-id="left" hide-print
            md-is-locked-open="$mdMedia('gt-sm')">

    <header class="nav-header">
        <a ng-href="{{url('main')}}" class="docs-logo">
            {{--<img src="{{asset('assets/img/icons/angular-logo.svg')}}" alt=""/>--}}
            <h1 class="docs-logotype md-heading">ชุดที่ {{$main_id}}</h1>
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
                    <span class="md-breadcrumb-page">{{$sectionName}}</span>
                    @if($subSectionName)
                        <span class="md-breadcrumb-page" hide-xs> - {{$subSectionName}}</span>
                    @endif
                </h2>

                <span flex></span> <!-- use up the empty space -->

                <div class="md-toolbar-item docs-tools" layout="row">
                    <md-button class="md-icon-button"
                               ng-href="{{url('main')}}">
                        <md-tooltip md-autohide>เปลี่ยนชุดแบบสอบถาม</md-tooltip>
                        <i class="ion-gear-b" style="font-size: 2em;"></i>
                    </md-button>
                    <md-button class="md-icon-button"
                               ng-href="{{url('logout')}}">
                        <md-tooltip md-autohide>logout</md-tooltip>
                        <i class="ion-power" style="font-size: 1.8em;"></i>
                    </md-button>
                </div>
            </div>
        </div>
    </md-toolbar>

    <md-content md-scroll-y layout="column" flex>
        {{--Content goes here--}}
        <md-content layout-padding>
            <a id="top" style="padding: 0;"></a>
            <div>
                @if($main_id>2500)
                <div>
                    <md-button class="md-raised md-button md-ink-ripple" ng-click="submit()" ng-disabled="isDisabled">Submit</md-button>
                </div>
                @endif

                <form ng-submit="submit()" name="myForm">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="section" value="{{$section}}">
                    <input type="hidden" name="sub_section" value="{{$sub_section}}">
                    {{--for test--}}
                    <input type="hidden" name="main_id" value="{{$main_id}}">

                    @include('partials.children5',['questions'=>$new,'margin'=>0])

                    @if($main_id>2500)
                    <div>
                        <md-button class="md-raised md-ink-ripple" ng-click="submit()" ng-disabled="isDisabled">Submit</md-button>
                    </div>
                    @endif

                </form>
            </div>
            <a id="bottom" style="padding: 0;"></a>
        </md-content>
    </md-content>
</div>

@if($main_id>2500)
<nav mfb-menu position="br" effect="zoomin" menu-state="floatMenuState"
     active-icon="ion-close-round" resting-icon="ion-plus-round"
     ng-mouseenter="hovered()" ng-mouseleave="hovered()"
     toggling-method="click" menu-state="ctrl.menuState" main-action="ctrl.mainAction()">
    <button mfb-button icon="@{{button.icon}}" ng-click="loc(button.href)"
            label="@{{button.label}}" ng-repeat="button in buttons" ng-disabled="isDisabled"></button>
</nav>
@endif

<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
<!-- Angular Material requires Angular.js Libraries -->
{{--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>--}}
{{--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-animate.min.js"></script>--}}
{{--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-aria.min.js"></script>--}}
{{--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular-messages.min.js"></script>--}}
<script src="{{asset('js/angular1.5.3/angular.min.js')}}"></script>
<script src="{{asset('js/angular1.5.3/angular-animate.min.js')}}"></script>
<script src="{{asset('js/angular1.5.3/angular-aria.min.js')}}"></script>
<script src="{{asset('js/angular1.5.3/angular-messages.min.js')}}"></script>

<!-- Angular Material Library -->
{{--<script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.js"></script>--}}
<script src="{{asset('js/angular-material.min.js')}}"></script>
{{--<script src="{{asset('assets/js/angular-material.js')}}"></script>--}}
<!-- Your application bootstrap  -->
<script type="text/javascript">
    /**
     * You must include the dependency on 'ngMaterial'
     */
    var myApp = angular.module('Survey', ['ngMaterial', 'ng-mfb','ngMessages']);
    myApp.constant('submitUrl', '{{url('test-post-3')}}');
    myApp.constant('siteBaseUrl', '{{url('/')}}');
    myApp.constant('surveyUrl', '{{url('html-loop-2')}}');
    myApp.constant('section_name', '{{$section}}');
    myApp.constant('subsection_name', '{{$sub_section}}');

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
            'surveyUrl',
            'section_name',
            'subsection_name',
        'menu',
        function ($scope, $http, $mdDialog, $mdSidenav, $timeout, $window, $location, $anchorScroll,submitUrl, siteBaseUrl, surveyUrl, section_name,subsection_name,menu) {
            var self = this;

            $scope.question = {};
            $scope.openMenu = openMenu;
            $scope.isDisabled = false;
            $scope.floatMenuState = 'closed';

            var isEdited = {!! $edited?:0 !!};

            $scope.loc = loc;
//            $scope.openSurvey = openSurvey;
            $scope.isSectionSelected = isSectionSelected;
            $scope.showConfirm = showConfirm;

            @foreach($scope as $aScope)
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
                menu.sections = response.data;

//                menu.selectSection(menu.sections[0]);
//                console.log(menu.isSectionSelected(menu.sections[0]));

                menu.sections.forEach(function(section) {
                    if (section.children) {
                        // matches nested section toggles, such as API or Customization
                        section.children.forEach(function(childSection){
                            menu.matchPage(section, childSection);
                        });
                    }
                    else if (section.type === 'link') {
                        // matches top-level links, such as "Getting Started"
                        menu.matchPage(section, section);
                    }
                });

            });

            $scope.sample = function(model,a,b){
//                console.log('old:',a);
//                console.log('new:',b);
//                if(showConfirm()){
//                    return false;
//                }
            };

            $scope.submit = function () {
                $scope.isDisabled = true;
                $scope.floatMenuState = false;

                submitItems = {};
                angular.forEach($scope.question, function (value, key) {
                    if (value && value != '' && value != 0) {
                        this[key] = value;
                    }
                }, submitItems);

//                console.log(submitItems);
                if (angular.equals({}, submitItems)){
                    $scope.showError(null, 'คำเตือน', 'โปรดกรอกข้อมูลก่อนกดยืนยัน');
                    $scope.isDisabled = false;
                    return;
                }

                submitItems["_token"] = $('[name="_token"]').val();
                submitItems["section"] = $('[name="section"]').val();
                submitItems["sub_section"] = $('[name="sub_section"]').val();
                submitItems["main_id"] = $('[name="main_id"]').val();

                this.myForm.$setSubmitted();

                if (!this.myForm.$valid){
                    angular.element("[name='" + this.myForm.$name + "']").find('.ng-invalid:visible:first').focus();
                    $scope.showError(null, 'กรอกข้อมูลไม่ถูกต้อง','แก้ไขข้อมูลก่อนกดยืนยัน');
                    $scope.isDisabled = false;
                    return;
                }

                console.log(submitItems);
                if (isEdited){
                    showConfirmBeforeSubmit().then(function () {
                        $http({
                            method: 'POST',
                            url: postURL,
                            data: $.param(submitItems),
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                        }).success(function (data) {
                            $scope.showAlert();
                            menu.get().then(function(response){
                                menu.sections = response.data;
                            });
                            $scope.isDisabled = false;
                        }).error(function (data) {
                            console.log(data);
                            $scope.showError(null,'เกิดข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้');
                            $scope.isDisabled = false;
                        });
                    },function () {
                        console.log('cancel button');
                        $scope.isDisabled = false;
                    });
                    return;
                }
                $http({
                    method: 'POST',
                    url: postURL,
                    data: $.param(submitItems),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (data) {
                    $scope.showAlert();
                    menu.get().then(function(response){
                        menu.sections = response.data;
                    });
                    $scope.isDisabled = false;
                }).error(function (data) {
                    console.log(data);
                    $scope.showError(null,'เกิดข้อผิดพลาด','ไม่สามารถบันทึกข้อมูลได้ห');
                    $scope.isDisabled = false;
                });
            };

            function showConfirm(ev) {
                var confirm = $mdDialog.confirm()
                        .title('ยืนยันการแก้ไข')
                        .textContent('ค่าที่่ลบ หรือไม่ถูกเลือกจะไม่ถูกบันทึกลงฐานข้อมูล')
                        .targetEvent(ev)
                        .ok('ตกลง')
                        .cancel('ยกเลิก');
                $mdDialog.show(confirm).then(function () {
                    return true;
                },function () {
                    return false;
                });
            }

            function showConfirmBeforeSubmit(ev) {
                var confirm = $mdDialog.confirm()
                        .title('ยืนยันการแก้ไข')
                        .textContent('ค่าที่่ลบ หรือไม่ถูกเลือกจะไม่ถูกบันทึกลงฐานข้อมูล')
                        .targetEvent(ev)
                        .ok('ตกลง')
                        .cancel('ยกเลิก');
                return $mdDialog.show(confirm)
            }

            $scope.showAlert = function (ev) {
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

            $scope.showError = function (ev, title, textContent) {
                $mdDialog.show(
                        $mdDialog.alert()
                                .parent(angular.element(document.querySelector('#popupContainer')))
                                .clickOutsideToClose(true)
                                .title(title)
                                .textContent(textContent)
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

//            function openSurvey(sectionId, subSectionId) {
//                console.log('test');
//                if (subSectionId)
//                    $window.location.href = surveyUrl + '/' +sectionId + '/' + subSectionId;
//                else
//                    $window.location.href = surveyUrl + '/' +sectionId;
//            }

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