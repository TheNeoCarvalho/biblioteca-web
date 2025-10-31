@extends('layouts.app')

@section('title', 'Histórico de Empréstimos - Sistema de Biblioteca')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clock-history"></i> Histórico de Empréstimos</h1>
    <div>
        <a href="{{ route('loans.index') }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-arrow-left-right"></i> Empréstimos Ativos
        </a>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Empréstimo
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('loans.history') }}" class="row g-3">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Data Inicial</label>
                <input type="date" 
                       class="form-control" 
                       id="start_date"
                       name="start_date" 
                       value="{{ $filters['start_date'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">Data Final</label>
                <input type="date" 
                       class="form-control" 
                       id="end_date"
                       name="end_date" 
                       value="{{ $filters['end_date'] ?? '' }}">
            </div>
            <div class="col-md-4">
                <label for="student_name" class="form-label">Nome do Aluno</label>
                <input type="text" 
                       class="form-control" 
                       id="student_name"
                       name="student_name" 
                       placeholder="Buscar por nome do aluno..." 
                       value="{{ $filters['student_name'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-outline-primary w-100 d-block">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- History Table -->
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
                            <th>Data Devolução Prevista</th>
                            <th>Data Devolução Real</th>
                            <th>Status</th>
                            <th width="80">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
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
                                @if($loan->return_date)
                                    {{ $loan->return_date->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->is_returned)
                                    @if($loan->wasReturnedLate())
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i>
                                            Devolvido com atraso
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i>
                                            Devolvido no prazo
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-question-circle"></i>
                                        Status indefinido
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('loans.show', $loan) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Statistics -->
            @if($loans->count() > 0)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <strong>{{ $loans->count() }}</strong><br>
                                    <small>Total de Empréstimos</small>
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ $loans->filter(function($loan) { return $loan->is_returned && !$loan->wasReturnedLate(); })->count() }}</strong><br>
                                    <small>Devolvidos no Prazo</small>
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ $loans->filter(function($loan) { return $loan->wasReturnedLate(); })->count() }}</strong><br>
                                    <small>Devolvidos com Atraso</small>
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ $loans->where('is_returned', false)->count() }}</strong><br>
                                    <small>Ainda Ativos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <i class="bi bi-clock-history display-1 text-muted"></i>
                <h4 class="text-muted mt-3">Nenhum empréstimo encontrado no histórico</h4>
                @if(array_filter($filters ?? []))
                    <p class="text-muted">Tente ajustar os filtros de busca.</p>
                    <a href="{{ route('loans.history') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Ver todo o histórico
                    </a>
                @else
                    <p class="text-muted">Ainda não há empréstimos finalizados.</p>
                    <a href="{{ route('loans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Registrar Primeiro Empréstimo
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection