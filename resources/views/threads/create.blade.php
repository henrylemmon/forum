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
                                <label for="title">Title</label>
                                <input type="text" id="title" name="title" class="form-control">
                            </div>

                            <div class="form-group">
                              <label for="body">Body</label>
                              <textarea id="body" name="body" class="form-control" rows="5"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Create Thread
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

