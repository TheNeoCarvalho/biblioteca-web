@extends('layouts.app')

@section('title', 'Cadastrar Livro - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('books.index') }}" class="text-decoration-none">Livros</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-book-half"></i> Cadastrar Novo Livro
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Título *</label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
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
                                   value="{{ old('author') }}" 
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
                                   value="{{ old('isbn') }}" 
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
                                   value="{{ old('publisher') }}" 
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
                                   value="{{ old('publication_year') }}" 
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
                                   value="{{ old('total_quantity', 1) }}" 
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
                                   value="{{ old('available_quantity', 1) }}" 
                                   min="0" 
                                   required>
                            @error('available_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Deve ser menor ou igual à quantidade total.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Cadastrar Livro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection