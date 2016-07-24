<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" type="text/css">
        {{--<link href="{{asset('assets/css/core.css')}}" rel="stylesheet" type="text/css">--}}
        <link href="{{asset('assets/css/components.css')}}" rel="stylesheet" type="text/css">
        {{--<link href="{{asset('assets/css/colors.css')}}" rel="stylesheet" type="text/css">--}}
        <!-- /global stylesheets -->

        <!-- Core JS files -->
        <script type="text/javascript" src="{{asset('assets/js/plugins/loaders/pace.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/core/libraries/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/core/libraries/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/plugins/loaders/blockui.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/js/core/libraries/angular.min.js')}}"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->
        <script type="text/javascript" src="{{asset('assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
        {{--<script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>--}}
        {{--<script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>--}}

        {{--<script type="text/javascript" src="assets/js/core/main_custom.js"></script>--}}
        {{--<script type="text/javascript" src="assets/js/pages/form_checkboxes_radios.js"></script>--}}

        <style>
            legend {
                border-bottom: 1px solid #000000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content" ng-app>
                <div class="row">
                    @foreach(\App\Question::$sections as $key=>$value)
                        @if((int)$key===0)
                            @continue
                        @endif
                    <a href="{{url("html-loop-2")}}/{{$key}}">{{$value}}</a>|
                    @endforeach
                </div>
                <form action="{{url('test-post')}}" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="section" value="{{$section}}">
                    <input type="hidden" name="sub_section" value="{{$sub_section}}">
                    {{--for test--}}
                    <input type="hidden" name="main_id" value="{{$main_id}}">

                    @include('partials.children2',[
                        'questions'=>$grouped,
                        'parentQuestions'=>null
                        ,'parent_parent_id'=>null
                        ,'parent_parent_option_id'=>null
                        ,'margin'=>0
                        ,'parent_id'=>''
                        ,'parent_option_id'=>''
                    ]);
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>

        <script type="text/javascript">
            var loopParent = [];
            var children = [];
//            $(function () {
//                $(".styled").uniform({
//                    radioClass: 'choice'
//                });
//            });

            function applyUniformFunction() {
                $(".styled").uniform({
                    radioClass: 'choice'
                });
            }
        </script>
    </body>
</html>
