<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Получение всех групп и студентов в них
     */
    public function index()
    {
        $groups = Group::with('students')->get();

        return response()->json($groups);
    }

    /**
     * Создание группы
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Group::create([
            'name' => $validated['name'],
        ]);

        return response()->json(['message' => 'Группа успешно создана', 'group' => $group], 200);

    }

    /**
     * Получение одной группы и студентов в ней
     */
    public function show(string $id)
    {
        $group = Group::with('students')->findOrFail($id);

        return response()->json($group);
    }

    /**
     * Обновление балов за групповые задания
     */


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'task' => 'required|in:score_task_1,score_task_2,score_task_3,score_task_4', // добавлено score_task_3 и score_task_4
            'score' => 'required|integer|min:0'
        ]);

        $group = Group::findOrFail($id);

        // Увеличиваем соответствующий счет на значение из запроса
        $group->{$validated['task']} += $validated['score'];

        // Пересчитываем total_score, учитывая все task'и
        $group->total_score = $group->score_task_1 + $group->score_task_2 + $group->score_task_3 + $group->score_task_4;

        $group->save();

        return response()->json(['message' => 'Баллы за задание обновлены!', 'group' => $group], 200);
    }



    public function destroy(string $id)
    {
        //
    }
}
