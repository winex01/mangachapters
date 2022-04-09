<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ChangeProxyNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ProxyNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winex:proxy-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notice to admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = auth()->user();
        $usersWithAdminPermission = User::permission('admin_proxy_notice')->get();
        Notification::send($usersWithAdminPermission, new ChangeProxyNotification($user));
    }
}
