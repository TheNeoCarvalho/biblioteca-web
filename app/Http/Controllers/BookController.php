<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        $books = $query->orderBy('title')->paginate(10);

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        try {
            $book = Book::create($request->validated());
            
            $message = "Livro '{$book->title}' cadastrado com sucesso!";
            $infoMessage = "Adicionado ao acervo com {$book->total_quantity} exemplar(es) disponível(is).";
            
            if ($book->hasLowStock()) {
                $warningMessage = 'Atenção: Este livro foi cadastrado com estoque baixo (menos de 2 unidades).';
                return redirect()->route('books.index')
                    ->with('success', $message)
                    ->with('info', $infoMessage)
                    ->with('warning', $warningMessage);
            }
            
            return redirect()->route('books.index')
                ->with('success', $message)
                ->with('info', $infoMessage);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar livro. Verifique os dados e tente novamente.')
                ->withErrors(['general' => 'Certifique-se de que o ISBN não está duplicado.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        try {
            $oldTitle = $book->title;
            $oldQuantity = $book->total_quantity;
            
            $book->update($request->validated());
            
            $message = "Livro '{$book->title}' atualizado com sucesso!";
            
            // Check for significant changes
            $changes = [];
            if ($oldTitle !== $book->title) {
                $changes[] = "título alterado de '{$oldTitle}' para '{$book->title}'";
            }
            if ($oldQuantity !== $book->total_quantity) {
                $diff = $book->total_quantity - $oldQuantity;
                $changes[] = $diff > 0 
                    ? "adicionados {$diff} exemplar(es) ao estoque"
                    : "removidos " . abs($diff) . " exemplar(es) do estoque";
            }
            
            $infoMessage = !empty($changes) 
                ? 'Alterações: ' . implode(', ', $changes) . '.'
                : 'Dados do livro atualizados.';
            
            $response = redirect()->route('books.index')
                ->with('success', $message)
                ->with('info', $infoMessage);
            
            if ($book->hasLowStock()) {
                $response->with('warning', 'Atenção: Este livro está com estoque baixo (menos de 2 unidades disponíveis).');
            }
            
            return $response;
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar livro. Verifique os dados e tente novamente.')
                ->withErrors(['general' => 'Certifique-se de que a quantidade disponível não excede a quantidade total.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            // Check if book has active loans before deleting
            $activeLoans = $book->loans()->where('is_returned', false)->count();
            
            if ($activeLoans > 0) {
                return back()->with('error', "Não é possível excluir o livro '{$book->title}' pois ele possui {$activeLoans} empréstimo(s) ativo(s).")
                    ->with('warning', 'Aguarde a devolução de todos os exemplares antes de excluir o livro.');
            }

            // Check if book has loan history
            $totalLoans = $book->loans()->count();
            $bookTitle = $book->title;
            
            $book->delete();
            
            $message = "Livro '{$bookTitle}' excluído com sucesso!";
            $infoMessage = $totalLoans > 0 
                ? "O livro teve {$totalLoans} empréstimo(s) durante seu período no acervo."
                : 'Livro removido do acervo sem histórico de empréstimos.';
            
            return redirect()->route('books.index')
                ->with('success', $message)
                ->with('info', $infoMessage);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir livro. Tente novamente.')
                ->withErrors(['general' => 'Se o problema persistir, entre em contato com o suporte.']);
        }
    }
}
