<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
    <!-- Angular Material style sheet -->
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.0-rc2/angular-material.min.css">

    <style>
        legend {
            border-bottom: 1px solid #000000;
        }
    </style>
</head>
<body ng-app="testAngular">
<div class="container">
    <div class="content" ng-controller="AppCtrl">
        <div class="row">
            @foreach(\App\Question::$sections as $key=>$value)
                @if((int)$key===0)
                    @continue
                @endif
                <a href="{{url("html-loop-2")}}/{{$key}}">{{$value}}</a>|
            @endforeach
        </div>
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

<script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>
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
    angular.module('testAngular', ['ngMaterial']).
    controller('AppCtrl', function ($scope, $http) {
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
            }).error(function (data) {
                console.log(data);
            });
        }
    });
</script>
</body>
</html>