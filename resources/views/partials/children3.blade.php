@if(count($questions)>0)
    @foreach($questions as $key=>$question)
        <div class="form-group text-left"
             @if(strpos($question->class,'has-parent')>-1 && !is_null($parentQuestions) && $parentQuestions[$parent_id])
             <?php $parentQ = $parentQuestions[$parent_id]; ?>
             @if($parent_option_id==null)
             {{--ขึ้นกับ option ทุกอันของแม่ loop ng-if สำหรับ or condition ng-if="model1||model2"--}}
             <?php $allOptions = "";$first = true; ?>
             @foreach($parentQ as $p_option)
             <?php
             if ($first) {
                 $first = false;
                 if ($parentQ->input_type === \App\Question::TYPE_CHECKBOX)
                     $allOptions .= "question.no_" . $parent_parent_id . "_" . $parent_parent_option_id . "_" . $parent_id . "_" . $p_option->option_id;
                 else
                     $allOptions .= "question.no_" . $parent_parent_id . "_" . $parent_parent_option_id . "_" . $parent_id . " == ".$p_option->option_id;

                 continue;
             }
             if ($parentQ->input_type === \App\Question::TYPE_CHECKBOX)
                 $allOptions .= " || question.no_" . $parent_parent_id . "_" . $parent_parent_option_id . "_" . $parent_id . "_" . $p_option->option_id;
             else
                 $allOptions .= " || question.no_" . $parent_parent_id . "_" . $parent_parent_option_id . "_" . $parent_id . " == ".$p_option->option_id;
             ?>
             @endforeach
             ng-if="{{$allOptions}}"
             @else
             @if($parentQ->input_type===\App\Question::TYPE_RADIO)
             ng-if="question.no_{{$parent_parent_id}}_{{$parent_parent_option_id}}_{{$parent_id}}=={{$parent_option_id}}"
             @elseif($parentQ->input_type===\App\Question::TYPE_CHECKBOX)
             ng-if="question.no_{{$parent_parent_id}}_{{$parent_parent_option_id}}_{{$parent_id}}_{{$parent_option_id}}"
             @endif
             @endif
             @endif
             style="margin-left: {{$margin}}px;">
            {{--ประเภท title--}}
            @if($question->input_type===\App\Question::TYPE_TITLE)
                <h3>{{$question->name}}</h3>
                {{--ประเภท textbox number --}}
            @elseif($question->input_type===\App\Question::TYPE_NUMBER)
                <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}}</label>
                <input type="number" value="{{$question[0]->answer_numeric}}"
                       ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                <label for="">{{$question[0]->unit_of_measure}}</label>
                {{--ประเภท textbox text--}}
            @elseif($question->input_type===\App\Question::TYPE_TEXT)
                <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}}</label>
                <input type="text" value="{{$question[0]->answer_text}}"
                       ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                <label for="">{{$question[0]->unit_of_measure}}</label>
                {{--ประเภท radio--}}
            @elseif($question->input_type===\App\Question::TYPE_RADIO)
                <h4>{{$question->name}}</h4>
                <md-radio-group ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                    @foreach($question as $option)
                        <md-radio-button value="{{$option->option_id}}">{{$option->option_name}}</md-radio-button>
                        @if($option->option_id===1)
                            <input type="text" ng-model="question.other_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" value="{{$option->other_text}}" init-from-form>
                        @endif
                        {{--each option has children--}}
                        @if(isset($option->children) && count($option->children)>0)
                            @include('partials.children3',[
                                'questions'=>$option->children
                                ,'parentQuestions'=> $questions
                                ,'parent_parent_option_id'=> $parent_option_id
                                ,'parent_parent_id'=>$parent_id
                                ,'margin'=>($margin+20)
                                ,'parent_id'=>$option->id
                                ,'parent_option_id'=>$option->option_id
                            ])
                        @endif
                    @endforeach
                </md-radio-group>
                {{--ประเภท checkbox--}}
            @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
                <h4>{{$question->name}}</h4>
                @foreach($question as $option)
                    <div>
                        <md-checkbox
                                ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}_{{$option->option_id}}">{{$option->option_name}}</md-checkbox>
                        @if($option->option_id===1)
                            <input type="text" ng-model="question.other_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}_{{$option->option_id}}" value="{{$option->other_text}}" init-from-form>
                        @endif
                        {{--each option has children--}}
                        @if(isset($option->children) && count($option->children)>0)
                            @include('partials.children3',[
                                'questions'=>$option->children
                                ,'parentQuestions'=> $questions
                                ,'parent_parent_option_id'=> $parent_option_id
                                ,'parent_parent_id'=>$parent_id
                                ,'margin'=>($margin+20)
                                ,'parent_id'=>$option->id
                                ,'parent_option_id'=>$option->option_id
                            ])
                        @endif
                    </div>
                @endforeach
            @endif

            @if($question->input_type===\App\Question::TYPE_TITLE)
                <?php  $next_parent_id = ""; ?>
            @else
                <?php  $next_parent_id = $question->id; ?>
            @endif

            {{--main question has children--}}
            @if(isset($question->children) && count($question->children)>0)
                @include('partials.children3',[
                    'questions'=>$question->children
                    ,'parentQuestions'=> $questions
                    ,'parent_parent_option_id'=>$parent_option_id
                    ,'parent_parent_id'=>$parent_id
                    ,'margin'=>($margin+20)
                    ,'parent_id'=>$next_parent_id
                    ,'parent_option_id'=>null
                ])
            @endif
        </div>

        @if($margin==0)
            <legend></legend>
        @endif
    @endforeach
@endif