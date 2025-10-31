/**
 * Sistema de Biblioteca - Validações JavaScript
 * Validações client-side para formulários e confirmações de exclusão
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all form validations
    initializeFormValidations();
    initializeDeleteConfirmations();
    initializeFormEnhancements();
});

/**
 * Initialize form validations for all forms
 */
function initializeFormValidations() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Add real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Validate on form submit
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showFormErrors();
            }
        });
    });
}

/**
 * Initialize delete confirmations with enhanced modals
 */
function initializeDeleteConfirmations() {
    const deleteForms = document.querySelectorAll('form[method="POST"] input[name="_method"][value="DELETE"]');
    
    deleteForms.forEach(methodInput => {
        const form = methodInput.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showDeleteConfirmation(this);
            });
        }
    });
}

/**
 * Initialize form enhancements
 */
function initializeFormEnhancements() {
    // Student form validations
    initializeStudentFormValidations();
    
    // Book form validations
    initializeBookFormValidations();
    
    // Loan form validations
    initializeLoanFormValidations();
    
    // Login form validations
    initializeLoginFormValidations();
    
    // Add loading states to submit buttons
    initializeSubmitButtonStates();
}

/**
 * Student form specific validations
 */
function initializeStudentFormValidations() {
    const nameField = document.getElementById('name');
    const emailField = document.getElementById('email');
    const registrationField = document.getElementById('registration');
    const courseField = document.getElementById('course');
    const gradeField = document.getElementById('grade');
    
    if (nameField) {
        nameField.addEventListener('blur', function() {
            validateStudentName(this);
        });
    }
    
    if (emailField) {
        emailField.addEventListener('blur', function() {
            validateEmail(this);
        });
    }
    
    if (registrationField) {
        registrationField.addEventListener('blur', function() {
            validateRegistration(this);
        });
    }
    
    if (courseField) {
        courseField.addEventListener('blur', function() {
            validateRequired(this, 'Curso é obrigatório');
        });
    }
    
    if (gradeField) {
        gradeField.addEventListener('blur', function() {
            validateRequired(this, 'Série é obrigatória');
        });
    }
}

/**
 * Book form specific validations
 */
function initializeBookFormValidations() {
    const titleField = document.getElementById('title');
    const authorField = document.getElementById('author');
    const isbnField = document.getElementById('isbn');
    const publisherField = document.getElementById('publisher');
    const publicationYearField = document.getElementById('publication_year');
    const totalQuantityField = document.getElementById('total_quantity');
    const availableQuantityField = document.getElementById('available_quantity');
    
    if (titleField) {
        titleField.addEventListener('blur', function() {
            validateRequired(this, 'Título é obrigatório');
        });
    }
    
    if (authorField) {
        authorField.addEventListener('blur', function() {
            validateRequired(this, 'Autor é obrigatório');
        });
    }
    
    if (isbnField) {
        isbnField.addEventListener('blur', function() {
            validateISBN(this);
        });
    }
    
    if (publisherField) {
        publisherField.addEventListener('blur', function() {
            validateRequired(this, 'Editora é obrigatória');
        });
    }
    
    if (publicationYearField) {
        publicationYearField.addEventListener('blur', function() {
            validatePublicationYear(this);
        });
    }
    
    if (totalQuantityField) {
        totalQuantityField.addEventListener('input', function() {
            validateQuantity(this);
            syncAvailableQuantity();
        });
    }
    
    if (availableQuantityField) {
        availableQuantityField.addEventListener('blur', function() {
            validateAvailableQuantity(this);
        });
    }
}

/**
 * Loan form specific validations
 */
function initializeLoanFormValidations() {
    const studentSelect = document.getElementById('student_id');
    const bookSelect = document.getElementById('book_id');
    
    if (studentSelect) {
        studentSelect.addEventListener('change', function() {
            validateRequired(this, 'Selecione um aluno');
        });
    }
    
    if (bookSelect) {
        bookSelect.addEventListener('change', function() {
            validateRequired(this, 'Selecione um livro');
            validateBookAvailability(this);
        });
    }
}

/**
 * Validate individual field
 */
function validateField(field) {
    const fieldType = field.type;
    const fieldName = field.name;
    
    switch (fieldName) {
        case 'name':
            return validateStudentName(field);
        case 'email':
            return validateEmail(field);
        case 'registration':
            return validateRegistration(field);
        case 'title':
        case 'author':
        case 'publisher':
        case 'course':
        case 'grade':
            return validateRequired(field, `${field.labels[0]?.textContent || 'Campo'} é obrigatório`);
        case 'isbn':
            return validateISBN(field);
        case 'publication_year':
            return validatePublicationYear(field);
        case 'total_quantity':
        case 'available_quantity':
            return validateQuantity(field);
        case 'student_id':
        case 'book_id':
            return validateRequired(field, 'Seleção obrigatória');
        default:
            if (field.hasAttribute('required')) {
                return validateRequired(field, 'Campo obrigatório');
            }
            return true;
    }
}

/**
 * Validate entire form
 */
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Validate student name
 */
function validateStudentName(field) {
    const value = field.value.trim();
    
    if (!value) {
        showFieldError(field, 'Nome é obrigatório');
        return false;
    }
    
    if (value.length < 2) {
        showFieldError(field, 'Nome deve ter pelo menos 2 caracteres');
        return false;
    }
    
    if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value)) {
        showFieldError(field, 'Nome deve conter apenas letras e espaços');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate email
 */
function validateEmail(field) {
    const value = field.value.trim();
    
    if (!value) {
        showFieldError(field, 'Email é obrigatório');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        showFieldError(field, 'Email deve ter um formato válido');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate registration number
 */
function validateRegistration(field) {
    const value = field.value.trim();
    
    if (!value) {
        showFieldError(field, 'Matrícula é obrigatória');
        return false;
    }
    
    if (value.length < 3) {
        showFieldError(field, 'Matrícula deve ter pelo menos 3 caracteres');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate ISBN
 */
function validateISBN(field) {
    const value = field.value.trim();
    
    // ISBN is optional, so if empty, it's valid
    if (!value) {
        clearFieldError(field);
        return true;
    }
    
    // Remove hyphens and spaces for validation
    const cleanISBN = value.replace(/[-\s]/g, '');
    
    // Check if it's a valid ISBN-10 or ISBN-13
    if (!/^\d{10}$/.test(cleanISBN) && !/^\d{13}$/.test(cleanISBN)) {
        showFieldError(field, 'ISBN deve ter 10 ou 13 dígitos');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate publication year
 */
function validatePublicationYear(field) {
    const value = parseInt(field.value);
    const currentYear = new Date().getFullYear();
    
    if (!value) {
        showFieldError(field, 'Ano de publicação é obrigatório');
        return false;
    }
    
    if (value < 1000 || value > currentYear + 1) {
        showFieldError(field, `Ano deve estar entre 1000 e ${currentYear + 1}`);
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate quantity fields
 */
function validateQuantity(field) {
    const value = parseInt(field.value);
    
    if (!value || value < 1) {
        showFieldError(field, 'Quantidade deve ser maior que zero');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate available quantity against total quantity
 */
function validateAvailableQuantity(field) {
    const value = parseInt(field.value);
    const totalQuantityField = document.getElementById('total_quantity');
    const totalQuantity = totalQuantityField ? parseInt(totalQuantityField.value) : 0;
    
    if (value < 0) {
        showFieldError(field, 'Quantidade disponível não pode ser negativa');
        return false;
    }
    
    if (totalQuantity && value > totalQuantity) {
        showFieldError(field, 'Quantidade disponível não pode ser maior que a quantidade total');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate book availability for loans
 */
function validateBookAvailability(field) {
    const selectedOption = field.options[field.selectedIndex];
    if (selectedOption && selectedOption.textContent.includes('(0 disponível)')) {
        showFieldError(field, 'Este livro não possui exemplares disponíveis');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Validate required fields
 */
function validateRequired(field, message) {
    const value = field.value.trim();
    
    if (!value) {
        showFieldError(field, message);
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.invalid-feedback.js-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback js-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
    
    // Also show field notification if notification system is available
    if (window.formNotifications) {
        window.formNotifications.showFieldError(field, message);
    }
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    // Remove JavaScript-generated error message
    const jsError = field.parentNode.querySelector('.invalid-feedback.js-error');
    if (jsError) {
        jsError.remove();
    }
    
    // Also clear field notification if notification system is available
    if (window.formNotifications) {
        window.formNotifications.clearFieldError(field);
    }
}

/**
 * Show form errors summary
 */
function showFormErrors() {
    const invalidFields = document.querySelectorAll('.is-invalid');
    if (invalidFields.length > 0) {
        // Focus on first invalid field
        invalidFields[0].focus();
        
        // Show notification if available, otherwise fallback to toast
        if (window.notifications) {
            const errors = Array.from(invalidFields).map(field => {
                const error = field.parentNode.querySelector('.invalid-feedback');
                return error ? error.textContent : 'Campo inválido';
            });
            window.notifications.validationError(errors);
        } else {
            showToast('Por favor, corrija os erros no formulário', 'error');
        }
    }
}

/**
 * Sync available quantity with total quantity for new books
 */
function syncAvailableQuantity() {
    const totalQuantityField = document.getElementById('total_quantity');
    const availableQuantityField = document.getElementById('available_quantity');
    
    if (totalQuantityField && availableQuantityField) {
        const totalQuantity = parseInt(totalQuantityField.value) || 0;
        const currentAvailable = parseInt(availableQuantityField.value) || 0;
        
        // Set max attribute
        availableQuantityField.setAttribute('max', totalQuantity);
        
        // Check if we're on create page (URL contains 'create')
        const isCreatePage = window.location.pathname.includes('/create');
        
        if (isCreatePage) {
            // Auto-sync for new books
            if (currentAvailable <= totalQuantity || availableQuantityField.value === '') {
                availableQuantityField.value = totalQuantity;
            }
        } else {
            // For edit pages, just ensure available doesn't exceed total
            if (currentAvailable > totalQuantity) {
                availableQuantityField.value = totalQuantity;
            }
        }
    }
}

/**
 * Show enhanced delete confirmation
 */
function showDeleteConfirmation(form) {
    const formAction = form.action;
    let itemType = 'item';
    let itemName = '';
    
    // Determine what we're deleting based on the URL
    if (formAction.includes('/students/')) {
        itemType = 'aluno';
        const row = form.closest('tr');
        if (row) {
            itemName = row.querySelector('td:first-child').textContent.trim();
        }
    } else if (formAction.includes('/books/')) {
        itemType = 'livro';
        const row = form.closest('tr');
        if (row) {
            itemName = row.querySelector('td:first-child strong').textContent.trim();
        }
    }
    
    const message = itemName 
        ? `Tem certeza que deseja excluir o ${itemType} "${itemName}"?`
        : `Tem certeza que deseja excluir este ${itemType}?`;
    
    showConfirmationModal(
        'Confirmar Exclusão',
        message,
        'Esta ação não pode ser desfeita.',
        'Excluir',
        'btn-danger',
        function() {
            form.submit();
        }
    );
}

/**
 * Show confirmation modal
 */
function showConfirmationModal(title, message, subtitle, confirmText, confirmClass, onConfirm) {
    // Remove existing modal if any
    const existingModal = document.getElementById('confirmationModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Create modal HTML
    const modalHTML = `
        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>${title}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">${message}</p>
                        ${subtitle ? `<small class="text-muted">${subtitle}</small>` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn ${confirmClass}" id="confirmButton">${confirmText}</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Initialize modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    
    // Add confirm button event
    document.getElementById('confirmButton').addEventListener('click', function() {
        modal.hide();
        onConfirm();
    });
    
    // Show modal
    modal.show();
    
    // Remove modal from DOM when hidden
    document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Remove existing toast if any
    const existingToast = document.getElementById('validationToast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toastClass = type === 'error' ? 'bg-danger' : 'bg-info';
    const icon = type === 'error' ? 'bi-exclamation-circle' : 'bi-info-circle';
    
    const toastHTML = `
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="validationToast" class="toast ${toastClass} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    <i class="bi ${icon} me-2"></i>${message}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toastHTML);
    
    const toast = new bootstrap.Toast(document.getElementById('validationToast'));
    toast.show();
    
    // Remove toast from DOM when hidden
    document.getElementById('validationToast').addEventListener('hidden.bs.toast', function() {
        this.closest('.toast-container').remove();
    });
}

/**
 * Enhanced return confirmation for loans
 */
function initializeLoanReturnConfirmations() {
    const returnForms = document.querySelectorAll('form[action*="return"]');
    
    returnForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const row = form.closest('tr');
            let studentName = '';
            let bookTitle = '';
            
            if (row) {
                const studentCell = row.querySelector('td:first-child strong');
                const bookCell = row.querySelector('td:nth-child(2) strong');
                
                if (studentCell) studentName = studentCell.textContent.trim();
                if (bookCell) bookTitle = bookCell.textContent.trim();
            }
            
            const message = studentName && bookTitle
                ? `Confirma a devolução do livro "${bookTitle}" pelo aluno "${studentName}"?`
                : 'Confirma a devolução deste livro?';
            
            showConfirmationModal(
                'Confirmar Devolução',
                message,
                'Esta ação registrará a devolução do livro.',
                'Confirmar Devolução',
                'btn-success',
                function() {
                    form.submit();
                }
            );
        });
    });
}

/**
 * Login form specific validations
 */
function initializeLoginFormValidations() {
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    if (emailField) {
        emailField.addEventListener('blur', function() {
            validateEmail(this);
        });
    }
    
    if (passwordField) {
        passwordField.addEventListener('blur', function() {
            validatePassword(this);
        });
    }
}

/**
 * Validate password field
 */
function validatePassword(field) {
    const value = field.value.trim();
    
    if (!value) {
        showFieldError(field, 'Senha é obrigatória');
        return false;
    }
    
    if (value.length < 3) {
        showFieldError(field, 'Senha deve ter pelo menos 3 caracteres');
        return false;
    }
    
    clearFieldError(field);
    return true;
}

/**
 * Initialize submit button loading states
 */
function initializeSubmitButtonStates() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                // Store original content
                const originalContent = submitButton.innerHTML;
                
                // Add loading state
                submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processando...';
                submitButton.disabled = true;
                
                // Restore button after 10 seconds (fallback)
                setTimeout(() => {
                    submitButton.innerHTML = originalContent;
                    submitButton.disabled = false;
                }, 10000);
            }
        });
    });
}

/**
 * Add visual feedback for form interactions
 */
function addVisualFeedback() {
    // Add hover effects to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add focus effects to form controls
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentNode.style.transform = 'scale(1.02)';
            this.parentNode.style.transition = 'transform 0.2s ease';
        });
        
        control.addEventListener('blur', function() {
            this.parentNode.style.transform = 'scale(1)';
        });
    });
}

// Initialize loan return confirmations when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeLoanReturnConfirmations();
    addVisualFeedback();
});