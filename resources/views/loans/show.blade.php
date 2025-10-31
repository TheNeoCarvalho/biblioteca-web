@extends('layouts.app')

@section('title', 'Detalhes do Empréstimo - Sistema de Biblioteca')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-eye"></i> Detalhes do Empréstimo</h1>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Informações do Empréstimo</h5>
                @if(!$loan->is_returned)
                    <form action="{{ route('loans.return', $loan) }}" 
                          method="POST" 
                          class="d-inline"
                          onsubmit="return confirm('Confirma a devolução deste livro?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check2"></i> Registrar Devolução
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Aluno</h6>
                        <p class="mb-3">
                            <strong>{{ $loan->student->name }}</strong><br>
                            <small class="text-muted">
                                Matrícula: {{ $loan->student->registration }}<br>
                                Curso: {{ $loan->student->course }}<br>
                                Email: {{ $loan->student->email }}
                            </small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Livro</h6>
                        <p class="mb-3">
                            <strong>{{ $loan->book->title }}</strong><br>
                            <small class="text-muted">
                                Autor: {{ $loan->book->author }}<br>
                                @if($loan->book->isbn)
                                    ISBN: {{ $loan->book->isbn }}<br>
                                @endif
                                Editora: {{ $loan->book->publisher }}
                            </small>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-muted">Data do Empréstimo</h6>
                        <p class="mb-3">
                            <i class="bi bi-calendar-event"></i>
                            {{ $loan->loan_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Data de Devolução Prevista</h6>
                        <p class="mb-3">
                            <i class="bi bi-calendar-check"></i>
                            {{ $loan->due_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        @if($loan->is_returned)
                            <h6 class="text-muted">Data de Devolução</h6>
                            <p class="mb-3">
                                <i class="bi bi-check-circle text-success"></i>
                                {{ $loan->return_date->format('d/m/Y') }}
                            </p>
                        @else
                            <h6 class="text-muted">Status</h6>
                            <p class="mb-3">
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
                            </p>
                        @endif
                    </div>
                </div>

                @if($loan->is_returned)
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i>
                        <strong>Livro devolvido com sucesso!</strong>
                        @if($loan->wasReturnedLate())
                            <br><small>Observação: O livro foi devolvido com atraso.</small>
                        @endif
                    </div>
                @elseif($loan->isOverdue())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Empréstimo em atraso!</strong>
                        Este livro deveria ter sido devolvido em {{ $loan->due_date->format('d/m/Y') }}.
                    </div>
                @elseif($loan->getDaysUntilDue() <= 3)
                    <div class="alert alert-warning">
                        <i class="bi bi-clock"></i>
                        <strong>Prazo próximo do vencimento!</strong>
                        Este livro deve ser devolvido em {{ $loan->due_date->format('d/m/Y') }}.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection