<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('lang.refer');
    }
    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make(__('lang.refer'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(__('lang.refer'))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('lang.name'))
                ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                ->label(__('lang.status'))
                ->colors([
                    'success' => 'complete', 
                    'info' => 'pending', 
                ])
                ->icons([
                    'heroicon-o-check-circle' => 'complete', 
                    'heroicon-m-question-mark-circle' => 'pending', 
                ]),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                ->label(__('lang.status'))
                ->options([
                    'pending' => __('lang.pending'),
                    'completed' => __('lang.complete'),
                ]),
            ])
            ->headerActions([
              
            ])
            ->actions([
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }
}
