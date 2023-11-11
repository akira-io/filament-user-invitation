<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Livewire;

use Akira\FilamentUserInvitation\Models\User;
use Akira\FilamentUserInvitation\Models\UserInvitation;
use Akira\FilamentUserInvitation\Resources\UserInvitationResource;
use AllowDynamicProperties;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Dashboard;
use Filament\Pages\SimplePage;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

#[AllowDynamicProperties]
/** mixin */
class AcceptInvitation extends SimplePage
{
    use HasRoutes;
    use InteractsWithFormActions;
    use InteractsWithForms;

    public static string $view = 'filament-user-invitation::livewire.accept-invitation';

    protected static string $resource = UserInvitationResource::class;

    public string $invitation;

    public ?array $data = [];

    private ?Model $invitationModel;

    public static function routes(Panel $panel): void
    {
        Route::get('/invitation/{invitation}/', static::class)
            ->middleware(static::getRouteMiddleware($panel))
            ->withoutMiddleware(static::getWithoutRouteMiddleware($panel))
            ->name('filament-user-invitation.accept-invitation');
    }

    public function mount(): void
    {
        $this->invitationModel = $this->getInvitation();
        $this->fillFormWithInvitationEmail();
    }

    private function getInvitation(): ?UserInvitation
    {
        $invitation = UserInvitation::query()->firstWhere('token', $this->invitation);
        if (! $invitation) {
            $this->invitationNotFoundNotification();

            return null;
        }
        if ($this->isInvitationExpired($invitation)) {

            $this->expiredInvitationNotification();

            $this->redirect(filament()->getLoginUrl());

            return null;
        }

        return $invitation;
    }

    private function fillFormWithInvitationEmail(): void
    {
        $this->form->fill([
            'email' => $this->invitationModel?->email,
        ]);
    }

    private function invitationNotFoundNotification(): void
    {

        Notification::make()
            ->title(__('Invalid invitation'))
            ->body(__('The invitation you are trying to accept is invalid. Please contact the administrator.'))
            ->danger()
            ->send();
    }

    private function isInvitationExpired(
        Model | UserInvitation | \LaravelIdea\Helper\Akira\FilamentUserInvitation\Models\_IH_UserInvitation_QB | \Illuminate\Database\Eloquent\Builder $invitation
    ): bool {

        return now()->format('Y-m-d H:i:s') > $invitation->expires_at->format('Y-m-d H:i:s');
    }

    private function expiredInvitationNotification(): void
    {

        Notification::make()
            ->title(__('Expired invitation'))
            ->body(__('Your invitation has expired, please contact the administrator.'))
            ->danger()
            ->send();
    }

    public function getHeading(): string
    {

        return 'Accept Invitation';
    }

    public function getSubheading(): string
    {

        return 'Create your user to accept the invitation.';
    }

    public function hasLogo(): bool
    {

        return false;
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ])->statePath('data');
    }

    protected function getNameFormComponent(): Component
    {

        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {

        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->disabled();
    }

    protected function getPasswordFormComponent(): Component
    {

        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {

        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
            ->password()
            ->required()
            ->dehydrated(false);
    }

    protected function getFormActions(): array
    {

        return [
            $this->getRegisterFormAction(),
        ];
    }

    public function getRegisterFormAction(): Action
    {

        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }

    public function create(): void
    {

        $this->invitationModel = $this->getInvitation();

        $user = $this->createUser();

        auth()->login($user);

        $this->invitationModel->delete();

        $this->redirect(Dashboard::getUrl());
    }

    private function createUser(): User
    {
        return User::query()->create([
            'name' => $this->form->getState()['name'],
            'email' => $this->invitationModel->email,
            'password' => Hash::make($this->form->getState()['password']),
        ]);
    }
}
