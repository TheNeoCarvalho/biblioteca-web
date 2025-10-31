@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Biblioteca')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-house"></i> Dashboard</h1>
            <small class="text-muted">Bem-vindo, {{ Auth::user()->name }}!</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($totalStudents) }}</h4>
                        <p class="card-text">Alunos Cadastrados</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($totalBooks) }}</h4>
                        <p class="card-text">Livros no Acervo</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-book" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($activeLoans) }}</h4>
                        <p class="card-text">Empréstimos Ativos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-arrow-left-right" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($overdueLoans) }}</h4>
                        <p class="card-text">Empréstimos em Atraso</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-speedometer2"></i> Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('students.create') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-person-plus"></i><br>
                            Cadastrar Aluno
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('books.create') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-book-half"></i><br>
                            Cadastrar Livro
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('loans.create') }}" class="btn btn-outline-warning w-100">
                            <i class="bi bi-plus-circle"></i><br>
                            Novo Empréstimo
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-arrow-return-left"></i><br>
                            Registrar Devolução
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

@if($overdueLoans > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5><i class="bi bi-exclamation-triangle"></i> Empréstimos em Atraso ({{ $overdueLoans }})</h5>
            </div>
            <div class="card-body">
                @if($overdueLoansDetails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Aluno</th>
                                    <th>Livro</th>
                                    <th>Data de Devolução</th>
                                    <th>Dias de Atraso</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueLoansDetails as $loan)
                                <tr>
                                    <td>{{ $loan->student->name }}</td>
                                    <td>{{ $loan->book->title }}</td>
                                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-danger">
                                            {{ $loan->getDaysOverdue() }} dias
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-primary">
                                            Ver Detalhes
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($overdueLoans > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('loans.index') }}" class="btn btn-outline-danger">
                                Ver todos os {{ $overdueLoans }} empréstimos em atraso
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endif


<!-- Top 10 Alunos que Mais Leram -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5><i class="bi bi-trophy"></i> Top 10 Alunos leitores</h5>
            </div>
            <div class="card-body">
                @if($topReaders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th width="10%">Posição</th>
                                    <th width="40%">Nome do Aluno</th>
                                    <th width="25%">Curso</th>
                                    <th width="15%">Série</th>
                                    <th width="10%">Livros Lidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReaders as $index => $reader)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-trophy-fill"></i> {{ $index + 1 }}º
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-award-fill"></i> {{ $index + 1 }}º
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge bg-warning">
                                                <i class="bi bi-award"></i> {{ $index + 1 }}º
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark">{{ $index + 1 }}º</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $reader->name }}</strong>
                                    </td>
                                    <td>{{ $reader->course }}</td>
                                    <td>{{ $reader->grade }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $reader->books_read }} livro{{ $reader->books_read != 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Ainda não há dados de leitura para exibir.</p>
                        <p class="text-muted">Os alunos aparecerão aqui após devolverem seus primeiros livros.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 
@if($lowStockBooks > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5><i class="bi bi-exclamation-circle"></i> Alerta de Estoque Baixo</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>{{ $lowStockBooks }}</strong> livro(s) com estoque baixo (menos de 2 unidades disponíveis).
                </p>
                <a href="{{ route('books.index') }}" class="btn btn-outline-warning">
                    <i class="bi bi-eye"></i> Verificar Estoque
                </a>
            </div>
        </div>
    </div>
</div>
@endif -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show welcome notification on dashboard
    if (window.notifications) {
        // Check if it's the first visit today
        const today = new Date().toDateString();
        const lastVisit = localStorage.getItem('lastDashboardVisit');
        
        if (lastVisit !== today) {
            setTimeout(() => {
                window.notifications.info('Bem-vindo ao Sistema de Biblioteca! Tenha um ótimo dia de trabalho.', {
                    duration: 4000
                });
                localStorage.setItem('lastDashboardVisit', today);
            }, 1000);
        }
        
        // Show alerts for overdue loans
        @if($overdueLoans > 0)
            setTimeout(() => {
                window.notifications.warning('Atenção: Existem {{ $overdueLoans }} empréstimo(s) em atraso que precisam de atenção.', {
                    duration: 6000
                });
            }, 2000);
        @endif
        
        // Show alert for low stock books if any
        @if(isset($lowStockBooks) && $lowStockBooks > 0)
            setTimeout(() => {
                window.notifications.warning('Alerta: {{ $lowStockBooks }} livro(s) com estoque baixo.', {
                    duration: 5000
                });
            }, 3000);
        @endif
    }
});
</script>
@endpush