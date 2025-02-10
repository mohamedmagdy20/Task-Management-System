<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Filament\Resources\TaskResource\RelationManagers\UsersRelationManager;
use App\Models\TaskUser;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTasks extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        if(auth()->user()->hasRole('Admin'))
        {
         return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()->requiresConfirmation()
         ];
        }
        else{
            return [
                Action::make('complete_task')->label(__('lang.complete_status'))
                ->action(function () {
                    $record = TaskUser::where('user_id',auth()->user()->id)
                    ->where('task_id',$this->record->id)->first();
                    $record->update([
                        'status'=>'complete'
                    ]);

                    // database Notification
                    $admins = User::role('Admin')->get();
                    foreach($admins as $admin)
                    {
                        Notification::make()
                        ->title(__('lang.task_done').auth()->user()->name)
                        ->icon('heroicon-o-shopping-bag')
                        ->body("**{$this->record->title}")
                        ->actions([
                            NotificationAction::make('View')
                                ->url(TaskResource::getUrl( 'view', ['record' => $this->record])),
                        ])
                        ->sendToDatabase($admin);  
                    } 
                    // 

                    Notification::make()
                    ->success()
                    ->title(__('lang.mission_done'))
                    ->body(__('lang.mission_done'))->send();
               
                    return redirect()->route('filament./.resources.tasks.index')->with('success');
                     })
                ->requiresConfirmation()
                ->icon('heroicon-s-check-circle')
                ->color('success')
                ->hidden(function(){
                    $record = TaskUser::where('user_id',auth()->user()->id)
                    ->where('task_id',$this->record->id)->first();
                   return  $record->status == 'complete';
                }),

               
    
            ];
        }

    }
    public function getRelationManagers(): array
    {
        if(auth()->user()->hasRole('Admin'))
        {

            return [
                UsersRelationManager::class,
            ];
        }
        return [];
    }
}
