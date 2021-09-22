<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Filters\ThreadFilters;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class ThreadController extends Controller
{
    /**
     * ThreadController constructor.
     */
    public function __construct()
    {
        /*$this->middleware('auth')->only(['store', 'create']);*/
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Collection
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($filters, $channel);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param Thread $thread
     * @return Application|Factory|View
     */
    public function show($channelId, Thread $thread)
    {
        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Thread $thread
     * @return Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Thread $thread
     * @return Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread $thread
     * @return Response
     */
    public function destroy(Thread $thread)
    {
        //
    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return Collection
     */
    protected function getThreads(ThreadFilters $filters, Channel $channel): Collection
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        }

        /*dd($threads->toSql());*/

        return $threads->get();
    }
}
