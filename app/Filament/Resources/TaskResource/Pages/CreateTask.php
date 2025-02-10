<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    // push notification to users which assign them task
    protected function afterCreate()
    {
        $task = $this->record;

        foreach($task->users as $user)
        {
            Notification::make()
            ->title(__('lang.task_assigned'))
            ->icon('heroicon-o-shopping-bag')
            ->body("**{$task->title}")
            ->actions([
                Action::make('View')
                    ->url(TaskResource::getUrl( 'view', ['record' => $task])),
            ])
            ->sendToDatabase($user);   
        }

    }
}
