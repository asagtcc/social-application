<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';
    protected $description = 'Create a new repository and its interface';

    public function handle()
    {
        $name = $this->argument('name');

        $this->createDirectory("app/Repositories");
        $this->createDirectory("app/Repositories/Interfaces");

        $repositoryPath = "app/Repositories/{$name}Repository.php";
        $interfacePath = "app/Repositories/Interfaces/{$name}RepositoryInterface.php";

        $this->createFile($repositoryPath, $this->getRepositoryTemplate($name));
        $this->createFile($interfacePath, $this->getInterfaceTemplate($name));

        $this->info("Repository and Interface created successfully!");
    }

    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    protected function createFile($path, $content)
    {
        if (File::exists($path)) {
            $this->error("The file {$path} already exists!");
            return;
        }

        File::put($path, $content);
    }

    protected function getRepositoryTemplate($name)
    {
        return "<?php\n\nnamespace App\Repositories;\n\nuse App\Repositories\Interfaces\\{$name}RepositoryInterface;\nuse App\Models\\{$name}; // Assumption: You have a Model with the same name\n\nclass {$name}Repository implements {$name}RepositoryInterface\n{\n    public function getAll()\n    {\n        return {$name}::all();\n    }\n\n    public function getById(\$id)\n    {\n        return {$name}::find(\$id);\n    }\n\n    public function create(array \$attributes)\n    {\n        return {$name}::create(\$attributes);\n    }\n\n    public function update(\$id, array \$attributes)\n    {\n        \$record = {$name}::find(\$id);\n        if (\$record) {\n            \$record->update(\$attributes);\n            return \$record;\n        }\n        return null;\n    }\n\n    public function delete(\$id)\n    {\n        \$record = {$name}::find(\$id);\n        if (\$record) {\n            return \$record->delete();\n        }\n        return false;\n    }\n}\n";
    }

    protected function getInterfaceTemplate($name)
    {
        return "<?php\n\nnamespace App\Repositories\Interfaces;\n\ninterface {$name}RepositoryInterface\n{\n    public function getAll();\n    public function getById(\$id);\n    public function create(array \$attributes);\n    public function update(\$id, array \$attributes);\n    public function delete(\$id);\n}\n";
    }
}
