<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseAuth;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseAuth
{
    public function getHeading(): string | Htmlable
    {
        return 'Selamat Datang Kembali';
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Masuk untuk mengelola inventori Anda';
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email atau Username')
            ->placeholder('Masukkan email Anda')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi')
            ->placeholder('Masukkan kata sandi Anda')
            ->hint(filament()->hasPasswordReset() ? new \Illuminate\Support\HtmlString('<a href="' . filament()->getRequestPasswordResetUrl() . '" style="color: inherit; text-decoration: none;">Lupa Kata Sandi?</a>') : null)
            ->password()
            ->revealable(true)
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login_type = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $login_type => $data['email'],
            'password'  => $data['password'],
        ];
    }

    public function getSubmitButtonLabel(): string
    {
        return '<div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg> Masuk</div>';
    }
}
