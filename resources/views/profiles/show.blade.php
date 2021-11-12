@extends('layouts.app')

@section('content')
    <div class="container">
        <div>
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @foreach($threads as $thread)
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                        <a href="#">{{ $thread->creator->name }}</a> posted:
                        {{ __($thread->title) }}
                    </span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        @endforeach
        {{ $threads->links() }}
    </div>
@endsection
