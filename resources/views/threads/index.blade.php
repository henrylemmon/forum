@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Forum Threads') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @forelse ($threads as $thread)
                            <article>
                                <h4>
                                    <a href="/threads/{{ $thread->id }}">
                                        {{ $thread->title }}
                                    </a>
                                </h4>
                                <div class="body">{{ $thread->body }}</div>
                            </article>

                            <hr>
                        @empty
                            <article>
                                <h4>No Threads Yet!</h4>
                                <div class="body">So create some willya?</div>
                            </article>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
