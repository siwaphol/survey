<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">--}}
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    {{--<link href="{{asset('assets/css/core.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css">--}}
    {{--<link href="{{asset('assets/css/colors.css')}}" rel="stylesheet" type="text/css">--}}
    <!-- /global stylesheets -->
    <!-- Angular Material style sheet -->
    {{--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.css">--}}
    <link href="{{asset('assets/css/angular-material.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">

    <style>
        legend {
            border-bottom: 1px solid #000000;
        }
    </style>
</head>
<body ng-app="testAngular">

@include('partials._navbar')

<div class="container" id="popupContainer">
    <div class="content" ng-controller="AppCtrl">

        <form ng-submit="submit()">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <input type="hidden" name="section" value="{{$section}}">
            <input type="hidden" name="sub_section" value="{{$sub_section}}">
            {{--for test--}}
            <input type="hidden" name="main_id" value="{{$main_id}}">

            @include('partials.children3',[
                'questions'=>$grouped,
                'parentQuestions'=>null
                ,'parent_parent_id'=>null
                ,'parent_parent_option_id'=>null
                ,'margin'=>0
                ,'parent_id'=>''
                ,'parent_option_id'=>''
            ])
            <input type="submit" value="Submit" id="submit">
            {{--<button class="btn btn-success" ng-click="submit()">Submit</button>--}}
        </form>
    </div>
</div>

{{--<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>--}}
<!-- Core JS files -->
{{--<script type="text/javascript" src="{{asset('assets/js/plugins/loaders/pace.min.js')}}"></script>--}}
<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>

{{--<script type="text/javascript" src="{{asset('assets/js/plugins/loaders/blockui.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{asset('assets/js/core/app.js')}}"></script>--}}
<!-- /core JS files -->

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
    angular.module('testAngular', ['ngMaterial'])
            .directive("initFromForm", function ($parse) {
                return {
                    link: function (scope, element, attrs) {
                        var attr = attrs.initFromForm || attrs.ngModel || element.attrs('name'),
                                val = attrs.value;
                        $parse(attr).assign(scope, val)
                    }
                };
            })
            .controller('AppCtrl', function ($scope, $http, $mdDialog) {
        $scope.question = {};

        @foreach($scopeParameters as $aScope)
        {!! $aScope !!}
        @endforeach

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
    });
</script>

</body>
</html>
