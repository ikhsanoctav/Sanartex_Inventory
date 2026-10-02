<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Filament\Notifications\Notification;

$user = User::first();
echo "User: " . ($user ? $user->id : 'null') . "\n";

try {
    Notification::make()->title('Test DB Notification')->success()->sendToDatabase($user);
    echo "Saved notification to DB.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Count: " . \Illuminate\Notifications\DatabaseNotification::count() . "\n";
