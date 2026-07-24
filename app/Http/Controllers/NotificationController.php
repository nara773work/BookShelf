<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * 通知一覧を表示する。
     *
     * @return View
     */
    public function index()
    {
        $notifications = Auth::user()->unreadNotifications;

        return view('notifications.index', compact('notifications'));
    }

    /**
     * 通知を既読化する。
     *
     * @param  int  $id  通知ID
     * @return RedirectResponse
     */
    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return back();
    }
}
