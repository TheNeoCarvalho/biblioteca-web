@extends('layouts.app')

@section('title', 'Gerenciar Alunos - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item active" aria-current="page">Alunos</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-people"></i> Gerenciar Alunos</h1>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Novo Aluno
    </a>
</div>

<!-- Search Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('students.index') }}" class="row g-3">
            <div class="col-md-10">
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       placeholder="Buscar por nome ou matrícula..." 
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

<!-- Students Table -->
<div class="card">
    <div class="card-body">
        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Matrícula</th>
                            <th>Curso</th>
                            <th>Série</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->registration }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->grade }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('students.edit', $student) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" 
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
                {{ $students->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="text-muted mt-3">Nenhum aluno encontrado</h4>
                @if(request('search'))
                    <p class="text-muted">Tente ajustar os termos de busca.</p>
                    <a href="{{ route('students.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Ver todos os alunos
                    </a>
                @else
                    <p class="text-muted">Comece cadastrando o primeiro aluno.</p>
                    <a href="{{ route('students.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Cadastrar Aluno
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection