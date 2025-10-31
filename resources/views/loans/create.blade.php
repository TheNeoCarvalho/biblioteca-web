@extends('layouts.app')

@section('title', 'Novo Empréstimo - Sistema de Biblioteca')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('loans.index') }}" class="text-decoration-none">Empréstimos</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Novo Empréstimo</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-plus-circle"></i> Novo Empréstimo</h1>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    
                    <!-- Student Search Section -->
                    <div class="loan-search-section">
                        <label class="form-label">
                            <i class="bi bi-person-circle me-2"></i>Buscar Aluno *
                        </label>
                        <div class="row">
                            <div class="col-md-8 mb-2">
                                <div class="input-group search-input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="student_search" 
                                           placeholder="Digite o nome, matrícula ou curso do aluno..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-outline-secondary w-100 clear-search-btn" id="clear_student_search">
                                    <i class="bi bi-x-circle"></i> Limpar
                                </button>
                            </div>
                        </div>
                        
                        <select class="form-select enhanced-select @error('student_id') is-invalid @enderror" 
                                id="student_id" 
                                name="student_id" 
                                required
                                size="4"
                                style="min-height: 120px;">
                            <option value="">📋 Selecione um aluno...</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" 
                                        data-name="{{ strtolower($student->name) }}"
                                        data-registration="{{ strtolower($student->registration) }}"
                                        data-course="{{ strtolower($student->course) }}"
                                        {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    👤 {{ $student->name }} - {{ $student->registration }} ({{ $student->course }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="search-results-info">
                            <i class="bi bi-info-circle"></i> Digite para filtrar ou clique diretamente na lista. Use as setas ↑↓ para navegar.
                        </div>
                    </div>

                    <!-- Book Search Section -->
                    <div class="loan-search-section">
                        <label class="form-label">
                            <i class="bi bi-book me-2"></i>Buscar Livro *
                        </label>
                        <div class="row">
                            <div class="col-md-8 mb-2">
                                <div class="input-group search-input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="book_search" 
                                           placeholder="Digite o título, autor ou ISBN do livro..."
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button type="button" class="btn btn-outline-secondary w-100 clear-search-btn" id="clear_book_search">
                                    <i class="bi bi-x-circle"></i> Limpar
                                </button>
                            </div>
                        </div>
                        
                        <select class="form-select enhanced-select @error('book_id') is-invalid @enderror" 
                                id="book_id" 
                                name="book_id" 
                                required
                                size="4"
                                style="min-height: 120px;">
                            <option value="">📚 Selecione um livro...</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" 
                                        data-title="{{ strtolower($book->title) }}"
                                        data-author="{{ strtolower($book->author) }}"
                                        data-isbn="{{ strtolower($book->isbn ?? '') }}"
                                        data-available="{{ $book->available_quantity }}"
                                        {{ old('book_id') == $book->id ? 'selected' : '' }}
                                        {{ $book->available_quantity == 0 ? 'disabled' : '' }}>
                                    @if($book->available_quantity > 0)
                                        ✅ {{ $book->title }} - {{ $book->author }} ({{ $book->available_quantity }} disponível)
                                    @else
                                        ❌ {{ $book->title }} - {{ $book->author }} (Indisponível)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="search-results-info">
                            <i class="bi bi-info-circle"></i> Apenas livros disponíveis podem ser selecionados. Pressione Enter para selecionar o primeiro resultado.
                        </div>
                    </div>

                    <!-- Selection Summary -->
                    <div class="card mb-4" id="selection_summary" style="display: none;">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-check-circle"></i> Resumo da Seleção
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Aluno:</strong>
                                    <div id="selected_student_info" class="text-muted">Nenhum aluno selecionado</div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Livro:</strong>
                                    <div id="selected_book_info" class="text-muted">Nenhum livro selecionado</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Empréstimo -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Informações do empréstimo:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Data de empréstimo: <strong>{{ now()->format('d/m/Y') }}</strong></li>
                            <li>Data de devolução: <strong>{{ now()->addDays(7)->format('d/m/Y') }}</strong></li>
                            <li>Prazo: <strong>7 dias</strong></li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-secondary" disabled>
                            <i class="bi bi-hourglass"></i> Selecione aluno e livro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced search styling for loan form */
.loan-search-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #dee2e6;
}

.loan-search-section .form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.search-input-group {
    position: relative;
}

.search-input-group .input-group-text {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: white;
    border: none;
}

.search-input-group .form-control {
    border-left: none;
    padding-left: 0.5rem;
}

.search-input-group .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.enhanced-select {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.enhanced-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.enhanced-select option {
    padding: 8px 12px;
    border-bottom: 1px solid #f8f9fa;
}

.enhanced-select option:hover {
    background-color: #e9ecef;
}

.enhanced-select option:disabled {
    background-color: #f8f9fa;
    color: #6c757d;
    font-style: italic;
}

.search-results-info {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

.search-results-info i {
    color: #0d6efd;
}

.clear-search-btn {
    transition: all 0.3s ease;
}

.clear-search-btn:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
    transform: translateY(-1px);
}

/* Animation for filtered results */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.enhanced-select option {
    animation: fadeIn 0.2s ease;
}

/* Submit button states */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-success {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
    border: none;
    box-shadow: 0 2px 4px rgba(25, 135, 84, 0.3);
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, #157347 0%, #146c43 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(25, 135, 84, 0.4);
}

/* Selection summary card */
#selection_summary {
    border: 2px solid #198754;
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
    animation: slideInUp 0.3s ease;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .loan-search-section {
        padding: 1rem;
    }
    
    .enhanced-select {
        min-height: 100px !important;
    }
}

/* High contrast mode */
@media (prefers-contrast: high) {
    .loan-search-section {
        border: 2px solid #000;
        background: #fff;
    }
    
    .enhanced-select {
        border: 2px solid #000;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize loan form search functionality
    initializeLoanFormSearch();
});

function initializeLoanFormSearch() {
    const studentSearch = document.getElementById('student_search');
    const studentSelect = document.getElementById('student_id');
    const clearStudentBtn = document.getElementById('clear_student_search');
    
    const bookSearch = document.getElementById('book_search');
    const bookSelect = document.getElementById('book_id');
    const clearBookBtn = document.getElementById('clear_book_search');
    
    // Store original options
    const originalStudentOptions = Array.from(studentSelect.options);
    const originalBookOptions = Array.from(bookSelect.options);
    
    // Student search functionality
    if (studentSearch && studentSelect) {
        studentSearch.addEventListener('input', function() {
            filterStudents(this.value.toLowerCase(), studentSelect, originalStudentOptions);
        });
        
        studentSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                selectFirstVisibleOption(studentSelect);
            }
        });
        
        // Auto-fill search when option is selected
        studentSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const fullText = selectedOption.textContent.replace('👤 ', '');
                const studentName = fullText.split(' - ')[0];
                studentSearch.value = studentName;
                
                // Update selection summary
                updateSelectionSummary('student', fullText);
                
                // Show success notification
                if (window.notifications) {
                    window.notifications.success(`Aluno selecionado: ${studentName}`, { duration: 2000 });
                }
            } else {
                updateSelectionSummary('student', null);
            }
            
            // Always check button state after change
            checkSubmitButtonState();
        });
    }
    
    // Book search functionality
    if (bookSearch && bookSelect) {
        bookSearch.addEventListener('input', function() {
            filterBooks(this.value.toLowerCase(), bookSelect, originalBookOptions);
        });
        
        bookSearch.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                selectFirstVisibleOption(bookSelect);
            }
        });
        
        // Auto-fill search when option is selected
        bookSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const fullText = selectedOption.textContent.replace(/^[✅❌] /, '');
                const bookTitle = fullText.split(' - ')[0];
                bookSearch.value = bookTitle;
                
                // Update selection summary
                updateSelectionSummary('book', fullText);
                
                // Check availability and show notification
                const available = selectedOption.getAttribute('data-available');
                if (window.notifications) {
                    if (parseInt(available) > 0) {
                        window.notifications.success(`Livro selecionado: ${bookTitle} (${available} disponível)`, { duration: 2000 });
                    } else {
                        window.notifications.warning(`Livro indisponível: ${bookTitle}`, { duration: 3000 });
                    }
                }
            } else {
                updateSelectionSummary('book', null);
            }
            
            // Always check button state after change
            checkSubmitButtonState();
        });
    }
    
    // Clear buttons functionality
    if (clearStudentBtn) {
        clearStudentBtn.addEventListener('click', function() {
            studentSearch.value = '';
            studentSelect.selectedIndex = 0;
            filterStudents('', studentSelect, originalStudentOptions);
            updateSelectionSummary('student', null);
            studentSearch.focus();
        });
    }
    
    if (clearBookBtn) {
        clearBookBtn.addEventListener('click', function() {
            bookSearch.value = '';
            bookSelect.selectedIndex = 0;
            filterBooks('', bookSelect, originalBookOptions);
            updateSelectionSummary('book', null);
            bookSearch.focus();
        });
    }
    
    // Add keyboard navigation
    addKeyboardNavigation(studentSearch, studentSelect);
    addKeyboardNavigation(bookSearch, bookSelect);
    
    // Initialize selection summary for pre-selected values
    initializePreselectedValues(studentSelect, bookSelect);
    
    // Add additional listeners to ensure button state is always checked
    if (studentSelect) {
        studentSelect.addEventListener('input', checkSubmitButtonState);
        studentSelect.addEventListener('blur', checkSubmitButtonState);
    }
    
    if (bookSelect) {
        bookSelect.addEventListener('input', checkSubmitButtonState);
        bookSelect.addEventListener('blur', checkSubmitButtonState);
    }
}

function filterStudents(searchTerm, selectElement, originalOptions) {
    // Clear current options except the first one
    selectElement.innerHTML = '';
    selectElement.appendChild(originalOptions[0].cloneNode(true));
    
    if (!searchTerm) {
        // Show all options if search is empty
        originalOptions.slice(1).forEach(option => {
            selectElement.appendChild(option.cloneNode(true));
        });
        updateSearchInfo('student', originalOptions.length - 1, originalOptions.length - 1);
        return;
    }
    
    // Filter and add matching options
    let matchCount = 0;
    originalOptions.slice(1).forEach(option => {
        const name = option.getAttribute('data-name') || '';
        const registration = option.getAttribute('data-registration') || '';
        const course = option.getAttribute('data-course') || '';
        
        if (name.includes(searchTerm) || 
            registration.includes(searchTerm) || 
            course.includes(searchTerm)) {
            selectElement.appendChild(option.cloneNode(true));
            matchCount++;
        }
    });
    
    // Show "no results" message if no matches
    if (matchCount === 0) {
        const noResultOption = document.createElement('option');
        noResultOption.textContent = '🔍 Nenhum aluno encontrado para "' + searchTerm + '"';
        noResultOption.disabled = true;
        noResultOption.style.fontStyle = 'italic';
        selectElement.appendChild(noResultOption);
    }
    
    updateSearchInfo('student', matchCount, originalOptions.length - 1);
}

function filterBooks(searchTerm, selectElement, originalOptions) {
    // Clear current options except the first one
    selectElement.innerHTML = '';
    selectElement.appendChild(originalOptions[0].cloneNode(true));
    
    if (!searchTerm) {
        // Show all options if search is empty
        originalOptions.slice(1).forEach(option => {
            selectElement.appendChild(option.cloneNode(true));
        });
        updateSearchInfo('book', originalOptions.length - 1, originalOptions.length - 1);
        return;
    }
    
    // Filter and add matching options
    let matchCount = 0;
    let availableCount = 0;
    originalOptions.slice(1).forEach(option => {
        const title = option.getAttribute('data-title') || '';
        const author = option.getAttribute('data-author') || '';
        const isbn = option.getAttribute('data-isbn') || '';
        
        if (title.includes(searchTerm) || 
            author.includes(searchTerm) || 
            isbn.includes(searchTerm)) {
            selectElement.appendChild(option.cloneNode(true));
            matchCount++;
            
            // Count available books
            const available = parseInt(option.getAttribute('data-available') || '0');
            if (available > 0) {
                availableCount++;
            }
        }
    });
    
    // Show "no results" message if no matches
    if (matchCount === 0) {
        const noResultOption = document.createElement('option');
        noResultOption.textContent = '🔍 Nenhum livro encontrado para "' + searchTerm + '"';
        noResultOption.disabled = true;
        noResultOption.style.fontStyle = 'italic';
        selectElement.appendChild(noResultOption);
    }
    
    updateSearchInfo('book', matchCount, originalOptions.length - 1, availableCount);
}

function selectFirstVisibleOption(selectElement) {
    // Select the first non-disabled option (skip the placeholder)
    for (let i = 1; i < selectElement.options.length; i++) {
        if (!selectElement.options[i].disabled) {
            selectElement.selectedIndex = i;
            selectElement.dispatchEvent(new Event('change'));
            // Force button state check
            setTimeout(() => checkSubmitButtonState(), 50);
            break;
        }
    }
}

function addKeyboardNavigation(searchInput, selectElement) {
    searchInput.addEventListener('keydown', function(e) {
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectElement.focus();
                if (selectElement.selectedIndex < selectElement.options.length - 1) {
                    selectElement.selectedIndex++;
                }
                break;
            case 'ArrowUp':
                e.preventDefault();
                selectElement.focus();
                if (selectElement.selectedIndex > 0) {
                    selectElement.selectedIndex--;
                }
                break;
            case 'Escape':
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input'));
                break;
        }
    });
    
    selectElement.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchInput.focus();
        }
    });
}

// Update selection summary
function updateSelectionSummary(type, selectionText) {
    const summaryCard = document.getElementById('selection_summary');
    const studentInfo = document.getElementById('selected_student_info');
    const bookInfo = document.getElementById('selected_book_info');
    const studentSelect = document.getElementById('student_id');
    const bookSelect = document.getElementById('book_id');
    
    if (type === 'student') {
        if (selectionText) {
            studentInfo.innerHTML = `<i class="bi bi-person-check text-success"></i> ${selectionText}`;
            studentInfo.className = 'text-success';
        } else {
            studentInfo.innerHTML = '<i class="bi bi-person text-muted"></i> Nenhum aluno selecionado';
            studentInfo.className = 'text-muted';
        }
    } else if (type === 'book') {
        if (selectionText) {
            bookInfo.innerHTML = `<i class="bi bi-book-fill text-success"></i> ${selectionText}`;
            bookInfo.className = 'text-success';
        } else {
            bookInfo.innerHTML = '<i class="bi bi-book text-muted"></i> Nenhum livro selecionado';
            bookInfo.className = 'text-muted';
        }
    }
    
    // Show/hide summary card based on actual select values
    const hasStudentSelection = studentSelect.value && studentSelect.value !== '';
    const hasBookSelection = bookSelect.value && bookSelect.value !== '';
    
    if (hasStudentSelection || hasBookSelection) {
        summaryCard.style.display = 'block';
        summaryCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        summaryCard.style.display = 'none';
    }
}

// Update search information display
function updateSearchInfo(type, matchCount, totalCount, availableCount = null) {
    const infoElement = document.querySelector(`.loan-search-section:has(#${type}_search) .search-results-info`);
    if (!infoElement) return;
    
    let message = '';
    if (type === 'student') {
        if (matchCount === totalCount) {
            message = `<i class="bi bi-people"></i> Mostrando todos os ${totalCount} alunos cadastrados`;
        } else if (matchCount > 0) {
            message = `<i class="bi bi-funnel"></i> ${matchCount} de ${totalCount} alunos encontrados`;
        } else {
            message = `<i class="bi bi-exclamation-circle text-warning"></i> Nenhum aluno encontrado. Tente termos diferentes.`;
        }
    } else if (type === 'book') {
        if (matchCount === totalCount) {
            message = `<i class="bi bi-books"></i> Mostrando todos os ${totalCount} livros do acervo`;
            if (availableCount !== null) {
                message += ` (${availableCount} disponíveis)`;
            }
        } else if (matchCount > 0) {
            message = `<i class="bi bi-funnel"></i> ${matchCount} de ${totalCount} livros encontrados`;
            if (availableCount !== null) {
                message += ` (${availableCount} disponíveis)`;
            }
        } else {
            message = `<i class="bi bi-exclamation-circle text-warning"></i> Nenhum livro encontrado. Tente termos diferentes.`;
        }
    }
    
    infoElement.innerHTML = message;
}

// Enhanced form validation for loan creation
function validateLoanForm() {
    const studentSelect = document.getElementById('student_id');
    const bookSelect = document.getElementById('book_id');
    let isValid = true;
    
    // Validate student selection
    if (!studentSelect.value) {
        if (window.formNotifications) {
            window.formNotifications.showFieldError(studentSelect, 'Selecione um aluno');
        }
        isValid = false;
    }
    
    // Validate book selection
    if (!bookSelect.value) {
        if (window.formNotifications) {
            window.formNotifications.showFieldError(bookSelect, 'Selecione um livro');
        }
        isValid = false;
    } else {
        // Check if selected book is available
        const selectedOption = bookSelect.options[bookSelect.selectedIndex];
        const available = parseInt(selectedOption.getAttribute('data-available') || '0');
        
        if (available === 0) {
            if (window.formNotifications) {
                window.formNotifications.showFieldError(bookSelect, 'Este livro não está disponível para empréstimo');
            }
            isValid = false;
        }
    }
    
    return isValid;
}

// Initialize preselected values (for form errors/old input)
function initializePreselectedValues(studentSelect, bookSelect) {
    // Check if student is preselected
    if (studentSelect.value) {
        const selectedOption = studentSelect.options[studentSelect.selectedIndex];
        const fullText = selectedOption.textContent.replace('👤 ', '');
        const studentName = fullText.split(' - ')[0];
        document.getElementById('student_search').value = studentName;
        updateSelectionSummary('student', fullText);
    }
    
    // Check if book is preselected
    if (bookSelect.value) {
        const selectedOption = bookSelect.options[bookSelect.selectedIndex];
        const fullText = selectedOption.textContent.replace(/^[✅❌] /, '');
        const bookTitle = fullText.split(' - ')[0];
        document.getElementById('book_search').value = bookTitle;
        updateSelectionSummary('book', fullText);
    }
    
    // Check button state after initialization
    setTimeout(() => checkSubmitButtonState(), 100);
}

// Check submit button state
function checkSubmitButtonState() {
    const studentSelect = document.getElementById('student_id');
    const bookSelect = document.getElementById('book_id');
    const submitButton = document.querySelector('button[type="submit"]');
    
    if (!submitButton) return;
    
    const hasStudentSelection = studentSelect && studentSelect.value && studentSelect.value !== '';
    const hasBookSelection = bookSelect && bookSelect.value && bookSelect.value !== '';
    
    if (hasStudentSelection && hasBookSelection) {
        // Check if selected book is available
        const selectedBookOption = bookSelect.options[bookSelect.selectedIndex];
        const available = parseInt(selectedBookOption.getAttribute('data-available') || '0');
        
        if (available > 0) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-check-circle"></i> Registrar Empréstimo';
            submitButton.className = 'btn btn-success';
            
            // Show success notification
            if (window.notifications && !submitButton.dataset.notified) {
                window.notifications.success('Pronto para registrar o empréstimo!', { duration: 2000 });
                submitButton.dataset.notified = 'true';
            }
        } else {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Livro indisponível';
            submitButton.className = 'btn btn-warning';
        }
    } else {
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass"></i> Selecione aluno e livro';
        submitButton.className = 'btn btn-secondary';
        submitButton.dataset.notified = 'false';
    }
}

// Add form validation to the existing form
document.addEventListener('DOMContentLoaded', function() {
    const loanForm = document.querySelector('form[action*="loans"]');
    if (loanForm) {
        loanForm.addEventListener('submit', function(e) {
            if (!validateLoanForm()) {
                e.preventDefault();
                if (window.notifications) {
                    window.notifications.error('Por favor, corrija os erros no formulário antes de continuar.');
                }
            }
        });
    }
    
    // Initial button state check
    setTimeout(() => {
        checkSubmitButtonState();
        
        // Debug: Test if elements are found
        const studentSelect = document.getElementById('student_id');
        const bookSelect = document.getElementById('book_id');
        const submitButton = document.querySelector('button[type="submit"]');
        
        if (window.notifications) {
            if (!studentSelect) {
                window.notifications.error('Elemento student_id não encontrado!');
            }
            if (!bookSelect) {
                window.notifications.error('Elemento book_id não encontrado!');
            }
            if (!submitButton) {
                window.notifications.error('Botão submit não encontrado!');
            }
        }
    }, 500);
});
</script>
@endpush