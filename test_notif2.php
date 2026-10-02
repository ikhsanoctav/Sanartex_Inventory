<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TestNotification extends Notification {
    public function via($notifiable) { return ['database']; }
    public function toArray($notifiable) { return ['title' => 'Test', 'body' => 'Test body']; }
}

$user = User::first();
echo "User: " . $user->id . "\n";

try {
    $user->notify(new TestNotification());
    echo "Saved notification to DB directly.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Count: " . \Illuminate\Notifications\DatabaseNotification::count() . "\n";
