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

        {{--<script type="text/javascript" src="assets/js/core/app.js"></script>--}}
        {{--<script type="text/javascript" src="assets/js/pages/form_checkboxes_radios.js"></script>--}}

        <style>
            /*html, body {*/
                /*height: 100%;*/
            /*}*/

            /*body {*/
                /*margin: 0;*/
                /*padding: 0;*/
                /*width: 100%;*/
                /*display: table;*/
                /*font-weight: 100;*/
                /*!*font-family: 'Lato';*!*/
                /*background-color: gainsboro;*/
            /*}*/

            /*.container {*/
                /*text-align: center;*/
                /*display: table-cell;*/
                /*vertical-align: middle;*/
            /*}*/

            /*.content {*/
                /*text-align: center;*/
                /*display: inline-block;*/
            /*}*/

            /*.title {*/
                /*font-size: 96px;*/
            /*}*/
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
            $(function () {

//                $(".has-parent").each(function () {
//                    var parentId = parseInt($(this).attr("data-parent-id"));
//                    var splitId = $(this).attr("id").replace("q_","").split("_");
//                    var currentId = $(this).attr("id");
//                    if(splitId.length==2){
//                        console.log("parent: ", splitId[0], ", splitId: ", splitId[1]);
//                        return;
//                    }
//                    console.log("parent: ", splitId[0],", whenparentselect: ",splitId[1] ,", splitId: ", splitId[2]);
//
//                    $('#q_'+parentId + ' input[type="radio"],[type="checkbox"]').change(function (e) {
//                        console.log(e);
////                        $("#q_"+parentId+"_"+dependDentOptionId+"_"+currentId).removeClass("hidden");
//                    });
//
//                    if(loopParent.indexOf(splitId[0])==-1){
//                        loopParent.push(splitId[0]);
//                        children[splitId[0]] = [];
//                    }
//
//                    children[splitId[0]].push(currentId);
//                });
//                var tst = function(e){
//                    console.log(e);
//                    console.log(e.data);
//                };
//                for(var i=0;i<loopParent.length;i++){
//                    var currentI = i;
//                    console.log('#q_'+loopParent[i] + ' input[type="checkbox"]');
//                    $('#q_'+loopParent[i] + ' input[type="checkbox"]').change({fixedId:currentI},function (e){
//                        console.log('checkbox target,',e.target.value,' checked, ', e.target.checked);
//                        if(e.target.checked){
//                            $("div[id*=q_"+loopParent[e.data.fixedId]+"_"+e.target.value+"]").each(function () {
//                                $(this).removeClass("hidden");
//                            })
//                        }
//                    });
////                    $('#q_'+loopParent[i] + ' input[type="checkbox"]').change({test: 123},tst);
//
//                    $('#q_'+loopParent[i] + ' input[type="radio"]').change(function ({fixedId:currentI},e){
//                        console.log('radio target,',e.target.value,' checked, ', e.target.checked);
//                        if(e.target.checked){
//                            $("div[id*=q_"+loopParent[e.data.fixedId]+"_"+e.target.value+"]").each(function () {
//                                $(this).removeClass("hidden");
//                            })
//                        }
//                    });
//                }

                $(".styled").uniform({
                    radioClass: 'choice'
                });
            });
        </script>
    </body>
</html>
