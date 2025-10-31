@extends('layouts.app')

@section('title', 'Editar Livro - Sistema de Biblioteca')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-pencil"></i> Editar Livro: {{ $book->title }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('books.update', $book) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $book->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="author" class="form-label">Autor *</label>
                            <input type="text" 
                                   class="form-control @error('author') is-invalid @enderror" 
                                   id="author" 
                                   name="author" 
                                   value="{{ old('author', $book->author) }}" 
                                   required>
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" 
                                   class="form-control @error('isbn') is-invalid @enderror" 
                                   id="isbn" 
                                   name="isbn" 
                                   value="{{ old('isbn', $book->isbn) }}" 
                                   placeholder="Ex: 978-85-123-4567-8">
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="publisher" class="form-label">Editora *</label>
                            <input type="text" 
                                   class="form-control @error('publisher') is-invalid @enderror" 
                                   id="publisher" 
                                   name="publisher" 
                                   value="{{ old('publisher', $book->publisher) }}" 
                                   required>
                            @error('publisher')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="publication_year" class="form-label">Ano de Publicação *</label>
                            <input type="number" 
                                   class="form-control @error('publication_year') is-invalid @enderror" 
                                   id="publication_year" 
                                   name="publication_year" 
                                   value="{{ old('publication_year', $book->publication_year) }}" 
                                   min="1000" 
                                   max="{{ date('Y') + 1 }}" 
                                   required>
                            @error('publication_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="total_quantity" class="form-label">Quantidade Total *</label>
                            <input type="number" 
                                   class="form-control @error('total_quantity') is-invalid @enderror" 
                                   id="total_quantity" 
                                   name="total_quantity" 
                                   value="{{ old('total_quantity', $book->total_quantity) }}" 
                                   min="1" 
                                   required>
                            @error('total_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="available_quantity" class="form-label">Quantidade Disponível *</label>
                            <input type="number" 
                                   class="form-control @error('available_quantity') is-invalid @enderror" 
                                   id="available_quantity" 
                                   name="available_quantity" 
                                   value="{{ old('available_quantity', $book->available_quantity) }}" 
                                   min="0" 
                                   max="{{ $book->total_quantity }}"
                                   required>
                            @error('available_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Deve ser menor ou igual à quantidade total.
                                <br><small class="text-muted">Livros emprestados: {{ $book->getBooksOnLoan() }}</small>
                            </div>
                        </div>
                    </div>

                    @if($book->hasLowStock())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Atenção:</strong> Este livro está com estoque baixo (menos de 2 unidades disponíveis).
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Atualizar Livro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection