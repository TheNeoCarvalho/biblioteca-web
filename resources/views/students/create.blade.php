@extends('layouts.app')

@section('title', 'Cadastrar Aluno - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('students.index') }}" class="text-decoration-none">Alunos</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Cadastrar</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus"></i> Cadastrar Novo Aluno
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">Nome Completo *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="registration" class="form-label">Matrícula *</label>
                            <input type="text" 
                                   class="form-control @error('registration') is-invalid @enderror" 
                                   id="registration" 
                                   name="registration" 
                                   value="{{ old('registration') }}" 
                                   required>
                            @error('registration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course" class="form-label">Curso *</label>
                            <input type="text" 
                                   class="form-control @error('course') is-invalid @enderror" 
                                   id="course" 
                                   name="course" 
                                   value="{{ old('course') }}" 
                                   required>
                            @error('course')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="grade" class="form-label">Série *</label>
                            <input type="text" 
                                   class="form-control @error('grade') is-invalid @enderror" 
                                   id="grade" 
                                   name="grade" 
                                   value="{{ old('grade') }}" 
                                   placeholder="Ex: 1º Ano, 2º Ano, etc." 
                                   required>
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Cadastrar Aluno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection