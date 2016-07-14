<html lang="en" >
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Angular Material style sheet -->
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.css">
</head>
<body ng-app="Survey" ng-controller="SurveyCtrl" class="docs-body" layout="row" ng-cloak>

<md-sidenav class="site-sidenav md-sidenav-left md-whiteframe-z2"
            md-component-id="left" hide-print
            md-is-locked-open="$mdMedia('gt-sm')">

    <header class="nav-header">
        <a ng-href="/" class="docs-logo">
            <img src="{{asset('assets/img/icons/angular-logo.svg')}}" alt="" />
            <h1 class="docs-logotype md-heading">Logo here</h1>
        </a>
    </header>

    <md-content flex role="navigation">
        <ul class="docs-menu">
            {{--<li ng-repeat="section in menu.sections" class="parent-list-item @{{section.className || ''}}" ng-class="{'parentActive' : isSectionSelected(section)}">--}}
                {{--<h2 class="menu-heading md-subhead" ng-if="section.type === 'heading'" id="heading_@{{ section.name | nospace }}">--}}
                    {{--@{{section.name}}--}}
                {{--</h2>--}}
                {{--<menu-link section="section" ng-if="section.type === 'link' && !section.hidden"></menu-link>--}}

                {{--<menu-toggle section="section" ng-if="section.type === 'toggle' && !section.hidden"></menu-toggle>--}}

                {{--<ul ng-if="section.children" class="menu-nested-list">--}}
                    {{--<li ng-repeat="child in section.children" ng-class="{'childActive' : isSectionSelected(child)}">--}}
                        {{--<menu-link section="child" ng-if="child.type === 'link'"></menu-link>--}}

                        {{--<menu-toggle section="child" ng-if="child.type === 'toggle'"></menu-toggle>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            <li><h2>link here</h2></li>
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
            <span ng-if="menu.currentPage.name !== menu.currentSection.name">
              <span hide-sm hide-md>@{{menu.currentSection.name}}</span>
              <span class="docs-menu-separator-icon" hide-sm hide-md style="transform: translate3d(0, 1px, 0)">
                <span class="md-visually-hidden">-</span>
                <md-icon
                        aria-hidden="true"
                        md-svg-src="img/icons/ic_chevron_right_24px.svg"
                        style="margin-top: -2px"></md-icon>
              </span>
            </span>
                    <span class="md-breadcrumb-page">@{{menu.currentPage | humanizeDoc}}</span>
                </h2>

                <span flex></span> <!-- use up the empty space -->

                <div class="md-toolbar-item docs-tools" layout="row">
                    <md-button class="md-icon-button"
                               aria-label="Install with Bower"
                               ng-if="!currentComponent.docs.length && !currentComponent.isService"
                               target="_blank"
                               ng-href="https://github.com/angular/bower-material">
                        <md-tooltip md-autohide>Install with Bower</md-tooltip>
                        <md-icon md-svg-src="{{asset('assets/img/icons/bower-logo.svg')}}"></md-icon>
                    </md-button>
                    <md-button class="md-icon-button"
                               aria-label="Install with NPM"
                               ng-if="!currentComponent.docs.length && !currentComponent.isService"
                               target="_blank"
                               ng-href="https://www.npmjs.com/package/angular-material">
                        <md-tooltip md-autohide>Install with NPM</md-tooltip>
                        <md-icon md-svg-src="{{asset('assets/img/icons/npm-logo.svg')}}" style="transform: scale(1.3)"></md-icon>
                    </md-button>
                    <md-button class="md-icon-button"
                               aria-label="View Source on Github"
                               ng-if="!currentComponent.docs.length && !currentComponent.isService"
                               target="_blank"
                               ng-href="#1234">
                        <md-tooltip md-autohide>View Source on Github</md-tooltip>
                        <md-icon md-svg-src="{{asset('assets/img/icons/github.svg')}}" style="color: rgba(255,255,255,0.97);"></md-icon>
                    </md-button>
                </div>
            </div>
        </div>

    </md-toolbar>

    <md-content md-scroll-y layout="column" flex>
        <div ng-view layout-padding flex="noshrink" class="docs-ng-view"></div>

        <div layout="row" flex="noshrink" layout-align="center center">
            <div id="license-footer" flex>
                Powered by Google &copy;2014&#8211;@{{thisYear}}.
                Code licensed under the <a ng-href="./license"  class="md-accent">MIT License</a>.
                Documentation licensed under
                <a href="http://creativecommons.org/licenses/by/4.0/"  target="_blank" class="md-default-theme md-accent">CC BY 4.0</a>.
            </div>
        </div>
    </md-content>

</div>

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
    angular.module('Survey', ['ngMaterial'])
            .directive("initFromForm", function ($parse) {
                return {
                    link: function (scope, element, attrs) {
                        var attr = attrs.initFromForm || attrs.ngModel || element.attrs('name'),
                                val = attrs.value;
                        $parse(attr).assign(scope, val)
                    }
                };
            })
            .filter('humanizeDoc', function() {
                return function(doc) {
                    if (!doc) return;
                    if (doc.type === 'directive') {
                        return doc.name.replace(/([A-Z])/g, function($1) {
                            return '-'+$1.toLowerCase();
                        });
                    }
                    return doc.label || doc.name;
                };
            })
            .controller('SurveyCtrl', function ($scope, $http, $mdDialog, $mdSidenav, $timeout) {
                $scope.question = {};
                $scope.openMenu = openMenu;

                        {{--@foreach($scopeParameters as $aScope)--}}
                        {{--{!! $aScope !!}--}}
                        {{--@endforeach--}}

                var submitItems = [];
                $scope.formData = {};
                var postURL = '{{url('test-post-2')}}';

                $scope.submit = function () {
                    submitItems = {};
                    angular.forEach($scope.question,function (value, key) {
                        if(value && value!='' && value!=0){
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
                        headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).success(function (data) {
                        console.log(data);
                        $scope.showAlert();
                    }).error(function (data) {
                        console.log(data);
                    });
                };

                $scope.showAlert = function(ev) {
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

                function openMenu() {
                    $timeout(function() { $mdSidenav('left').open(); });
                }
            })
            .controller('NavCtrl', function ($scope, $timeout, $mdSidenav, $log) {
                $scope.toggleLeft = buildToggler('left');
                $scope.isOpenLeft = function(){
                    return $mdSidenav('left').isOpen();
                };

                function buildToggler(navID) {
                    return function() {
                        // Component lookup should always be available since we are not using `ng-if`
                        $mdSidenav(navID)
                                .toggle()
                                .then(function () {
                                    $log.debug("toggle " + navID + " is done");
                                });
                    }
                }
            })
            .controller('LeftCtrl', function ($scope, $timeout, $mdSidenav, $log) {
                $scope.close = function () {
                    // Component lookup should always be available since we are not using `ng-if`
                    $mdSidenav('left').close()
                            .then(function () {
                                $log.debug("close LEFT is done");
                            });
                };
            });
</script>

</body>
</html>