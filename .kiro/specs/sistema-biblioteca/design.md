# Documento de Design - Sistema de Biblioteca

## Visão Geral

O Sistema de Biblioteca será desenvolvido como uma aplicação web Laravel seguindo o padrão MVC (Model-View-Controller). A aplicação utilizará autenticação baseada em sessão, Eloquent ORM para persistência de dados, e Blade templates para renderização das views. O sistema será responsivo e seguirá as melhores práticas de segurança do Laravel.

## Arquitetura

### Arquitetura Geral
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Presentation  │    │    Business     │    │   Data Access   │
│     Layer       │    │     Logic       │    │     Layer       │
│                 │    │     Layer       │    │                 │
│ - Blade Views   │◄──►│ - Controllers   │◄──►│ - Models        │
│ - CSS/JS        │    │ - Middleware    │    │ - Migrations    │
│ - Forms         │    │ - Requests      │    │ - Seeders       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                                │
                                ▼
                       ┌─────────────────┐
                       │    Database     │
                       │    (MySQL)      │
                       └─────────────────┘
```

### Estrutura de Diretórios Laravel
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   ├── BookController.php
│   │   └── LoanController.php
│   ├── Middleware/
│   │   └── AuthenticateLibrarian.php
│   └── Requests/
│       ├── StudentRequest.php
│       ├── BookRequest.php
│       └── LoanRequest.php
├── Models/
│   ├── User.php (Regente)
│   ├── Student.php
│   ├── Book.php
│   └── Loan.php
└── Services/
    └── LoanService.php

database/
├── migrations/
├── seeders/
└── factories/

resources/
├── views/
│   ├── auth/
│   ├── dashboard/
│   ├── students/
│   ├── books/
│   └── loans/
└── css/
    └── app.css
```

## Componentes e Interfaces

### Models (Eloquent)

#### User Model (Regente)
```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    
    // Relacionamentos: nenhum direto
}
```

#### Student Model
```php
class Student extends Model
{
    protected $fillable = ['name', 'email', 'registration', 'course', 'grade'];
    
    // Relacionamentos
    public function loans(): HasMany
    public function activeLoans(): HasMany
}
```

#### Book Model
```php
class Book extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'publisher', 'publication_year', 'total_quantity', 'available_quantity'];
    
    // Relacionamentos
    public function loans(): HasMany
    
    // Métodos auxiliares
    public function isAvailable(): bool
    public function hasLowStock(): bool
}
```

#### Loan Model
```php
class Loan extends Model
{
    protected $fillable = ['student_id', 'book_id', 'loan_date', 'due_date', 'return_date', 'is_returned'];
    protected $casts = ['loan_date' => 'date', 'due_date' => 'date', 'return_date' => 'date'];
    
    // Relacionamentos
    public function student(): BelongsTo
    public function book(): BelongsTo
    
    // Métodos auxiliares
    public function isOverdue(): bool
    public function getDaysOverdue(): int
    public function getDaysUntilDue(): int
}
```

### Controllers

#### AuthController
- `showLoginForm()`: Exibe formulário de login
- `login(Request $request)`: Processa autenticação
- `logout()`: Realiza logout

#### DashboardController
- `index()`: Exibe painel principal com estatísticas

#### StudentController
- `index()`: Lista alunos com paginação e busca
- `create()`: Exibe formulário de cadastro
- `store(StudentRequest $request)`: Salva novo aluno
- `edit(Student $student)`: Exibe formulário de edição
- `update(StudentRequest $request, Student $student)`: Atualiza aluno
- `destroy(Student $student)`: Remove aluno

#### BookController
- `index()`: Lista livros com paginação e busca
- `create()`: Exibe formulário de cadastro
- `store(BookRequest $request)`: Salva novo livro
- `edit(Book $book)`: Exibe formulário de edição
- `update(BookRequest $request, Book $book)`: Atualiza livro
- `destroy(Book $book)`: Remove livro

#### LoanController
- `index()`: Lista empréstimos ativos
- `create()`: Exibe formulário de empréstimo
- `store(LoanRequest $request)`: Registra empréstimo
- `return(Loan $loan)`: Registra devolução
- `history()`: Histórico de empréstimos

### Services

#### LoanService
```php
class LoanService
{
    public function createLoan(Student $student, Book $book): Loan
    public function returnBook(Loan $loan): bool
    public function getOverdueLoans(): Collection
    public function calculateDueDate(Carbon $loanDate): Carbon
}
```

### Form Requests

#### StudentRequest
- Validação: name (required), email (required, email, unique), registration (required, unique), course (required), grade (required)

#### BookRequest
- Validação: title (required), author (required), isbn (nullable, unique), publisher (required), publication_year (required, integer), total_quantity (required, integer, min:1)

#### LoanRequest
- Validação: student_id (required, exists), book_id (required, exists)

## Modelos de Dados

### Estrutura do Banco de Dados

```sql
-- Tabela users (regentes)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela students
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    registration VARCHAR(50) UNIQUE NOT NULL,
    course VARCHAR(255) NOT NULL,
    grade VARCHAR(50) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela books
CREATE TABLE books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE NULL,
    publisher VARCHAR(255) NOT NULL,
    publication_year INT NOT NULL,
    total_quantity INT NOT NULL DEFAULT 1,
    available_quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabela loans
CREATE TABLE loans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    book_id BIGINT UNSIGNED NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE NULL,
    is_returned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);
```

### Relacionamentos
- Student 1:N Loan (um aluno pode ter vários empréstimos)
- Book 1:N Loan (um livro pode ter vários empréstimos)
- User não possui relacionamentos diretos

## Tratamento de Erros

### Estratégias de Tratamento
1. **Validação de Formulários**: Form Requests com mensagens personalizadas
2. **Exceções de Banco**: Try-catch em operações críticas
3. **Autenticação**: Middleware personalizado para verificar acesso
4. **Disponibilidade de Livros**: Verificação antes de criar empréstimo

### Tipos de Erro
- **ValidationException**: Dados inválidos em formulários
- **ModelNotFoundException**: Registro não encontrado
- **AuthenticationException**: Usuário não autenticado
- **BusinessLogicException**: Regras de negócio violadas (ex: livro indisponível)

### Tratamento por Camada
```php
// Controller
try {
    $loan = $this->loanService->createLoan($student, $book);
    return redirect()->route('loans.index')->with('success', 'Empréstimo registrado com sucesso!');
} catch (InsufficientStockException $e) {
    return back()->withErrors(['book_id' => 'Livro não disponível para empréstimo.']);
} catch (Exception $e) {
    return back()->withErrors(['error' => 'Erro interno. Tente novamente.']);
}
```

## Estratégia de Testes

### Tipos de Teste

#### Unit Tests
- **Models**: Testes de relacionamentos, métodos auxiliares e validações
- **Services**: Lógica de negócio isolada
- **Form Requests**: Regras de validação

#### Feature Tests
- **Autenticação**: Login, logout, proteção de rotas
- **CRUD Operations**: Criar, listar, editar, excluir para cada entidade
- **Fluxo de Empréstimos**: Criar empréstimo, devolver livro
- **Busca e Filtros**: Funcionalidades de pesquisa

#### Browser Tests (Dusk)
- **Fluxos Completos**: Jornada completa do usuário
- **Interface**: Interações com formulários e navegação

### Estrutura de Testes
```
tests/
├── Unit/
│   ├── Models/
│   │   ├── StudentTest.php
│   │   ├── BookTest.php
│   │   └── LoanTest.php
│   └── Services/
│       └── LoanServiceTest.php
├── Feature/
│   ├── Auth/
│   │   └── AuthenticationTest.php
│   ├── Students/
│   │   └── StudentManagementTest.php
│   ├── Books/
│   │   └── BookManagementTest.php
│   └── Loans/
│       └── LoanManagementTest.php
└── Browser/
    └── LibrarySystemTest.php
```

### Dados de Teste
- **Factories**: Para gerar dados consistentes
- **Seeders**: Para popular banco de desenvolvimento
- **Database Transactions**: Para isolar testes

### Cobertura de Testes
- **Objetivo**: Mínimo 80% de cobertura
- **Foco**: Lógica de negócio crítica (empréstimos, validações)
- **Ferramentas**: PHPUnit com Xdebug para relatórios de cobertura