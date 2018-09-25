<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;

/**
 * Class RepliesController
 *
 * @package App\Http\Controllers
 */
class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param             $channelId
     * @param \App\Thread $thread
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);
        $thread->addReply([
            'body' => request('body'),
            'user_id'=>auth()->id(),

        ]);

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * @param \App\Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }

    /**
     * @param \App\Reply $reply
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->wantsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }
}
