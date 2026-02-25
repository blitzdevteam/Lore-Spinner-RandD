@if(!empty($conversationHistory))
CONVERSATION SO FAR:
@foreach($conversationHistory as $turn)
@if($turn['role'] === 'narrator')
[NARRATOR]: {!! $turn['text'] !!}
@else
[PLAYER]: {{ $turn['text'] }}
@endif
@endforeach

@endif
@if(!empty($playerAction))
PLAYER'S ACTION: {{ $playerAction }}
@else
This is the OPENING of the event. Narrate the scene cinematically and present the first set of choices.
@endif
