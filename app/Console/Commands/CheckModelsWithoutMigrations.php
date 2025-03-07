<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CheckModelsWithoutMigrations extends Command
{
    protected $signature = 'check:models-without-migrations';
    protected $description = 'Check which models do not have a corresponding table in the database';

    public function handle()
    {
        // ดึงรายชื่อ Model ที่มีอยู่ในโฟลเดอร์ app/Models
        $modelPath = app_path('Models');
        $models = [];

        if (File::isDirectory($modelPath)) {
            foreach (File::files($modelPath) as $file) {
                $models[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
        }

        // ดึงรายชื่อ Tables ที่มีอยู่ใน Database สำหรับ SQL Server
        $tables = collect(DB::select("SELECT name FROM sys.tables"))->pluck('name')->toArray();

        // ตรวจสอบ Model ที่ไม่มี Table
        $modelsWithoutTable = [];

        foreach ($models as $model) {
            $tableName = Str::snake(Str::pluralStudly($model));

            if (!in_array($tableName, $tables)) {
                $modelsWithoutTable[] = $model;
            }
        }

        if (empty($modelsWithoutTable)) {
            $this->info('✅ All models have corresponding tables.');
        } else {
            $this->warn('⚠️ The following models do not have corresponding tables:');
            foreach ($modelsWithoutTable as $model) {
                $this->line("- $model");
            }
        }
    }
}
