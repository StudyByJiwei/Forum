<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Thread
 *
 * @package App
 */
class Thread extends Model
{
    use RecordsActivity;
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['creator', 'channel'];

    /**
     * @var array
     */
    protected $appends = ['isSubscribedTo'];
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();


        static::deleting(function($thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * @var array
     */
    protected $fillable = ['title', 'body', 'user_id', 'channel_id'];

    /**
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $reply
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);
        $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    /**
     * @param $query
     * @param $filters
     *
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * @param null $userId
     *
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);
        return $this;
    }

    /**
     * @param null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }
}
