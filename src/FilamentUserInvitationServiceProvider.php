<?php

namespace Akira\FilamentUserInvitation;

use Akira\FilamentUserInvitation\Commands\FilamentUserInvitationCommand;
use Akira\FilamentUserInvitation\Testing\TestsFilamentUserInvitation;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentUserInvitationServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-user-invitation';

    public static string $viewNamespace = 'filament-user-invitation';

    public function configurePackage(Package $package): void
    {

        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {

                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('akira/filament-user-invitation');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    public function packageBooted(): void
    {

        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName(),
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName(),
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-user-invitation/{$file->getFilename()}"),
                ], 'filament-user-invitation-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentUserInvitation());

    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {

        return [
            FilamentUserInvitationCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {

        return [
            'create_filament-user-invitation_table',
        ];
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {

        return [
            // AlpineComponent::make('filament-user-invitation', __DIR__ . '/../resources/dist/components/filament-user-invitation.js'),
            Css::make('filament-user-invitation-styles', __DIR__ . '/../resources/dist/filament-user-invitation.css'),
            Js::make('filament-user-invitation-scripts', __DIR__ . '/../resources/dist/filament-user-invitation.js'),
        ];
    }

    protected function getAssetPackageName(): ?string
    {

        return 'akira/filament-user-invitation';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {

        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {

        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {

        return [];
    }
}
