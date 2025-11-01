<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateVisitorCount extends Command
{
    protected $signature = 'visitors:update';
    protected $description = 'Update live visitor count';

    public function handle()
    {
        $visitors = Cache::get('visitors', []);
        $threshold = now()->subMinutes(3)->timestamp; // 3 mins window

        $activeVisitors = array_filter($visitors, function ($lastSeen) use ($threshold) {
            return $lastSeen >= $threshold;
        });

        // Save live visitors count in cache
        Cache::put('live_visitors_count', count($activeVisitors), now()->addMinutes(3));

        $this->info('Live visitors: ' . count($activeVisitors));
    }
}
