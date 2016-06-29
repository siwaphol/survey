@if(count($questions)>0)
    @foreach($questions as $key=>$question)
        <div class="form-group text-left {{$question->class}}"
             data-parent-id="{{$question->parent_id}}"
             data-dependent-parent-option="{{$question->dependent_parent_option_id}}"
             @if(strpos($question->class,'has-parent')>-1 && !is_null($parentQuestions) && $parentQuestions[$parent_id])
                     <?php $parentQ = $parentQuestions[$parent_id]; ?>
                 @if($parent_option_id==null)
                    {{--ขึ้นกับ option ทุกอันของแม่ loop ng-if สำหรับ or condition ng-if="model1||model2"--}}
                     <?php $allOptions = "";$first= true; ?>
                     @foreach($parentQ as $p_option)
                        <?php
                        if ($first){
                            $first = false;
                            $allOptions .= "q_".$p_option->id.'_'.$p_option->option_id;
                            continue;
                        }
                        $allOptions .= " || q_".$p_option->id.'_'.$p_option->option_id;
                        ?>
                     @endforeach
                     ng-if="{{$allOptions}}"
                 @else
                    ng-if="q_{{$parent_id}}_{{$parent_option_id}}"
                @endif
             @endif
            ng-init="applyUniformFunction()"
             style="margin-left: {{$margin}}px;">
        {{--ประเภท title--}}
        @if($question->input_type===\App\Question::TYPE_TITLE)
            <h3>{{$question->name}}</h3>
        {{--ประเภท textbox number --}}
        @elseif($question->input_type===\App\Question::TYPE_NUMBER)
                <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}}</label>
            <input type="number" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" value="{{$question[0]->answer_numeric}}">
        {{--ประเภท textbox text--}}
        @elseif($question->input_type===\App\Question::TYPE_TEXT)
                <label for="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}">{{$question->name}}</label>
                <input type="text" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$question->id}}" value="{{$question[0]->answer_text}}">
        {{--ประเภท radio--}}
        @elseif($question->input_type===\App\Question::TYPE_RADIO)
            <h4>{{$question->name}}</h4>
            @foreach($question as $option)
                <div class="radio">
                    <label>
                        <input type="radio"
                               name="q_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}"
                               ng-model="q_{{$option->id}}_{{$option->option_id}}"
                               value="{{$option->option_id}}"
                               class="styled" {{is_null($option->selected)?'':'checked'}}>
                        {{$option->option_name}}
                        @if($option->option_id===1)
                            <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                        @endif
                    </label>
                </div>
                {{--each option has children--}}
                @if(isset($option->children) && count($option->children)>0)
                    @include('partials.children2',[
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
        {{--ประเภท checkbox--}}
        @elseif($question->input_type===\App\Question::TYPE_CHECKBOX)
                <h4>{{$question->name}}</h4>
            @foreach($question as $option)
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               name="q_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}[]"
                               ng-model="q_{{$option->id}}_{{$option->option_id}}"
                               value="{{$option->option_id}}"
                               class="styled" {{is_null($option->selected)?'':'checked'}}>
                        {{$option->option_name}}
                        @if($option->option_id===1)
                            <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                        @endif
                    </label>
                </div>
                {{--each option has children--}}
                @if(isset($option->children) && count($option->children)>0)
                    @include('partials.children2',[
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
        @endif

        @if($question->input_type===\App\Question::TYPE_TITLE)
            <?php  $next_parent_id=""; ?>
        @else
            <?php  $next_parent_id=$question->id; ?>
        @endif
            
        {{--main question has children--}}
        @if(isset($question->children) && count($question->children)>0)
            @include('partials.children2',[
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