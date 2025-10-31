@extends('layouts.app')

@section('title', 'Empréstimos Ativos - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Empréstimos Ativos</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-arrow-left-right"></i> Empréstimos Ativos</h1>
    <div>
        <a href="{{ route('loans.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-circle"></i> Novo Empréstimo
        </a>
        <div class="btn-group" role="group">
            <a href="{{ route('loans.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-clock-history"></i> Histórico
            </a>
            <a href="{{ route('loans.overdue') }}" class="btn btn-outline-warning">
                <i class="bi bi-exclamation-triangle"></i> Em Atraso
            </a>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('loans.index') }}" class="row g-3">
            <div class="col-md-5">
                <input type="text" 
                       class="form-control" 
                       name="student_name" 
                       placeholder="Buscar por nome do aluno..." 
                       value="{{ $filters['student_name'] ?? '' }}">
            </div>
            <div class="col-md-5">
                <input type="text" 
                       class="form-control" 
                       name="book_title" 
                       placeholder="Buscar por título do livro..." 
                       value="{{ $filters['book_title'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loans Table -->
<div class="card">
    <div class="card-body">
        @if($loans->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Livro</th>
                            <th>Data Empréstimo</th>
                            <th>Data Devolução</th>
                            <th>Status</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr class="{{ $loan->isOverdue() ? 'table-warning' : '' }}">
                            <td>
                                <strong>{{ $loan->student->name }}</strong><br>
                                <small class="text-muted">{{ $loan->student->registration }}</small>
                            </td>
                            <td>
                                <strong>{{ $loan->book->title }}</strong><br>
                                <small class="text-muted">{{ $loan->book->author }}</small>
                            </td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td>
                                @if($loan->isOverdue())
                                    <span class="badge bg-danger">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ $loan->getDaysOverdue() }} dia(s) de atraso
                                    </span>
                                @else
                                    @php
                                        $daysUntilDue = $loan->getDaysUntilDue();
                                    @endphp
                                    @if($daysUntilDue <= 3)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>
                                            {{ $daysUntilDue }} dia(s) restante(s)
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            {{ $daysUntilDue }} dia(s) restante(s)
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('loans.show', $loan) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('loans.return', $loan) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-success" 
                                                title="Registrar devolução">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                <h4 class="text-muted mt-3">Nenhum empréstimo ativo encontrado</h4>
                @if(array_filter($filters ?? []))
                    <p class="text-muted">Tente ajustar os termos de busca.</p>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Ver todos os empréstimos
                    </a>
                @else
                    <p class="text-muted">Comece registrando o primeiro empréstimo.</p>
                    <a href="{{ route('loans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Empréstimo
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection