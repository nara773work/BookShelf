<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReminderNotification extends Notification
{
    use Queueable;

    /**
     * reminder通知を作成する。
     *
     * @param  Book  $book  通知対象の書籍
     */
    public function __construct($book)
    {
        $this->book = $book;
    }

    /**
     * 通知保存先を取得する。
     *
     * @param  object  $notifiable  通知対象ユーザー
     * @return array<int,string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * データベース通知の内容を作成する。
     *
     * @param  mixed  $notifiable  通知対象ユーザー
     * @return array<string,mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'remind',
            'title' => 'リマインド通知',
            'body' => '『'.$this->book->title.'』'.'の期日が残り3日になりました',
            'book_id' => $this->book->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
