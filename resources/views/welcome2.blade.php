<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <!-- dependencies (jquery, handlebars and bootstrap) -->
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js"></script>
        <link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

        <!-- alpaca -->
        <link type="text/css" href="//code.cloudcms.com/alpaca/1.5.17/bootstrap/alpaca.min.css" rel="stylesheet"/>
        <script type="text/javascript" src="//code.cloudcms.com/alpaca/1.5.17/bootstrap/alpaca.min.js"></script>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
                background-color: gainsboro;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
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
                                    <input type="radio" name="q_{{$option->id}}[]" value="{{$option->option_id}}">
                                    {{$option->option_name}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
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
            });
        </script>
    </body>
</html>
