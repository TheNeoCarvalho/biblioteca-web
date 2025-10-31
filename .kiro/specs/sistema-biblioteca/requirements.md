# Documento de Requisitos - Sistema de Biblioteca

## Introdução

O Sistema de Biblioteca é uma aplicação web desenvolvida em Laravel para gerenciar o acervo e usuários de uma biblioteca escolar ou acadêmica. O sistema permite que o regente da biblioteca gerencie alunos, livros e empréstimos de forma centralizada e eficiente.

## Glossário

- **Sistema de Biblioteca**: A aplicação web completa para gerenciamento da biblioteca
- **Regente**: Administrador responsável pela biblioteca que possui acesso completo ao sistema
- **Aluno**: Usuário cadastrado que pode realizar empréstimos de livros
- **Livro**: Item do acervo da biblioteca disponível para empréstimo
- **Empréstimo**: Registro de retirada de um livro por um aluno
- **Matrícula**: Número único de identificação do aluno na instituição
- **Acervo**: Conjunto de todos os livros disponíveis na biblioteca

## Requisitos

### Requisito 1

**User Story:** Como regente da biblioteca, eu quero fazer login no sistema, para que eu possa acessar as funcionalidades administrativas de forma segura.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL apresentar uma tela de login com campos para email e senha
2. WHEN o regente inserir credenciais válidas, O Sistema de Biblioteca SHALL autenticar o usuário e redirecionar para o painel administrativo
3. IF as credenciais forem inválidas, THEN O Sistema de Biblioteca SHALL exibir uma mensagem de erro e manter o usuário na tela de login
4. O Sistema de Biblioteca SHALL manter a sessão do regente ativa por 2 horas de inatividade
5. O Sistema de Biblioteca SHALL permitir logout seguro do regente

### Requisito 2

**User Story:** Como regente da biblioteca, eu quero cadastrar alunos no sistema, para que eles possam realizar empréstimos de livros.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL fornecer um formulário de cadastro com campos obrigatórios: nome, email, matrícula, curso e série
2. WHEN o regente submeter o formulário com dados válidos, O Sistema de Biblioteca SHALL salvar o aluno no banco de dados
3. O Sistema de Biblioteca SHALL validar que o email possui formato válido
4. O Sistema de Biblioteca SHALL validar que a matrícula é única no sistema
5. IF a matrícula já existir, THEN O Sistema de Biblioteca SHALL exibir mensagem de erro informando duplicidade

### Requisito 3

**User Story:** Como regente da biblioteca, eu quero visualizar a lista de alunos cadastrados, para que eu possa gerenciar as informações dos usuários.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL exibir uma lista paginada com todos os alunos cadastrados
2. O Sistema de Biblioteca SHALL mostrar nome, email, matrícula, curso e série de cada aluno na listagem
3. O Sistema de Biblioteca SHALL permitir busca de alunos por nome ou matrícula
4. O Sistema de Biblioteca SHALL fornecer opções para editar e excluir cada aluno da lista
5. WHEN o regente clicar em editar, O Sistema de Biblioteca SHALL abrir formulário preenchido com dados atuais do aluno

### Requisito 4

**User Story:** Como regente da biblioteca, eu quero cadastrar livros no acervo, para que eles fiquem disponíveis para empréstimo.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL fornecer formulário de cadastro com campos: título, autor, ISBN, editora, ano de publicação e quantidade disponível
2. WHEN o regente submeter o formulário com dados válidos, O Sistema de Biblioteca SHALL salvar o livro no banco de dados
3. O Sistema de Biblioteca SHALL validar que o ISBN possui formato válido quando informado
4. O Sistema de Biblioteca SHALL permitir cadastro de múltiplas cópias do mesmo livro através do campo quantidade
5. O Sistema de Biblioteca SHALL definir status inicial do livro como "disponível"

### Requisito 5

**User Story:** Como regente da biblioteca, eu quero visualizar o acervo de livros, para que eu possa gerenciar o inventário da biblioteca.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL exibir lista paginada com todos os livros do acervo
2. O Sistema de Biblioteca SHALL mostrar título, autor, ISBN, quantidade total e quantidade disponível de cada livro
3. O Sistema de Biblioteca SHALL permitir busca de livros por título, autor ou ISBN
4. O Sistema de Biblioteca SHALL fornecer opções para editar e excluir cada livro da lista
5. O Sistema de Biblioteca SHALL exibir indicador visual quando um livro estiver com estoque baixo (menos de 2 unidades disponíveis)

### Requisito 6

**User Story:** Como regente da biblioteca, eu quero registrar empréstimos de livros, para que eu possa controlar quais livros estão com quais alunos.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL fornecer formulário para registrar empréstimo selecionando aluno e livro
2. WHEN o regente registrar um empréstimo, O Sistema de Biblioteca SHALL decrementar a quantidade disponível do livro
3. O Sistema de Biblioteca SHALL definir data de devolução como 15 dias após a data do empréstimo
4. IF o livro não possuir exemplares disponíveis, THEN O Sistema de Biblioteca SHALL impedir o empréstimo e exibir mensagem informativa
5. O Sistema de Biblioteca SHALL registrar data e hora do empréstimo automaticamente

### Requisito 7

**User Story:** Como regente da biblioteca, eu quero visualizar empréstimos ativos, para que eu possa acompanhar quais livros estão emprestados e suas datas de devolução.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL exibir lista de todos os empréstimos ativos com nome do aluno, título do livro e data de devolução
2. O Sistema de Biblioteca SHALL destacar empréstimos em atraso com indicador visual diferenciado
3. O Sistema de Biblioteca SHALL permitir busca de empréstimos por nome do aluno ou título do livro
4. O Sistema de Biblioteca SHALL calcular e exibir quantos dias restam para devolução ou quantos dias de atraso
5. O Sistema de Biblioteca SHALL ordenar empréstimos por data de devolução por padrão

### Requisito 8

**User Story:** Como regente da biblioteca, eu quero registrar devoluções de livros, para que eu possa atualizar o status dos empréstimos e disponibilidade do acervo.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL permitir marcar empréstimo como devolvido através da lista de empréstimos ativos
2. WHEN o regente registrar uma devolução, O Sistema de Biblioteca SHALL incrementar a quantidade disponível do livro
3. O Sistema de Biblioteca SHALL registrar data e hora da devolução automaticamente
4. O Sistema de Biblioteca SHALL mover o empréstimo para histórico de empréstimos concluídos
5. O Sistema de Biblioteca SHALL calcular e registrar se houve atraso na devolução

### Requisito 9

**User Story:** Como regente da biblioteca, eu quero visualizar relatórios de leitura dos alunos, para que eu possa identificar quais alunos mais leram em um determinado período (mês, semestre ou ano letivo) e promover ações de incentivo à leitura.

#### Critérios de Aceitação

1. O Sistema de Biblioteca SHALL permitir que o regente selecione o período de análise (mês, semestre ou ano letivo) para geração do relatório de leitura.
2. O Sistema de Biblioteca SHALL exibir o aluno que mais realizou empréstimos no período selecionado, apresentando nome, curso e quantidade de livros lidos.
3. O Sistema de Biblioteca SHALL exibir um ranking Top 10 dos alunos que mais realizaram empréstimos, com as colunas: Posição, Nome do Aluno, Curso e Quantidade de Livros Lidos.
3. O Sistema de Biblioteca SHALL permitir exportar o relatório em formato PDF e/ou CSV.
4. O Sistema de Biblioteca SHALL atualizar automaticamente os dados de leitura com base nas devoluções registradas.
5. O Sistema de Biblioteca SHALL permitir filtrar os resultados por curso, turma ou período específico.