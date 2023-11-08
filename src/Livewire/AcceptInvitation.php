<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Livewire;

use Akira\FilamentUserInvitation\Models\UserInvitation;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Dashboard;
use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AcceptInvitation extends SimplePage
{
    use InteractsWithFormActions;
    use InteractsWithForms;

    public static string $view = 'filament-user-invitation::livewire.accept-invitation';

    public int $invitation;

    public ?array $data = [];

    private UserInvitation $invitationModel;

    public function mount(): void
    {
        $this->invitationModel = UserInvitation::findOrFail($this->invitation);
        $this->form->fill([
            'email' => $this->invitationModel->email,
        ]);
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

    public function getHeading(): string
    {

        return 'Accept Invitation';
    }

    public function hasLogo(): bool
    {

        return false;
    }

    public function getSubheading(): string
    {

        return 'Create your user to accept the invitation.';
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
        ray('fsfs');
        $this->invitationModel = UserInvitation::find($this->invitation);

        $user = User::create([
            'name' => $this->form->getState()['name'],
            'email' => $this->invitationModel->email,
            'password' => Hash::make($this->form->getState()['password']),
        ]);
        auth()->login($user);
        $this->invitationModel->delete();
        $this->redirect(Dashboard::getUrl());
    }
}
