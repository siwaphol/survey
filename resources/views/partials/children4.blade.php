@if(count($questions)>0)
    @foreach($questions as $key=>$question)

        <?php
                $ngIf = "";
                $required = "";
            if (strpos($question->class,'has-parent')>-1 && !is_null($parentQuestions) && $parentQuestions[$parent_id]){
                //set required for field that has parent and type text and number
                if ($question->input_type===\App\Question::TYPE_NUMBER || $question->input_type===\App\Question::TYPE_TEXT){
                    $required = "required";
                }

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
                    $ngIf = 'ng-if='.$allOptions.'';
                }else{
                    if($parentQ->input_type===\App\Question::TYPE_RADIO)
                        $ngIf = 'ng-if=question.no_'. $parent_parent_id .'_'. $parent_parent_option_id .'_'.$parent_id.'=='.$parent_option_id.'';
                    elseif($parentQ->input_type===\App\Question::TYPE_CHECKBOX)
                        $ngIf = 'ng-if=question.no_'. $parent_parent_id .'_'. $parent_parent_option_id .'_'.$parent_id.'_'.$parent_option_id .'';
                }
            }
        ?>

            {{--ประเภท title--}}
            @if($question->input_type===\App\Question::TYPE_TITLE)
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {!! $ngIf !!} {!! $title_parent_ng_if !!}>
                    <h4>{{$question->name}}</h4>
                </md-input-container>
                {{--ประเภท textbox number --}}
            @elseif($question->input_type===\App\Question::TYPE_NUMBER)
                <div {{$ngIf}} {!! $title_parent_ng_if !!}>
                    <md-input-container class="md-block" style="margin-left: {{$margin}}px;">
                        <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}} @if(!empty($question[0]->unit_of_measure))({{$question[0]->unit_of_measure}})@endif</label>
                        <input type="number" value="{{$question[0]->answer_numeric}}" {{$required}}
                               ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" min="1" name="no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                        <div ng-messages="myForm.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}.$error" multiple>
                            <div ng-message="required">This is required.</div>
                            <div ng-message="min">Not less than 1</div>
                        </div>
                    </md-input-container>
                </div>
                {{--ประเภท textbox text--}}
            @elseif($question->input_type===\App\Question::TYPE_TEXT)
                <md-input-container class="md-block" style="margin-left: {{$margin}}px;" {!! $ngIf !!} {!! $title_parent_ng_if !!}>
                    <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}} @if(!empty($question[0]->unit_of_measure))({{$question[0]->unit_of_measure}})@endif </label>
                    <input type="text" value="{{$question[0]->answer_numeric}}" {{$required}}
                           ng-model="question.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" name="no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">
                    <div ng-messages="myForm.no_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}.$error">
                        <div ng-message="required">This is required.</div>
                    </div>
                </md-input-container>
                {{--ประเภท radio--}}
            @elseif($question->input_type===\App\Question::TYPE_RADIO)
                <md-content flex layout-padding style="margin-left: {{$margin}}px;" {!! $ngIf !!} {!! $title_parent_ng_if !!}>
                    <h4>{{$question->name}}</h4>
                    <?php $modelName = "no_" .$parent_id."_".$parent_option_id."_".$question->id; ?>
                    <md-radio-group ng-model="question.{{$modelName}}" >
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
                                    ,'title_parent_ng_if'=>''
                                ])
                            @endif
                        @endforeach
                    </md-radio-group>
                </md-content>
                {{--ประเภท checkbox--}}
            @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
                <md-content flex layout-padding style="margin-left: {{$margin}}px;" {!! $ngIf !!} {!! $title_parent_ng_if !!}>
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
                                    ,'title_parent_ng_if'=>''
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
                <?php
                    $title_parent_ng_if='';
                    if ($question->input_type===\App\Question::TYPE_TITLE){
                        $title_parent_ng_if = $ngIf;
                    }
                ?>
                @include('partials.children4',[
                    'questions'=>$question->children
                    ,'parentQuestions'=> $questions
                    ,'parent_parent_option_id'=>$parent_option_id
                    ,'parent_parent_id'=>$parent_id
                    ,'margin'=>($margin+20)
                    ,'parent_id'=>$next_parent_id
                    ,'parent_option_id'=>null
                    ,'title_parent_ng_if'=>$title_parent_ng_if
                ])
            @endif

        @if($margin==0)
            <legend></legend>
        @endif
    @endforeach
@endif