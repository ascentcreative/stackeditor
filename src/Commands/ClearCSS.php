<?php

namespace AscentCreative\StackEditor\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

class ClearCSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stackeditor:clearcss';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear created CSS files';

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

       // echo 'running';

        $path = Storage::disk('public')->path('stackeditor');

        // Get all files in a directory
        $files = Storage::disk('public')->files('stackeditor');

        // Storage::cleanDirectory($path);

        // Delete Files
        Storage::disk('public')->delete($files);

        return 0;


    }
}
