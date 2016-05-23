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
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="row">
                    <a href="{{url("html-loop")}}/1">ทั่วไป</a>|
                    <a href="{{url("html-loop")}}/2">ก.1</a>|
                    <a href="{{url("html-loop")}}/3">ก.2</a>|
                    <a href="{{url("html-loop")}}/4">ก.3</a>|
                    <a href="{{url("html-loop")}}/5">ข.1</a>|
                    <a href="{{url("html-loop")}}/6">ข.2</a>
                </div>
                <form action="test-post" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                @foreach($grouped as $question)
                    @if($question->input_type===\App\Question::TYPE_RADIO)
                    <div class="form-group text-left {{$question->class}}" data-parent-id="{{$question->parent_id}}" data-id="{{$question->id}}" id="q_{{$question->id}}">
                   <h3>{{$question->name}} | {{$question->subtext}}</h3>
                        @foreach($question as $option)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="q_{{$option->id}}[]" value="{{$option->option_id}}"
                                           class="styled" {{is_null($option->selected)?'':'checked'}}>
                                    {{$option->option_name}}
                                    @if($option->option_id===1)
                                        <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                                    @endif
                                </label>
                            </div>
                            {{--ถ้ามีลูและลูกขึ้นอยู่กับ option_id ปัจจุบัน--}}
                            @if(isset($question->children))
                                @foreach($question->children as $childQuestion)
                                    @if(!is_null($question->dependent_parent_option_id)&&(int)$question->dependent_parent_option_id===(int)$option->option_id)
                                        @include('partials.children',['question'=>$childQuestion,
                                            'parent_id'=>$question->id
                                            ,'parent_type'=>'radio'
                                            ,'parent_option_id'=>''
                                            ,'margin'=>0
                                            ])
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @if(isset($question->children))
                            @foreach($question->children as $childQuestion)
                            @include('partials.children',['question'=>$childQuestion,
                            'parent_id'=>$question->id
                           ,'parent_type'=>'radio'
                           ,'parent_option_id'=>''
                           ,'margin'=>0
                            ])
                            @endforeach
                        @endif
                    </div>
                    @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
                            <div class="form-group text-left {{$question->class}}"
                                 data-parent-id="{{$question->parent_id}}"
                                 data-id="{{$question->id}}"
                                 id="q_{{$question->id}}">
                                <h3>{{$question->name}}
                                    {{(!is_null($question->subtext)&&!empty($question->subtext))
                                    ?"({$question->subtext})":''}}</h3>
                                @foreach($question as $option)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="q_{{$option->id}}[]" value="{{$option->option_id}}"
                                                   class="styled" {{is_null($option->selected)?'':'checked'}}>
                                            {{$option->option_name}}
                                            @if($option->option_id===1)
                                                <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                                            @endif
                                        </label>
                                    </div>
                                    @if(isset($question->children))
                                        @foreach($question->children as $childQuestion)
                                        @include('partials.children',['question'=>$childQuestion,
                                        'parent_type'=>'checkbox',
                                        'parent_option_id'=>$option->option_id,
                                        'parent_id'=>$question->id,
                                        'margin'=>0
                                        ])
                                        @endforeach
                                    @endif
                                @endforeach
                            </div>
                    @elseif($question->input_type===\App\Question::TYPE_TITLE)
                        <div class="form-group text-left {{$question->class}}">
                            <h3>{{$question->name}}</h3>
                            @if(isset($question->children))
                                @foreach($question->children as $childQuestion)
                                    @include('partials.children',['question'=>$childQuestion,
                                    'parent_id'=>$question->id
                                   ,'parent_type'=>\App\Question::TYPE_TITLE
                                   ,'parent_option_id'=>''
                                   ,'margin'=>0
                                    ])
                                @endforeach
                            @endif
                        </div>
                    @elseif($question->input_type===\App\Question::TYPE_NUMBER)
                            <div class="form-group text-left {{$question->class}}">
                                <h3>{{$question->name}}</h3>
                                <input type="number" name="q_{{$question[0]->id}}" value="">
                                @if(isset($question->children))
                                    @foreach($question->children as $childQuestion)
                                        @include('partials.children',['question'=>$childQuestion,
                                        'parent_id'=>$question->id
                                       ,'parent_type'=>\App\Question::TYPE_NUMBER
                                       ,'parent_option_id'=>''
                                       ,'margin'=>0
                                        ])
                                    @endforeach
                                @endif
                            </div>
                    @elseif($question->input_type===\App\Question::TYPE_TEXT)
                            <div class="form-group text-left {{$question->class}}">
                                <h3>{{$question->name}}</h3>
                                <input type="text" name="q_{{$question[0]->id}}" value="">
                                @if(isset($question->children))
                                    @foreach($question->children as $childQuestion)
                                        @include('partials.children',['question'=>$childQuestion,
                                        'parent_id'=>$question->id
                                       ,'parent_type'=>\App\Question::TYPE_TEXT
                                       ,'parent_option_id'=>''
                                       ,'margin'=>0
                                        ])
                                    @endforeach
                                @endif
                            </div>
                    @endif

                @endforeach
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>

        <script type="text/javascript">
            var loopParent = [];
            var children = [];
            $(function () {

               $(".has-parent-no-dependent").each(function () {
                   var parentId = parseInt($(this).attr("data-parent-id"));
                   var currentId = $(this).attr("data-id");
                   var dependDentOptionId = $(this).attr("data-dependent-parent-option");
                   $('#q_'+parentId + ' input[type="radio"]').change(function () {
                       console.log('parent is changed: ', "#q_"+parentId+"_"+dependDentOptionId+"_"+currentId);
                       $("#q_"+parentId+"_"+dependDentOptionId+"_"+currentId).removeClass("hidden");
                   });
                   $('#q_'+parentId + ' input[type="checkbox"]').change(function () {
                       console.log('parent is changed: ', "#q_"+parentId+"_"+dependDentOptionId+"_"+currentId);
                       $("#q_"+parentId+"_"+dependDentOptionId+"_"+currentId).removeClass("hidden");
                   });
               });

                $(".has-parent").each(function () {
                    var parentId = parseInt($(this).attr("data-parent-id"));
                    var splitId = $(this).attr("id").replace("q_","").split("_");
                    var currentId = $(this).attr("id");
                    if(splitId.length==2){
                        console.log("parent: ", splitId[0], ", splitId: ", splitId[1]);
                        return;
                    }
                    console.log("parent: ", splitId[0],", whenparentselect: ",splitId[1] ,", splitId: ", splitId[2]);

                    $('#q_'+parentId + ' input[type="radio"]').change(function (e) {
                        console.log(e);
//                        $("#q_"+parentId+"_"+dependDentOptionId+"_"+currentId).removeClass("hidden");
                    });

                    if(loopParent.indexOf(splitId[0])==-1){
                        loopParent.push(splitId[0]);
                        children[splitId[0]] = [];
                    }

                    children[splitId[0]].push(currentId);

//                    $('#q_'+parentId + ' input[type="checkbox"]').change(function (e) {
////                        console.log('parent is changed: ', "#q_"+parentId+"_"+dependDentOptionId+"_"+currentId);
//                        console.log(e);
//                        if(e.target.value===splitId[1] && e.target.checked==true){
//                            $(this).removeClass("hidden");
//                        }
////                        $("#q_"+parentId+"_"+dependDentOptionId+"_"+currentId).removeClass("hidden");
//                    });
                });
                var tst = function(e){
                    console.log(e);
                    console.log(e.data);
                };
                for(var i=0;i<loopParent.length;i++){
                    var currentI = i;
                    console.log('#q_'+loopParent[i] + ' input[type="checkbox"]');
                    $('#q_'+loopParent[i] + ' input[type="checkbox"]').change({fixedId:currentI},function (e){
                        console.log('checkbox target,',e.target.value,' checked, ', e.target.checked);
                        if(e.target.checked){
                            $("div[id*=q_"+loopParent[e.data.fixedId]+"_"+e.target.value+"]").each(function () {
                                $(this).removeClass("hidden");
                            })
                        }
                    });
//                    $('#q_'+loopParent[i] + ' input[type="checkbox"]').change({test: 123},tst);

                    $('#q_'+loopParent[i] + ' input[type="radio"]').change(function ({fixedId:currentI},e){
                        console.log('radio target,',e.target.value,' checked, ', e.target.checked);
                        if(e.target.checked){
                            $("div[id*=q_"+loopParent[e.data.fixedId]+"_"+e.target.value+"]").each(function () {
                                $(this).removeClass("hidden");
                            })
                        }
                    });
                }

                $(".styled").uniform({
                    radioClass: 'choice'
                });
            });
        </script>
    </body>
</html>
