<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
/**
 * Class YouWereMentioned
 *
 * @package \App\Notifications
 */
class YouWereMentioned extends Notification
{
    use Queueable;

    protected $reply;

    /**
     * YouWereMentioned constructor.
     *
     * @param $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'message' => $this->reply->owner->name . 'mentioned you in ' . $this->reply->thread->title,
            'link'    => $this->reply->path()
        ];
    }
}
