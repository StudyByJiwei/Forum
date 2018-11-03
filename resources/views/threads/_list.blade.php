@forelse($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">

                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->user() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>

                    </h4>

                    <h5>Posted By:<a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name  }}</a></h5>
                </div>

                <a href="{{ $thread->path() }}">
                    <strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                </a>
            </div>
        </div>

        <div class="panel-body">
            <div class="body">{{ $thread->body }}</div>
        </div>

        <div class="panel-footer">
            {{ $thread->visits()->count() }} Visits
        </div>
    </div>
@empty
    <p>There are no relevant result at this time.</p>
@endforelse
