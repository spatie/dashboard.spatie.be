<x-tile :position="$position">
    <ul wire:poll.5s class="grid" style="grid-auto-rows: auto;">
        @foreach($tweets as $tweet)
            <li class="overflow-hidden pb-4 mb-4 border-b-2 border-screen">
                <div class="markup grid gap-padding" style="grid-auto-rows: auto">
                    <div class="grid gap-2 items-center w-full" style="grid-template-columns: auto 1fr">
                        <avatar :src="tweet.authorAvatar"/>
                        <div class="flex-none overflow-hidden w-10 h-10 rounded-full">
                            <img class="filter-grey block w-10 h-10" src="{{ $tweet->authorAvatar() }}"
                                 style="object-fit: cover;"/>
                            <div class="absolute pin bg-accent opacity-25"></div>
                        </div>
                        <div class="leading-tight min-w-0">
                            <h2 class="truncate">{{ $tweet->authorName() }}</h2>
                            <div class="truncate text-sm">{{ $tweet->authorScreenName() }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="{{ $tweet->displayClass() }}">{{ $tweet->html() }}</div>
                        <div class="mt-1 text-xs text-dimmed">
                            <relative-date :moment="tweet.date"></relative-date>
                            {{ $tweet->date() }}
                            @if ($tweet->hasQuote())
                                <span> In reply to {{ $tweet->quote()->authorScreenName() }} </span>
                            @endif
                        </div>
                    </div>

                    @if($tweet->image())
                        <img alt="tweet image" class="max-h-48 mx-auto" style="objection-fit: cover;"
                             src="{{ $tweet->image() }}"/>
                    @endif

                    @if ($tweet->hasQuote())
                        <div class="py-2 pl-2 text-xs text-dimmed border-l-2 border-screen">
                            {{ $tweet->quote()->html() }}
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</x-tile>
