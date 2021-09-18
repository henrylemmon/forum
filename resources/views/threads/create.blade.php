@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create a New Thread') }}</div>

                    <div class="card-body">
                        <form method="POST" action="/threads">
                            @csrf

                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control">
                                    <option value="">Select a Channel</option>

                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : ''  }}>
                                            {{ ucwords($channel->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">
                            </div>

                            <div class="form-group">
                              <label for="body">Body</label>
                              <textarea id="body" name="body" class="form-control" rows="5">{{ old('body') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Create Thread
                            </button>

                            @if ($errors->any())
                                <ul class="alert alert-danger mt-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

