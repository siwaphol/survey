@if(count($questions)>0)
    @foreach($questions as $key=>$question)
        @if($question->input_type!==\App\Question::TYPE_TITLE)
            @if(is_null($question->dependent_parent_option_id))
                $scope.question.no_{{$question->parent_id}}_{{$question->dependent_parent_option_id}}_{{$question->id}} = $question[0]->text;
            @endif
        @endif

        {{--main question has children--}}
        @if(isset($question->children) && count($question->children)>0)
            @include('partials.create_angular_scope',[
                'questions'=>$question->children
            ])
        @endif
    @endforeach
@endif