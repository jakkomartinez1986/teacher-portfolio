<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ModelTableService
{
    public function getModelsForTables()
    {
        // Obtener nombres de tablas
        //$tables = DB::select('SHOW TABLES');
        $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\'');
        
        $tableNames = array_map(function ($table) {
            return array_values((array)$table)[0];
        }, $tables);

        // Obtener modelos
        $models = [];
        $modelFiles = $this->getModelFiles(app_path('Models'));

        foreach ($modelFiles as $file) {
            $modelName = pathinfo($file, PATHINFO_FILENAME);
            $modelClass = "App\\Models\\" . $this->getModelNamespace($file, app_path('Models'));
            
            if (class_exists($modelClass)) {
                $modelInstance = app($modelClass);

                if ($modelInstance instanceof \Illuminate\Database\Eloquent\Model) {
                    $table = $modelInstance->getTable();
                    if (in_array($table, $tableNames)) {
                        $models[$modelName] = $table;
                    }
                }
            }
        }

        return $models;
    }

    protected function getModelFiles($directory)
    {
        $files = [];
        foreach (File::allFiles($directory) as $file) {
            $files[] = $file->getRealPath();
        }
        foreach (File::directories($directory) as $subdirectory) {
            $files = array_merge($files, $this->getModelFiles($subdirectory));
        }
        return $files;
    }

    protected function getModelNamespace($filePath, $baseDirectory)
    {
        $relativePath = str_replace($baseDirectory . DIRECTORY_SEPARATOR, '', $filePath);
        $relativePath = str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);
        return str_replace('.php', '', $relativePath);
    }
}
