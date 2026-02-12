@php
    use Illuminate\Support\Collection;

    /**
     * @var Collection<int, \App\Models\Event>|null $previousEvents Events before target (positions -5 to -1)
     * @var \App\Models\Event $targetEvent The target event (position 0)
     * @var Collection<int, \App\Models\Event>|null $nextEvents Events after target (positions +1 to +5)
     */
@endphp
<events>
    @foreach($previousEvents ?? [] as $event)
        <event position="-{{ $previousEvents->count() - $loop->index }}">
            <title>{{ $event->title }}</title>
            <objective>
                {{ $event->objectives }}
            </objective>
            <attributes>
                {{ $event->attributes }}
            </attributes>
            <content>
                {{ $event->content }}
            </content>
        </event>
    @endforeach

    <event position="0">
        <title>{{ $targetEvent->title }}</title>
        <objective></objective>
        <attributes></attributes>
        <content>
            {{ $targetEvent->content }}
        </content>
    </event>

    @foreach($nextEvents ?? [] as $event)
        <event position="+{{ $loop->iteration }}">
            <title>{{ $event->title }}</title>
            <objective></objective>
            <attributes></attributes>
            <content>
                {{ $event->content }}
            </content>
        </event>
    @endforeach
</events>
