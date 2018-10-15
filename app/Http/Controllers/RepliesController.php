<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
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
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * @param             $channelId
     * @param \App\Thread $thread
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validateReply();
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id'=>auth()->id(),

        ]);
        if (request()->expectsJson()) {
            return $reply->load('owner');
        }
        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * @param \App\Reply            $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        $this->validateReply();
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


    protected function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);
        resolve(Spam::class)->detect(request('body'));
    }
}
