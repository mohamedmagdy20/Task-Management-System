<?php

namespace App\Filament\Resources;

use Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource\RelationManager\UserRelationManager;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Carbon\Carbon;
use Filament\Infolists\Components;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $userModel = User::class;


    public static function getNavigationBadge(): ?string
    {
        if(auth()->user()->hasRole('Admin'))
        {
            return static::$model::count();            
        }else{
            return static::$userModel::find(auth()->user()->id)->tasks_count;
        }
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label(__('lang.title'))
                ->required()->default(null),
                DateTimePicker::make('duedate')->after('tomorrow')->label(__('lang.duedate'))->seconds(false),
                Select::make('users')->multiple()->label(__('lang.refer'))
                ->relationship(name: 'users', 
                titleAttribute: 'name',
                modifyQueryUsing :function  ($query) {
                    $query->withoutRole('Admin');
                    if(auth()->user()->hasRole('User'))
                    {
                        $query->where('users.id', auth()->user()->id);
                    }
                })->preload()->required()->hidden(auth()->user()->hasRole('User'))->columnSpanFull(),
                FileUpload::make('attachment')->label(__('lang.attachment'))
                ->multiple()
                ->openable()->downloadable()
                ->imageEditor()
                ->circleCropper()
                ->panelLayout('grid')
                ->previewable()
                ->columnSpanFull(),

                Forms\Components\MarkdownEditor::make('description')->label(__('lang.description'))
                ->required()->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->query(function(){
                $query = Task::query();
                if(auth()->user()->hasRole('User'))
                {
                    return $query->whereHas('users',function(Builder $query){
                        $query->where('user_id',auth()->user()->id);
                    });
                }
            return $query;
            })   
            ->columns([
                Tables\Columns\TextColumn::make('title')
                ->label(__('lang.title'))
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('users.name')
                    ->label(__('lang.refer'))->searchable()
                    ->getStateUsing(function($record){
                        if(auth()->user()->hasRole('User'))
                        {
                            return auth()->user()->name;
                        }
                        return $record->users->pluck('name');
                    }),
                
                Tables\Columns\BadgeColumn::make('status')
                ->label(__('lang.status'))
                ->getStateUsing(function($record){
                    if(auth()->user()->hasRole('User'))
                    {
                        return TaskUser::where('task_id',$record->id)->where('user_id',auth()->user()->id)->first()->status;
                    }
                })
                ->colors([
                    'success' => 'complete', 
                    'info' => 'pending', 
                ])
                
                ->icons([
                    'heroicon-o-check-circle' => 'complete', 
                    'heroicon-m-question-mark-circle' => 'pending', 
                ])->hidden(auth()->user()->hasRole('Admin')),

                Tables\Columns\BadgeColumn::make('duedate')
                    ->label(__('lang.duedate'))
                    ->color(function ($state): string {
                        if (!$state) {
                            return 'gray'; // Handle null values
                        }
                        if (Carbon::parse($state)->isPast()) {
                            return 'danger';
                        }
                        return 'success';
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('users')->label(__('lang.refer'))
                ->relationship(name: 'users',titleAttribute: 'name',
                modifyQueryUsing :function  ($query) {
                    $query->withoutRole('Admin');
                    if(auth()->user()->hasRole('User'))
                    {
                        $query->where('users.id',auth()->user()->id);
                    }
                }),
                Tables\Filters\SelectFilter::make('status')
                ->label(__('lang.status'))
                ->options([
                    'pending' => __('lang.pending'),
                    'completed' => __('lang.complete'),
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        $query->whereHas('users', function (Builder $query) use ($data) {
                            $query->where('task_users.status', $data['value']);
                        });
                    }
                }),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                Tables\Actions\ViewAction::make()

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTasks::route('/{record}'),
        ];
    }

 

    public static function getNavigationLabel(): string
    {
        return __('lang.task');
    }
    public static function getModelLabel(): string
    {
        return __('lang.tasks');
    }
    public static function getPluralModelLabel(): string
    {
        return __('lang.tasks'); // Plural label
    }
}
