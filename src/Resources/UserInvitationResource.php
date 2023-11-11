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

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function table(Table $table): Table
    {

        return $table
            ->columns([

                Tables\Columns\TextColumn::make('email')
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make(__('created_at'))
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
