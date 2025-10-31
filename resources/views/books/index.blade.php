@extends('layouts.app')

@section('title', 'Gerenciar Livros - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Livros</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-book"></i> Gerenciar Livros</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Livro
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('books.index') }}" class="row g-3">
            <div class="col-md-10">
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Buscar por título, autor ou ISBN..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Books Table -->
<div class="card">
    <div class="card-body">
        @if($books->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>ISBN</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>
                                <strong>{{ $book->title }}</strong>
                                @if($book->hasLowStock())
                                    <span class="badge bg-warning text-dark ms-1" title="Estoque baixo">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </span>
                                @endif
                            </td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->isbn ?: '-' }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ $book->publication_year }}</td>
                            <td>
                                <span class="badge bg-info">{{ $book->available_quantity }}/{{ $book->total_quantity }}</span>
                            </td>
                            <td>
                                @if($book->isAvailable())
                                    <span class="badge bg-success">Disponível</span>
                                @else
                                    <span class="badge bg-danger">Indisponível</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('books.edit', $book) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('books.destroy', $book) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $books->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-book display-1 text-muted"></i>
                <h4 class="text-muted mt-3">Nenhum livro encontrado</h4>
                @if(request('search'))
                    <p class="text-muted">Tente ajustar os termos de busca.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Ver todos os livros
                    </a>
                @else
                    <p class="text-muted">Comece cadastrando o primeiro livro.</p>
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Cadastrar Livro
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection