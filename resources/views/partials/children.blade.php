@if(count($question)>0)
    <?php $margin+=40;?>
    @foreach($question as $aQuestion)
    @if($aQuestion->input_type===\App\Question::TYPE_RADIO
    && (is_null($aQuestion->dependent_parent_option_id) || in_array((string)$parent_option_id,explode(",",$aQuestion->dependent_parent_option_id)))
    )
        <div class="form-group text-left {{$aQuestion->class}}"
             data-parent-id="{{$aQuestion->parent_id}}"
             {{--data-parent-input-type="{{$aQuestion->parent_input_type}}"--}}
             data-dependent-parent-option="{{$aQuestion->dependent_parent_option_id}}"
             data-id="{{$aQuestion->id}}" id="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}"
             style="margin-left: {{$margin}}px;">

            <h3>{{$aQuestion->name}} | {{$aQuestion->subtext}}</h3>
            @foreach($aQuestion as $option)
                <div class="radio">
                    <label>
                        <input type="radio" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}[]" value="{{$option->option_id}}"
                               class="styled" {{is_null($option->selected)?'':'checked'}}>
                        {{$option->option_name}}
                        @if($option->option_id===1)
                            <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                        @endif
                    </label>
                </div>
            @endforeach

            @if(isset($aQuestion->children))
                @foreach($aQuestion->children as $childQuestion)
                    @include('partials.children',['question'=>$childQuestion,
                    'parent_id'=>$aQuestion->id
                   ,'parent_type'=>'radio'
                   ,'parent_option_id'=>''
                   ,'margin'=>$margin
                    ])
                @endforeach
            @endif
        </div>
    @elseif($aQuestion->input_type===\App\Question::TYPE_CHECKBOX
    && (is_null($aQuestion->dependent_parent_option_id) || in_array((string)$parent_option_id,explode(",",$aQuestion->dependent_parent_option_id)))
    )
        <div class="form-group text-left {{$aQuestion->class}}"
             data-parent-id="{{$aQuestion->parent_id}}"
             {{--data-parent-input-type="{{$aQuestion->parent_input_type}}"--}}
             data-dependent-parent-option="{{$aQuestion->dependent_parent_option_id}}"
             data-id="{{$aQuestion->id}}" id="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}"
             style="margin-left: {{$margin}}px;">

            <h3>{{$aQuestion->name}}</h3>
            @foreach($aQuestion as $option)
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$option->id}}[]" value="{{$option->option_id}}"
                               class="styled" {{is_null($option->selected)?'':'checked'}}>
                        {{$option->option_name}}
                        @if($option->option_id===1)
                            <input type="text" name="q_{{$option->id}}_other" value="{{$option->other_text}}">
                        @endif
                    </label>
                </div>
                @if(isset($aQuestion->children))
                    @foreach($aQuestion->children as $childQuestion)
                        @if($childQuestion[0]->dependent_parent_option_id)
                        @include('partials.children',['question'=>$childQuestion,
                        'parent_type'=>'checkbox',
                        'parent_option_id'=>$option->option_id,
                        'parent_id'=>$aQuestion->id,
                        'margin'=>$margin
                        ])
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    @elseif($aQuestion->input_type===\App\Question::TYPE_TITLE)
        <div class="form-group text-left {{$aQuestion->class}}"
             data-parent-id="{{$aQuestion->parent_id}}"
             {{--data-parent-input-type="{{$aQuestion->parent_input_type}}"--}}
             data-dependent-parent-option="{{$aQuestion->dependent_parent_option_id}}"
             data-id="{{$aQuestion->id}}" id="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}"
             style="margin-left: {{$margin}}px;">

            <h3>{{$aQuestion->name}}</h3>
            @if(isset($aQuestion->children))
                @foreach($aQuestion->children as $childQuestion)
                    @include('partials.children',['question'=>$childQuestion,
                    'parent_id'=>$aQuestion->id
                   ,'parent_type'=>'radio'
                   ,'parent_option_id'=>''
                   ,'margin'=>$margin
                    ])
                @endforeach
            @endif
        </div>
    @elseif($aQuestion->input_type===\App\Question::TYPE_NUMBER)
        <div class="form-group text-left {{$aQuestion->class}}"
             data-parent-id="{{$aQuestion->parent_id}}"
             {{--data-parent-input-type="{{$aQuestion->parent_input_type}}"--}}
             data-dependent-parent-option="{{$aQuestion->dependent_parent_option_id}}"
             data-id="{{$aQuestion->id}}" id="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}"
             style="margin-left: {{$margin}}px;">

            <h3>{{$aQuestion->name}}</h3>
            <input type="number" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}" value="">
            @if(isset($aQuestion->children))
                @foreach($aQuestion->children as $childQuestion)
                    @include('partials.children',['question'=>$childQuestion,
                    'parent_id'=>$aQuestion->id
                   ,'parent_type'=>'radio'
                   ,'parent_option_id'=>''
                   ,'margin'=>$margin
                    ])
                @endforeach
            @endif
        </div>
    @elseif($aQuestion->input_type===\App\Question::TYPE_TEXT)
        <div class="form-group text-left {{$aQuestion->class}}"
             data-parent-id="{{$aQuestion->parent_id}}"
             {{--data-parent-input-type="{{$aQuestion->parent_input_type}}"--}}
             data-dependent-parent-option="{{$aQuestion->dependent_parent_option_id}}"
             data-id="{{$aQuestion->id}}" id="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}"
             style="margin-left: {{$margin}}px;">

            <h3>{{$aQuestion->name}}</h3>
            <input type="text" name="q_{{$parent_id}}_{{$parent_option_id}}_{{$aQuestion->id}}" value="">
            @if(isset($aQuestion->children))
                @foreach($aQuestion->children as $childQuestion)
                    @include('partials.children',['question'=>$childQuestion,
                    'parent_id'=>$aQuestion->id
                   ,'parent_type'=>'radio'
                   ,'parent_option_id'=>''
                   ,'margin'=>$margin
                    ])
                @endforeach
            @endif
        </div>
    @endif
    @endforeach
@endif