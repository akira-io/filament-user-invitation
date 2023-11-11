<?php

namespace Akira\FilamentUserInvitation\Resources;

use Akira\FilamentUserInvitation\Actions\ResendUserTableAction;
use Akira\FilamentUserInvitation\Models\UserInvitation;
use Akira\FilamentUserInvitation\Resources\UserInvitationResource\Pages\ListUserInvitation;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserInvitationResource extends Resource
{
    protected static ?string $model = UserInvitation::class;

    public static function getNavigationGroup(): ?string
    {
        if (! config('filament-user-invitation.with_navigation_group')) {
            return '';
        }

        return __('filament-user-invitation::resource.navigation_group');
    }

    public static function getNavigationIcon(): ?string
    {
        if (! config('filament-user-invitation.with_navigation_icon')) {
            return '';
        }

        return config('filament-user-invitation.navigation_icon');
    }

    public static function getNavigationBadge(): ?string
    {

        if (config('filament-user-invitation.with_navigation_badge')) {
            return UserInvitation::query()->count();
        }

        return null;
    }

    public static function getLabel(): ?string
    {
        return __('filament-user-invitation::resource.label.singular');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament-user-invitation::resource.label.plural');
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([

                Tables\Columns\TextColumn::make('email')
                    ->label(__('filament-user-invitation::table.email'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label(__('filament-user-invitation::table.expires_at'))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament-user-invitation::table.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                ResendUserTableAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {

        return [
            'index' => ListUserInvitation::route('/'),
        ];
    }
}
