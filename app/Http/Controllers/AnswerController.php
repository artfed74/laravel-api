<?php

namespace App\Http\Controllers;

use App\Events\ScoreUpdated;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class AnswerController extends Controller
{
    public function checkAnswer(Request $request, $taskId)
    {
        $user = Auth::user();

        $task = Task::findOrFail($taskId);

        // Проверяем, совпадает ли решение пользователя с правильным решением
        if ($request->input('solution') === $task->solution) {

            $user->increment('score_correct_task', 1);
            $user->save();

            $group = $user->group;
            $group->increment('total_score', 1);
            $group->save();

            // Вызываем событие
            event(new ScoreUpdated($user, $group));

            return response()->json([
                'correct' => true,
                'answer' => $task->answer,
                'message' => 'Ты заработал свой честный балл!'
            ]);
        } else {

            $user->increment('score_incorrect_task', 1);
            $user->save();

            return response()->json(['correct' => false, 'message' => 'Ну неет, подумай побольше!'], 400);
        }
    }
}
