# Plano de Implementação - Sistema de Biblioteca

- [x] 1. Configurar estrutura base e autenticação





  - Configurar middleware de autenticação personalizado
  - Criar migration e seeder para usuário regente
  - Implementar AuthController com login/logout
  - Criar views de login e layout base
  - _Requisitos: 1.1, 1.2, 1.3, 1.4, 1.5_

- [x] 2. Implementar gerenciamento de alunos




- [x] 2.1 Criar model e migration de Student


  - Implementar model Student com fillables e relacionamentos
  - Criar migration com campos: name, email, registration, course, grade
  - Adicionar índices únicos para email e registration
  - _Requisitos: 2.1, 2.3, 2.4, 2.5_

- [x] 2.2 Implementar StudentController e Form Request


  - Criar StudentRequest com validações (email único, registration único)
  - Implementar métodos CRUD no StudentController
  - Adicionar busca por nome e matrícula
  - _Requisitos: 2.1, 2.2, 2.4, 2.5, 3.3_



- [x] 2.3 Criar views para gerenciamento de alunos





  - Implementar formulário de cadastro/edição de aluno
  - Criar listagem paginada com busca
  - Adicionar botões de ação (editar, excluir)
  - _Requisitos: 2.1, 3.1, 3.2, 3.4, 3.5_

- [ ]* 2.4 Escrever testes para Student
  - Criar testes unitários para model Student
  - Implementar testes de feature para CRUD de alunos
  - Testar validações e busca
  - _Requisitos: 2.1, 2.2, 2.3, 2.4, 2.5, 3.1, 3.2, 3.3, 3.4, 3.5_

- [x] 3. Implementar gerenciamento de livros





- [x] 3.1 Criar model e migration de Book


  - Implementar model Book com fillables e métodos auxiliares
  - Criar migration com campos: title, author, isbn, publisher, publication_year, total_quantity, available_quantity
  - Adicionar índice único para ISBN
  - _Requisitos: 4.1, 4.2, 4.3, 4.4, 4.5_

- [x] 3.2 Implementar BookController e Form Request


  - Criar BookRequest com validações (ISBN único quando informado)
  - Implementar métodos CRUD no BookController
  - Adicionar busca por título, autor e ISBN
  - _Requisitos: 4.1, 4.2, 4.3, 5.3_

- [x] 3.3 Criar views para gerenciamento de livros


  - Implementar formulário de cadastro/edição de livro
  - Criar listagem paginada com busca e indicadores visuais
  - Mostrar quantidade total e disponível
  - Destacar livros com estoque baixo
  - _Requisitos: 4.1, 5.1, 5.2, 5.3, 5.4, 5.5_

- [ ]* 3.4 Escrever testes para Book
  - Criar testes unitários para model Book (métodos auxiliares)
  - Implementar testes de feature para CRUD de livros
  - Testar validações e indicadores de estoque
  - _Requisitos: 4.1, 4.2, 4.3, 4.4, 4.5, 5.1, 5.2, 5.3, 5.4, 5.5_

- [-] 4. Implementar sistema de empréstimos





- [x] 4.1 Criar model e migration de Loan


  - Implementar model Loan com relacionamentos e métodos auxiliares
  - Criar migration com campos: student_id, book_id, loan_date, due_date, return_date, is_returned
  - Adicionar foreign keys e índices
  - _Requisitos: 6.1, 6.2, 6.3, 6.5_



- [x] 4.2 Implementar LoanService



  - Criar service para lógica de empréstimos
  - Implementar método createLoan com validação de disponibilidade
  - Implementar método returnBook com atualização de estoque
  - Calcular data de devolução (15 dias)

  - _Requisitos: 6.1, 6.2, 6.3, 6.4, 8.1, 8.2, 8.3_

- [x] 4.3 Implementar LoanController e Form Request

  - Criar LoanRequest com validações
  - Implementar métodos para criar empréstimo e registrar devolução
  - Adicionar busca por aluno e livro
  - Integrar com LoanService
  - _Requisitos: 6.1, 6.4, 7.3, 8.1_

- [x] 4.4 Criar views para empréstimos


  - Implementar formulário de empréstimo com seleção de aluno e livro
  - Criar listagem de empréstimos ativos com indicadores de atraso
  - Adicionar botão de devolução
  - Mostrar dias restantes/atraso
  - _Requisitos: 6.1, 7.1, 7.2, 7.4, 7.5, 8.1_

- [ ]* 4.5 Escrever testes para Loan
  - Criar testes unitários para model Loan (métodos auxiliares)
  - Implementar testes de feature para fluxo de empréstimos
  - Testar LoanService e validações de negócio
  - _Requisitos: 6.1, 6.2, 6.3, 6.4, 6.5, 7.1, 7.2, 7.3, 7.4, 7.5, 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 5. Implementar dashboard e histórico





- [x] 5.1 Criar DashboardController





  - Implementar método index com estatísticas gerais
  - Calcular total de alunos, livros e empréstimos ativos
  - Mostrar empréstimos em atraso
  - _Requisitos: 7.1, 7.2_

- [x] 5.2 Criar view do dashboard


  - Implementar painel com cards de estatísticas
  - Mostrar gráficos ou indicadores visuais
  - Adicionar links rápidos para funcionalidades principais
  - _Requisitos: 7.1, 7.2_

- [x] 5.3 Implementar histórico de empréstimos


  - Adicionar método history no LoanController
  - Criar view para histórico de empréstimos concluídos
  - Mostrar informações de atraso quando aplicável
  - _Requisitos: 8.4, 8.5_

- [ ]* 5.4 Escrever testes para Dashboard
  - Criar testes de feature para dashboard
  - Testar cálculo de estatísticas
  - Verificar exibição de dados
  - _Requisitos: 7.1, 7.2, 8.4, 8.5_

- [x] 6. Finalizar interface e navegação







- [x] 6.1 Implementar layout responsivo


  - Criar layout base com Bootstrap ou Tailwind
  - Implementar navegação principal
  - Adicionar breadcrumbs e menu lateral
  - _Requisitos: Todos os requisitos de interface_

- [x] 6.2 Adicionar validações JavaScript



  - Implementar validações client-side nos formulários
  - Adicionar confirmações para exclusões
  - Melhorar experiência do usuário com feedback visual
  - _Requisitos: 2.1, 4.1, 6.1_



- [ ] 6.3 Implementar sistema de notificações
  - Adicionar flash messages para operações
  - Implementar alertas de sucesso e erro
  - Mostrar mensagens de validação de forma amigável
  - _Requisitos: 2.2, 4.2, 6.2, 8.2_

- [ ]* 6.4 Escrever testes de interface
  - Criar testes browser (Dusk) para fluxos principais
  - Testar navegação e interações
  - Verificar responsividade básica
  - _Requisitos: Todos os requisitos de interface_

- [ ] 7. Configurar ambiente e deploy
- [ ] 7.1 Configurar seeders e factories
  - Criar factory para cada model
  - Implementar seeders para dados de desenvolvimento
  - Configurar DatabaseSeeder principal
  - _Requisitos: Suporte ao desenvolvimento_

- [ ] 7.2 Configurar variáveis de ambiente
  - Definir configurações de banco de dados
  - Configurar chaves de aplicação
  - Documentar variáveis necessárias no .env.example
  - _Requisitos: Configuração de ambiente_

- [ ]* 7.3 Escrever documentação
  - Criar README com instruções de instalação
  - Documentar funcionalidades principais
  - Adicionar guia de uso básico
  - _Requisitos: Documentação do sistema_