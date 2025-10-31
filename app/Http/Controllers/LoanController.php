<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoanController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of active loans.
     */
    public function index(Request $request): View
    {
        $filters = [
            'student_name' => $request->get('student_name'),
            'book_title' => $request->get('book_title'),
        ];

        $loans = $this->loanService->getActiveLoans($filters);

        return view('loans.index', compact('loans', 'filters'));
    }

    /**
     * Show the form for creating a new loan.
     */
    public function create(): View
    {
        $students = Student::orderBy('name')->get();
        $books = Book::where('available_quantity', '>', 0)->orderBy('title')->get();

        return view('loans.create', compact('students', 'books'));
    }

    /**
     * Store a newly created loan in storage.
     */
    public function store(LoanRequest $request): RedirectResponse
    {
        try {
            $student = Student::findOrFail($request->student_id);
            $book = Book::findOrFail($request->book_id);

            $loan = $this->loanService->createLoan($student, $book);

            $message = "Empréstimo registrado com sucesso!";
            $infoMessage = "Livro '{$book->title}' emprestado para {$student->name}. Data de devolução: {$loan->due_date->format('d/m/Y')}.";
            
            $response = redirect()
                ->route('loans.index')
                ->with('success', $message)
                ->with('info', $infoMessage);

            // Check if book is now low on stock
            $book->refresh();
            if ($book->hasLowStock()) {
                $response->with('warning', "Atenção: O livro '{$book->title}' está com estoque baixo ({$book->available_quantity} disponível).");
            }

            return $response;

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao registrar empréstimo: ' . $e->getMessage())
                ->withErrors(['general' => 'Verifique se o aluno e o livro selecionados estão corretos.']);
        }
    }

    /**
     * Display the specified loan.
     */
    public function show(Loan $loan): View
    {
        $loan->load(['student', 'book']);
        return view('loans.show', compact('loan'));
    }

    /**
     * Return a book (mark loan as returned).
     */
    public function return(Loan $loan): RedirectResponse
    {
        try {
            $student = $loan->student;
            $book = $loan->book;
            $wasOverdue = $loan->isOverdue();
            $daysOverdue = $wasOverdue ? $loan->getDaysOverdue() : 0;

            $this->loanService->returnBook($loan);

            $message = "Devolução registrada com sucesso!";
            $infoMessage = "Livro '{$book->title}' devolvido por {$student->name}.";
            
            $response = redirect()
                ->route('loans.index')
                ->with('success', $message)
                ->with('info', $infoMessage);

            if ($wasOverdue) {
                $response->with('warning', "Atenção: O livro foi devolvido com {$daysOverdue} dia(s) de atraso.");
            } else {
                $response->with('info', $infoMessage . ' Devolução realizada dentro do prazo.');
            }

            return $response;

        } catch (Exception $e) {
            return back()
                ->with('error', 'Erro ao registrar devolução: ' . $e->getMessage())
                ->withErrors(['general' => 'Tente novamente ou entre em contato com o suporte.']);
        }
    }

    /**
     * Display loan history.
     */
    public function history(Request $request): View
    {
        $filters = [
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'student_name' => $request->get('student_name'),
        ];

        $loans = $this->loanService->getLoanHistory($filters);

        return view('loans.history', compact('loans', 'filters'));
    }

    /**
     * Display overdue loans.
     */
    public function overdue(): View
    {
        $loans = $this->loanService->getOverdueLoans();

        return view('loans.overdue', compact('loans'));
    }

    /**
     * Search for students (AJAX endpoint).
     */
    public function searchStudents(Request $request)
    {
        $term = $request->get('term');
        
        $students = Student::where('name', 'like', '%' . $term . '%')
            ->orWhere('registration', 'like', '%' . $term . '%')
            ->limit(10)
            ->get(['id', 'name', 'registration']);

        return response()->json($students);
    }

    /**
     * Search for books (AJAX endpoint).
     */
    public function searchBooks(Request $request)
    {
        $term = $request->get('term');
        
        $books = Book::where('available_quantity', '>', 0)
            ->where(function ($query) use ($term) {
                $query->where('title', 'like', '%' . $term . '%')
                    ->orWhere('author', 'like', '%' . $term . '%')
                    ->orWhere('isbn', 'like', '%' . $term . '%');
            })
            ->limit(10)
            ->get(['id', 'title', 'author', 'available_quantity']);

        return response()->json($books);
    }
}
