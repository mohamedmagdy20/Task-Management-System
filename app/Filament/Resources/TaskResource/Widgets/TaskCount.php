<?php

namespace App\Filament\Resources\TaskResource\Widgets;

use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskCount extends BaseWidget
{
    protected function getStats(): array
    {
        $taskCount = '';
        $task = Task::query();
        if(auth()->user()->hasRole('User'))
        {
            $taskCount = $task->whereHas('users',function($query){
                $query->where('user_id',auth()->user()->id);
            })->count();
        }else{
            $taskCount = $task->count();
        }

        if(auth()->user()->hasRole('Admin'))
        {
            return [
                //
                Stat::make(__('lang.task'), $taskCount)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

                
                Stat::make(__('lang.admin'), User::role('Admin')->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

                
                Stat::make(__('lang.users'), User::role('User')->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),

                
                Stat::make(__('lang.pending'), TaskUser::where('status','pending')->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

                
                Stat::make(__('lang.complete'), TaskUser::where('status','complete')->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            ];
        }else{
            return [
                Stat::make(__('lang.task'), $taskCount)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

                Stat::make(__('lang.pending'), TaskUser::where('status','pending')->where('user_id',auth()->user()->id)->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

                
                Stat::make(__('lang.complete'), TaskUser::where('status','complete')->where('user_id',auth()->user()->id)->count())
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            
            ];
       }
        
    }
}
