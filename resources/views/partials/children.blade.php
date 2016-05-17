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
        @endforeach
        
        @if(isset($question->children))
            @each('partials.children',$question->children,'question')
        @endif
    </div>
@endif