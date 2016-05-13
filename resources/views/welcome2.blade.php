<!DOCTYPE html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        {{--<link href="assets/css/core.css" rel="stylesheet" type="text/css">--}}
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        {{--<link href="assets/css/colors.css" rel="stylesheet" type="text/css">--}}
        <!-- /global stylesheets -->

        <!-- Core JS files -->
        <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->
        <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
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
                <form action="test-post" method="post">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                @foreach($grouped as $question)
                    <div class="form-group text-left {{is_null($question[0]->parent_id)?'':'hidden has-parent'}}"
                            data-parent-id="{{$question[0]->parent_id}}"
                            data-id="{{$question[0]->id}}"
                    id="q_{{$question[0]->id}}">
                   <h3>{{$question[0]->name}}
                            {{(!is_null($question[0]->subtext)&&!empty($question[0]->subtext))
                            ?"({$question[0]->subtext})":''}}</h3>
                        @foreach($question as $option)
                            <div class="radio">
                                <label>
                                    <input type="radio" name="q_{{$option->id}}[]" value="{{$option->option_id}}" class="styled">
                                    {{$option->option_name}}
                                    @if($option->option_id===1)
                                        <input type="text">
                                    @endif
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>

        <script type="text/javascript">
            $(function () {
               $(".has-parent").each(function () {
                   var parentId = parseInt($(this).attr("data-parent-id"));
                   var currentId = $(this).attr("data-id");
                   $('#q_'+parentId + ' input[type="radio"]').change(function () {
                       $("#q_"+currentId).removeClass("hidden");
                   });
               });

                $(".styled").uniform({
                    radioClass: 'choice'
                });
            });
        </script>
    </body>
</html>
