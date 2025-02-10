<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    // protected static ?string $navigationGroup = __('lang.settings');

    public static function getNavigationGroup(): ?string
    {
        return __('lang.settings');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('lang.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')->label(__('lang.email'))
                    ->email()
                    ->different('email')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')->label(__('lang.password'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')->confirmed(),

                Forms\Components\TextInput::make('password_confirmation')->password()
                    ->label(__('lang.password_confirmation'))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('roles')->multiple()->label(__('filament-spatie-roles-permissions::filament-spatie.field.roles'))
                ->relationship('roles', 'name')
                ->preload()
                ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('lang.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('lang.email'))
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('roles.name')
                ->label(__('lang.status'))
                    ->colors([
                        'success' => 'admin', 
                        'info' => 'user', 
                    ])
                    ->icons([
                        'heroicon-o-shield-check' => 'Admin', 
                        'heroicon-m-user-circle' => 'User', 
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
           
                    
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('roles')->label(__('filament-spatie-roles-permissions::filament-spatie.field.roles'))
                ->relationship('roles','name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->hidden(fn (User $record): bool => $record->roles[0]->name === 'Admin')
                ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        // Delete selected users except the admin user
                        $records->where('email', '!=', 'admin@admin.com')->each->delete();
                    }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    public static function getNavigationLabel(): string
    {
        return __('lang.user');
    }
    public static function getModelLabel(): string
    {
        return __('lang.users');
    }
    public static function getPluralModelLabel(): string
    {
        return __('lang.users'); // Plural label
    }
}
