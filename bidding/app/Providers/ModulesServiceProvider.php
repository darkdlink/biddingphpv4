<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $modules = config('modules.modules');

        foreach ($modules as $module) {
            // Verifica se o módulo está habilitado
            if (!config("modules.module_specific.{$module}.enabled", true)) {
                continue;
            }

            // Carrega rotas
            $this->loadModuleRoutes($module);

            // Carrega migrações
            $this->loadModuleMigrations($module);

            // Pode adicionar mais carregamentos: traduções, views, etc.
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registra os serviços e repositórios dos módulos
        $this->registerModuleServices();
    }

    /**
     * Carrega as rotas de um módulo específico
     */
    private function loadModuleRoutes(string $module): void
    {
        $routesPath = app_path("Modules/{$module}/Routes");

        if (File::isDirectory($routesPath)) {
            $routeFiles = File::files($routesPath);

            foreach ($routeFiles as $file) {
                $middlewares = config("modules.module_specific.{$module}.middlewares", ['web', 'api']);

                Route::middleware($middlewares)
                    ->prefix(strtolower($module))
                    ->group($file->getPathname());
            }
        }
    }

    /**
     * Carrega as migrações de um módulo específico
     */
    private function loadModuleMigrations(string $module): void
    {
        $migrationsPath = app_path("Modules/{$module}/Database/Migrations");

        if (File::isDirectory($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    /**
     * Registra os serviços e repositórios de todos os módulos
     */
    private function registerModuleServices(): void
    {
        $modules = config('modules.modules');
        $namespace = config('modules.namespace');

        foreach ($modules as $module) {
            // Verifica se o módulo está habilitado
            if (!config("modules.module_specific.{$module}.enabled", true)) {
                continue;
            }

            // Registra repositórios
            $repositoriesPath = app_path("Modules/{$module}/Repositories");
            if (File::isDirectory($repositoriesPath)) {
                foreach (File::files($repositoriesPath) as $file) {
                    $fileName = $file->getFilenameWithoutExtension();
                    if (strpos($fileName, 'Interface') !== false) {
                        continue; // Pula interfaces
                    }

                    $interfaceName = "{$namespace}\\{$module}\\Repositories\\{$fileName}Interface";
                    $implementationName = "{$namespace}\\{$module}\\Repositories\\{$fileName}";

                    if (interface_exists($interfaceName) && class_exists($implementationName)) {
                        $this->app->bind($interfaceName, $implementationName);
                    }
                }
            }

            // Registra serviços
            $servicesPath = app_path("Modules/{$module}/Services");
            if (File::isDirectory($servicesPath)) {
                foreach (File::files($servicesPath) as $file) {
                    $fileName = $file->getFilenameWithoutExtension();
                    if (strpos($fileName, 'Interface') !== false) {
                        continue; // Pula interfaces
                    }

                    $interfaceName = "{$namespace}\\{$module}\\Services\\{$fileName}Interface";
                    $implementationName = "{$namespace}\\{$module}\\Services\\{$fileName}";

                    if (interface_exists($interfaceName) && class_exists($implementationName)) {
                        $this->app->bind($interfaceName, $implementationName);
                    }
                }
            }
        }
    }
}
