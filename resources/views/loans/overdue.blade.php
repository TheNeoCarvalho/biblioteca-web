@extends('layouts.app')

@section('title', 'Empréstimos em Atraso - Sistema de Biblioteca')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-exclamation-triangle text-warning"></i> Empréstimos em Atraso</h1>
    <div>
        <a href="{{ route('loans.index') }}" class="btn btn-outline-primary me-2">
            <i class="bi bi-arrow-left-right"></i> Empréstimos Ativos
        </a>
        <a href="{{ route('loans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Empréstimo
        </a>
    </div>
</div>

@if($loans->count() > 0)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        <strong>Atenção!</strong> Existem {{ $loans->count() }} empréstimo(s) em atraso que precisam de atenção.
    </div>
@endif

<!-- Overdue Loans Table -->
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
                            <th>Dias de Atraso</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr class="table-danger">
                            <td>
                                <strong>{{ $loan->student->name }}</strong><br>
                                <small class="text-muted">
                                    {{ $loan->student->registration }}<br>
                                    <i class="bi bi-envelope"></i> {{ $loan->student->email }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $loan->book->title }}</strong><br>
                                <small class="text-muted">{{ $loan->book->author }}</small>
                            </td>
                            <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    {{ $loan->getDaysOverdue() }} dia(s)
                                </span>
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
                                          class="d-inline"
                                          onsubmit="return confirm('Confirma a devolução deste livro em atraso?')">
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

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <strong>{{ $loans->count() }}</strong><br>
                                <small>Empréstimos em Atraso</small>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ $loans->sum(function($loan) { return $loan->getDaysOverdue(); }) }}</strong><br>
                                <small>Total de Dias em Atraso</small>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ number_format($loans->avg(function($loan) { return $loan->getDaysOverdue(); }), 1) }}</strong><br>
                                <small>Média de Dias em Atraso</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-3">
                <div class="col-md-12 text-center">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-warning" onclick="printOverdueList()">
                            <i class="bi bi-printer"></i> Imprimir Lista
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="exportOverdueList()">
                            <i class="bi bi-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-check-circle display-1 text-success"></i>
                <h4 class="text-success mt-3">Nenhum empréstimo em atraso!</h4>
                <p class="text-muted">Todos os empréstimos estão dentro do prazo ou já foram devolvidos.</p>
                <div class="mt-3">
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-arrow-left-right"></i> Ver Empréstimos Ativos
                    </a>
                    <a href="{{ route('loans.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Novo Empréstimo
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function printOverdueList() {
    window.print();
}

function exportOverdueList() {
    // Implementar exportação se necessário
    alert('Funcionalidade de exportação será implementada em breve.');
}
</script>
@endpush