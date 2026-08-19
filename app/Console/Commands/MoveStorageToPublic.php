<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveStorageToPublic extends Command
{
    protected $signature = 'storage:move-public';
    protected $description = 'Move files from storage/app/public to public/storage (non-destructive).';

    public function handle()
    {
        $source = storage_path('app/public');
        $target = public_path('storage');

        if (! File::exists($source)) {
            $this->info('Source directory does not exist: '.$source);
            return 0;
        }

        File::ensureDirectoryExists($target);

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relative = str_replace($source.'\\', '', $file->getPathname());
            $dest = $target.DIRECTORY_SEPARATOR.$relative;
            File::ensureDirectoryExists(dirname($dest));
            File::copy($file->getPathname(), $dest);
        }

        $this->info('Copied '.count($files).' files to '.$target);

        return 0;
    }
}
