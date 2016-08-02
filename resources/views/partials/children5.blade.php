@if(count($questions)>0)
    @foreach($questions as $key=>$question)

        <?php
        $ngIf = isset($question->ngIf)?'ng-if="'.$question->ngIf .'"':"";
        $required = "";
        if (strpos($question->class,'has-parent')>-1){
            //set required for field that has parent and type text and number
            if ($question->input_type===\App\Question::TYPE_NUMBER || $question->input_type===\App\Question::TYPE_TEXT){
                $required = "required";
            }
        }
        ?>

        {{--ประเภท title--}}
        @if($question->input_type===\App\Question::TYPE_TITLE)
            <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {!! $ngIf !!}>
                <h4>{{$question->name}}</h4>
            </md-input-container>
            {{--ประเภท textbox number --}}
        @elseif($question->input_type===\App\Question::TYPE_NUMBER)
            <div {!! $ngIf !!}>
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;">
                    <label for="{{$question->unique_key}}">{{$question->name}} @if(!empty($question[0]->unit_of_measure))({{$question[0]->unit_of_measure}})@endif</label>
                    <input type="number" {{$required}}
                    ng-model="{{$question->unique_key}}" min="0.1" name="{{str_replace("question.","",$question->unique_key)}}">
                    <div ng-messages="myForm.{{str_replace("question.","",$question->unique_key)}}.$error" multiple>
                        <div ng-message="required">This is required.</div>
                        <div ng-message="min">More than 0</div>
                    </div>
                </md-input-container>
            </div>
            {{--ประเภท textbox text--}}
        @elseif($question->input_type===\App\Question::TYPE_TEXT)
            <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {!! $ngIf !!}>
                <label for="{{$question->unique_key}}">{{$question->name}} @if(!empty($question[0]->unit_of_measure))({{$question[0]->unit_of_measure}})@endif </label>
                <input type="text" {{$required}}
                ng-model="{{$question->unique_key}}" name="{{str_replace("question.","",$question->unique_key)}}">
                <div ng-messages="myForm.{{str_replace("question.","",$question->unique_key)}}.$error">
                    <div ng-message="required">This is required.</div>
                </div>
            </md-input-container>
            {{--ประเภท radio--}}
        @elseif($question->input_type===\App\Question::TYPE_RADIO)
            <md-content flex layout-padding style="margin-left: {{$margin}}px;" {!! $ngIf !!}>
                <h4>{{$question->name}}</h4>
                <md-radio-group ng-model="{{$question->unique_key}}" >
                    @foreach($question as $option)
                        <md-radio-button value="{{$option->option_id}}">{{$option->option_name}}</md-radio-button>
                        @if($option->option_id===1)
                            <input type="text" ng-model="{{str_replace("no","other",$question->unique_key)}}">
                        @endif
                        {{--each option has children--}}
                        @if(isset($option->children) && count($option->children)>0)
                            @include('partials.children5',['questions'=>$option->children,'margin'=>($margin+20)])
                        @endif
                    @endforeach
                </md-radio-group>
            </md-content>
            {{--ประเภท checkbox--}}
        @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
            <md-content flex layout-padding style="margin-left: {{$margin}}px;" {!! $ngIf !!}>
                <h4>{{$question->name}}</h4>
                @foreach($question as $option)
                    <div>
                        <md-checkbox
                                ng-model="{{$option->unique_key}}">{{$option->option_name}}</md-checkbox>
                        @if($option->option_id===1)
                            <input type="text" ng-model="{{str_replace("no","other",$option->unique_key)}}">
                        @endif
                        {{--each option has children--}}
                        @if(isset($option->children) && count($option->children)>0)
                            @include('partials.children5',['questions'=>$option->children,'margin'=>($margin+20)])
                        @endif
                    </div>
                @endforeach
            </md-content>
        @endif

        {{--main question has children--}}
        @if(isset($question->children) && count($question->children)>0)
            @include('partials.children5',['questions'=>$question->children,'margin'=>($margin+20)])
        @endif

        @if($margin==0)
            <legend></legend>
        @endif
    @endforeach
@endif