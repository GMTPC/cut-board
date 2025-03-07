<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateMigrationsFromModels extends Command
{
    protected $signature = 'generate:migrations-from-models';
    protected $description = 'Generate migrations for models without corresponding tables';

    public function handle()
    {
        // ดึงรายชื่อ Model ที่อยู่ในโฟลเดอร์ app/Models
        $modelPath = app_path('Models');
        $models = [];

        if (File::isDirectory($modelPath)) {
            foreach (File::files($modelPath) as $file) {
                $models[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            }
        }

        // ดึงรายชื่อ Tables ที่มีอยู่ใน Database สำหรับ SQL Server
        $tables = collect(DB::select("SELECT name FROM sys.tables"))->pluck('name')->toArray();

        // สร้าง Migration ให้กับ Model ที่ไม่มี Table และยังไม่มี Migration
        foreach ($models as $model) {
            $tableName = Str::snake(Str::pluralStudly($model));

            // เช็คว่า Table มีอยู่แล้วหรือไม่
            if (in_array($tableName, $tables)) {
                $this->warn("⚠️ Table {$tableName} already exists, skipping...");
                continue;
            }

            // เช็คว่า Migration มีอยู่หรือไม่
            $migrationFiles = File::glob(database_path("migrations/*_create_{$tableName}_table.php"));
            if (!empty($migrationFiles)) {
                $this->warn("⚠️ Migration for {$tableName} already exists, skipping...");
                continue;
            }

            // สร้าง Migration ถ้ายังไม่มี
            $this->info("✅ Generating migration for: {$tableName}");
            $this->call('make:migration', [
                'name' => "create_{$tableName}_table"
            ]);
        }

        $this->info('🎉 Migration files generated successfully!');
    }
}
