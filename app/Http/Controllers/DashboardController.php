<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe o painel principal com estatísticas.
     */
    public function index()
    {
        // Calcular estatísticas gerais
        $totalStudents = Student::count();
        $totalBooks = Book::sum('total_quantity');
        $activeLoans = Loan::active()->count();
        $overdueLoans = Loan::overdue()->count();
        
        // Livros com estoque baixo
        $lowStockBooks = Book::where('available_quantity', '<', 2)->count();
        
        // Empréstimos em atraso com detalhes
        $overdueLoansDetails = Loan::overdue()
            ->with(['student', 'book'])
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Top 10 alunos que mais leram (empréstimos devolvidos)
        $topReaders = Student::select('students.id', 'students.name', 'students.course', 'students.grade')
            ->join('loans', 'students.id', '=', 'loans.student_id')
            ->where('loans.is_returned', true)
            ->groupBy('students.id', 'students.name', 'students.course', 'students.grade')
            ->selectRaw('COUNT(loans.id) as books_read')
            ->orderByDesc('books_read')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'totalBooks',
            'activeLoans',
            'overdueLoans',
            'lowStockBooks',
            'overdueLoansDetails',
            'topReaders'
        ));
    }
}