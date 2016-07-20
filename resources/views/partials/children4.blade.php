@if(count($questions)>0)
    @foreach($questions as $key=>$question)

        <?php
                $ngIf = "";
            if (strpos($question->class,'has-parent')>-1 && !is_null($parentQuestions) && $parentQuestions[$parent_id]){
                $parentQ = $parentQuestions[$parent_id];
                if (is_null($parent_option_id)){
                    $allOptions = "";$first = true;
                    foreach ($parentQ as $p_option){
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
                    }
                    $ngIf = 'ng-if="'.$allOptions.'"';
                }else{
                    if($parentQ->input_type===\App\Question::TYPE_RADIO)
                        $ngIf = 'ng-if="question.no_'. $parent_parent_id .'_'. $parent_parent_option_id .'_'.$parent_id.'=='.$parent_option_id.'"';
                    elseif($parentQ->input_type===\App\Question::TYPE_CHECKBOX)
                        $ngIf = 'ng-if="question.no_'. $parent_parent_id .'_'. $parent_parent_option_id .'_'.$parent_id.'_'.$parent_option_id .'"';
                }
            }
        ?>

            {{--ประเภท title--}}
            @if($question->input_type===\App\Question::TYPE_TITLE)
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {{$ngIf}}>
                    <label for="">{{$question->name}}</label>
                    </br>
                </md-input-container>
                {{--ประเภท textbox number --}}
            @elseif($question->input_type===\App\Question::TYPE_NUMBER)
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {{$ngIf}}>
                    <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}} ({{$question[0]->unit_of_measure}})</label>
                    <input type="number" value="{{$question[0]->answer_numeric}}"
                           ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" min="1" name="no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                    <div ng-messages="myForm.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}.$error" multiple>
                        <div ng-message="required">This is required.</div>
                        <div ng-message="min">Not less than 0</div>
                    </div>
                </md-input-container>
                {{--ประเภท textbox text--}}
            @elseif($question->input_type===\App\Question::TYPE_TEXT)
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {{$ngIf}}>
                    <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}} ({{$question[0]->unit_of_measure}}) </label>
                    <input type="text" value="{{$question[0]->answer_numeric}}"
                           ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" name="no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                    <div ng-messages="myForm.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}.$error">
                        <div ng-message="required">This is required.</div>
                    </div>
                </md-input-container>
                {{--ประเภท radio--}}
            @elseif($question->input_type===\App\Question::TYPE_RADIO)
                <md-content flex layout-padding style="margin-left: {{$margin}}px;" {{$ngIf}}>
                    <h4>{{$question->name}}</h4>
                    <md-radio-group ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                        @foreach($question as $option)
                            <md-radio-button value="{{$option->option_id}}">{{$option->option_name}}</md-radio-button>
                            @if($option->option_id===1)
                                <input type="text" ng-model="question.other_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" value="{{$option->other_text}}" init-from-form>
                            @endif
                            {{--each option has children--}}
                            @if(isset($option->children) && count($option->children)>0)
                                @include('partials.children4',[
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
                </md-content>
                {{--ประเภท checkbox--}}
            @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
                <md-content flex layout-padding style="margin-left: {{$margin}}px;" {{$ngIf}}>
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
                                @include('partials.children4',[
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
                </md-content>
            @endif

            @if($question->input_type===\App\Question::TYPE_TITLE)
                <?php  $next_parent_id = ""; ?>
            @else
                <?php  $next_parent_id = $question->id; ?>
            @endif

            {{--main question has children--}}
            @if(isset($question->children) && count($question->children)>0)
                @include('partials.children4',[
                    'questions'=>$question->children
                    ,'parentQuestions'=> $questions
                    ,'parent_parent_option_id'=>$parent_option_id
                    ,'parent_parent_id'=>$parent_id
                    ,'margin'=>($margin+20)
                    ,'parent_id'=>$next_parent_id
                    ,'parent_option_id'=>null
                ])
            @endif

        @if($margin==0)
            <legend></legend>
        @endif
    @endforeach
@endif