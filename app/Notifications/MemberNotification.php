<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MemberNotification extends Notification
{
    use Queueable;

    // Tambahkan properti data untuk menampung isi notifikasi
    public array $data;

    /**
     * Pastikan __construct menerima parameter data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Gunakan channel 'database' agar tersimpan di tabel notifications
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Struktur data yang akan disimpan ke kolom 'data' di database (JSON)
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => $this->data['title'] ?? 'Notifikasi Baru',
            'message' => $this->data['message'] ?? '',
            'type'    => $this->data['type'] ?? 'info', 
            'url'     => $this->data['url'] ?? '#',
        ];
    }
}