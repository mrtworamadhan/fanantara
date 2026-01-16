<?php

namespace App\Livewire\Member;

use Livewire\Component;

class NotificationInbox extends Component
{

    public function render()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        
        auth()->user()->unreadNotifications->markAsRead();

        return view('livewire.member.notification-inbox', [
            'notifications' => $notifications
        ]);
    }
}
