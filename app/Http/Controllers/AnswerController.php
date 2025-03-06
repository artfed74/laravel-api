<?php

namespace App\Http\Controllers;

use App\Events\ScoreUpdated;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Pusher\Pusher;

class AnswerController extends Controller
{
    public function checkAnswer(Request $request, $taskId)
    {
        $user = Auth::user();
        $task = Task::find($taskId);

        // Проверяем, совпадает ли решение пользователя с правильным решением (без учета регистра и пробелов)
        if (trim(mb_strtolower($request->input('solution'))) === trim(mb_strtolower($task->solution))) {
            $scoreToAdd = 0;

            // Определяем количество очков в зависимости от типа задачи
            if ($task->type === 'select') {
                $scoreToAdd = 1; // 3 очка за select
                $user->group->increment('score_task_3', 1);
            } elseif ($task->type === 'update') {
                $scoreToAdd = 1; // 4 очка за update
                $user->group->increment('score_task_4', 1);
            }

            // Обновляем очки пользователя
            $user->increment('score_correct_task', 1);
            $user->group->increment('total_score', $scoreToAdd); // Обновление общего счёта пользователя
            $user->save();

            // Вызываем событие
            event(new ScoreUpdated($user, $user->group));

            return response()->json([
                'correct' => true,
                'answer' => $task->answer,
                'message' => 'Ты заработал свой честный балл!'
            ]);
        } else {
            $user->increment('score_incorrect_task', 1);
            $user->save();

            return response()->json([
                'correct' => false,
                'message' => "Ну неет, подумай побольше! "
            ]);
        }
    }

}
