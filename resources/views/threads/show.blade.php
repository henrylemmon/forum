@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ __($thread->title) }}
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            {{ $thread->body }}
                    </div>
                </div>

                @foreach ($replies as $reply)
                    @include('threads.reply')
                @endforeach

                <div class="mt-3">
                    {{ $replies->links() }}
                </div>

                <div class="mt-3">
                    @if (auth()->check())
                        <form method="POST" action="{{ $thread->path() }}/replies">
                            @csrf
                            <div class="form-group">
                        <textarea
                            rows="5"
                            id="body"
                            name="body"
                            class="form-control"
                            placeholder="Have something to say?"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Post
                            </button>
                        </form>
                    @else
                        <div class="text-center">
                            <a href="/register">Create and account</a> or <a href="/login">sign in</a> to participate
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a> and currently has {{ $thread->replies_count }}
                            {{ \Str::plural('comment', $thread->replies_count) }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
