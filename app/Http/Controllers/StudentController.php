<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Получение всех студентов
     */
    public function index()
    {
        $students = User::with('group')->get();

        return response()->json($students);
    }

    /**
     * Создание наверное не понадобиться :)
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Получение конкретного студента
     */
    public function show(string $id)
    {
        $student = User::with('group')->findOrFail($id);

        return response()->json($student);
    }

    /**
     * Обновление данных студента тоже не понадобиться
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Удаление если вдруг кто-то неправильно зарегался
     */
    public function destroy(string $id)
    {
        $student = User::findOrFail($id);

        $student->delete();

        return response()->json(['message' => 'Студент успешно удален']);
    }
}
