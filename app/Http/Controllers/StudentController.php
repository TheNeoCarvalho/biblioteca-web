<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('registration', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(15);

        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        try {
            $student = Student::create($request->validated());

            return redirect()->route('students.index')
                ->with('success', "Aluno '{$student->name}' cadastrado com sucesso!")
                ->with('info', 'O aluno já pode realizar empréstimos de livros.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar aluno. Verifique os dados e tente novamente.')
                ->withErrors(['general' => 'Se o problema persistir, entre em contato com o suporte.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        try {
            $oldName = $student->name;
            $student->update($request->validated());

            $message = $oldName !== $student->name 
                ? "Dados do aluno atualizados com sucesso! Nome alterado de '{$oldName}' para '{$student->name}'."
                : "Dados do aluno '{$student->name}' atualizados com sucesso!";

            return redirect()->route('students.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar dados do aluno. Tente novamente.')
                ->withErrors(['general' => 'Verifique se todos os campos estão preenchidos corretamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {
            // Check if student has active loans
            $activeLoans = $student->loans()->where('is_returned', false)->count();
            
            if ($activeLoans > 0) {
                return back()->with('error', "Não é possível excluir o aluno '{$student->name}' pois ele possui {$activeLoans} empréstimo(s) ativo(s).")
                    ->with('warning', 'Registre a devolução dos livros antes de excluir o aluno.');
            }

            $studentName = $student->name;
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', "Aluno '{$studentName}' excluído com sucesso!")
                ->with('info', 'Todos os dados relacionados ao aluno foram removidos do sistema.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir aluno. Tente novamente.')
                ->withErrors(['general' => 'Se o problema persistir, entre em contato com o suporte.']);
        }
    }
}
