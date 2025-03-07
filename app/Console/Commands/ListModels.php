<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ListModels extends Command
{
    protected $signature = 'list:models';
    protected $description = 'List all models in the app/Models directory';

    public function handle()
    {
        $modelPath = app_path('Models'); // โฟลเดอร์ Model
        $models = [];

        if (File::isDirectory($modelPath)) {
            foreach (File::files($modelPath) as $file) {
                $models[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
        }

        if (empty($models)) {
            $this->warn('⚠️ No models found!');
        } else {
            $this->info('📌 Models in app/Models:');
            foreach ($models as $model) {
                $this->line("- $model");
            }
        }
    }
}
