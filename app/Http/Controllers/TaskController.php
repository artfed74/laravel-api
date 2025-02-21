<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Вывод всех задач с их решением и ответами,
     * для каждого студента выводиться только для его варианта!!
     */
    public function index()
    {
        $user = Auth::user();

        $tasks = Task::where('variant', $user->variant)
            ->get();

        return response()->json($tasks);
    }

    /**
     * Создание задачи для админа
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'variant' => 'required|in:A,B,C,D',
            'task_number' => 'required|integer',
            'conditions' => 'required|string',
            'solution' => 'required|string',
            'answer' => 'required|string',
        ]);

        $answer = Task::create([
            'variant' => $validated['variant'],
            'task_number' => $validated['task_number'],
            'conditions' => $validated['conditions'],
            'solution' => $validated['solution'],
            'answer' => $validated['answer'],
        ]);

        return response()->json(['message' => 'Задача успешно создана', 'answer' => $answer], 200);
    }

    /**
     * Вывод конкретной задачи для каждого студента выводиться
     * только для его варианта!!
     */
    public function show(string $id)
    {
        $user = Auth::user();

        $task = Task::where('variant', $user->variant)
            ->findOnFail($id);


        return response()->json($task);
    }

    /**
     * Обновление задачи на всякий божий случай сделаю :)
     */
    public function update(Request $request, $id)
    {
        $answer = Task::findOnFail($id);

        $validated = $request->validate([
            'variant' => 'required|in:A,B,C,D',
            'task_number' => 'required|integer',
            'conditions' => 'required|string',
            'solution' => 'required|string',
            'answer' => 'required|string',
        ]);

        $answer->update([
            'variant' => $validated['variant'],
            'task_number' => $validated['task_number'],
            'conditions' => $validated['conditions'],
            'solution' => $validated['solution'],
            'answer' => $validated['answer'],
        ]);

        return response()->json(['message' => 'Задача успешно отредактирована', 'answer' => $answer]);
    }

    /**
     * Удаление задачи
     */
    public function destroy(string $id)
    {
        $answer = Task::findOnFail($id);

        $answer->delete();

        return response()->json(['message' => 'Задача удалена']);
    }
}
