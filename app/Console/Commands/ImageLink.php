<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating symbol link to image';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        symlink(public_path('images'),base_path("images"));
        return Command::SUCCESS;
    }
}
