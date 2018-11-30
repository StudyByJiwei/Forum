{{-- Editing the question. --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">

        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">
            <input type="text" value="{{ $thread->title }}" class="form-control">
            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control" rows="10">{{ $thread->body }}</textarea>
        </div>

    </div>

    <div class="panel-footer">
        <button class="btn btn-xs" @click="editing">Edit</button>
    </div>
</div>
{{-- Viewing the question.  --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">

        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="25" height="25" class="mr-1">
            <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">
                                {{ $thread->creator->name }}
                            </a> posted:
                {{ $thread->title }}
                            </span>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="panel-body">
        {{ $thread->body }}
    </div>

    <div class="panel-footer">
        <button class="btn btn-xs" @click="editing">Edit</button>
    </div>
</div>

