@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
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
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @if (auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8 mt-3">
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
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-8 mt-3 text-center">
                    <a href="/register">Create and account</a> or <a href="/login">sign in</a> to participate
                </div>
            </div>
        @endif
    </div>
@endsection
