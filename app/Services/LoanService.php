<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class LoanService
{
    /**
     * Create a new loan for a student and book.
     *
     * @param Student $student
     * @param Book $book
     * @return Loan
     * @throws Exception
     */
    public function createLoan(Student $student, Book $book): Loan
    {
        // Verificar se o livro está disponível
        if (!$book->isAvailable()) {
            throw new Exception('Livro não disponível para empréstimo.');
        }

        // Verificar se o aluno já tem um empréstimo ativo deste livro
        $existingLoan = Loan::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->where('is_returned', false)
            ->first();

        if ($existingLoan) {
            throw new Exception('Aluno já possui um empréstimo ativo deste livro.');
        }

        $loanDate = Carbon::now();
        $dueDate = $this->calculateDueDate($loanDate);

        // Criar o empréstimo
        $loan = Loan::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'is_returned' => false,
        ]);

        // Decrementar a quantidade disponível do livro
        $book->decrement('available_quantity');

        return $loan;
    }

    /**
     * Return a book and update the loan record.
     *
     * @param Loan $loan
     * @return bool
     * @throws Exception
     */
    public function returnBook(Loan $loan): bool
    {
        // Verificar se o empréstimo já foi devolvido
        if ($loan->is_returned) {
            throw new Exception('Este empréstimo já foi devolvido.');
        }

        // Atualizar o registro do empréstimo
        $loan->update([
            'return_date' => Carbon::now(),
            'is_returned' => true,
        ]);

        // Incrementar a quantidade disponível do livro
        $loan->book->increment('available_quantity');

        return true;
    }

    /**
     * Get all overdue loans.
     *
     * @return Collection
     */
    public function getOverdueLoans(): Collection
    {
        return Loan::overdue()
            ->with(['student', 'book'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    /**
     * Calculate the due date for a loan (15 days from loan date).
     *
     * @param Carbon $loanDate
     * @return Carbon
     */
    public function calculateDueDate(Carbon $loanDate): Carbon
    {
        return $loanDate->copy()->addDays(15);
    }

    /**
     * Get active loans with optional filters.
     *
     * @param array $filters
     * @return Collection
     */
    public function getActiveLoans(array $filters = []): Collection
    {
        $query = Loan::active()->with(['student', 'book']);

        // Filtro por aluno
        if (!empty($filters['student_name'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['student_name'] . '%');
            });
        }

        // Filtro por livro
        if (!empty($filters['book_title'])) {
            $query->whereHas('book', function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['book_title'] . '%');
            });
        }

        return $query->orderBy('due_date', 'asc')->get();
    }

    /**
     * Get loan history with optional filters.
     *
     * @param array $filters
     * @return Collection
     */
    public function getLoanHistory(array $filters = []): Collection
    {
        $query = Loan::returned()->with(['student', 'book']);

        // Filtro por período
        if (!empty($filters['start_date'])) {
            $query->where('loan_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('loan_date', '<=', $filters['end_date']);
        }

        // Filtro por aluno
        if (!empty($filters['student_name'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['student_name'] . '%');
            });
        }

        return $query->orderBy('return_date', 'desc')->get();
    }

    /**
     * Get statistics for the dashboard.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $activeLoans = Loan::active()->count();
        $overdueLoans = Loan::overdue()->count();
        $totalLoans = Loan::count();
        $returnedLoans = Loan::returned()->count();

        return [
            'active_loans' => $activeLoans,
            'overdue_loans' => $overdueLoans,
            'total_loans' => $totalLoans,
            'returned_loans' => $returnedLoans,
        ];
    }
}