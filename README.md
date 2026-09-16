# TraducaIdiomas

> Plataforma educacional e de gestão para aulas de idiomas, desenvolvida com Laravel, Docker e MySQL.

## 📚 Sobre o projeto

O **TraducaIdiomas** reúne em uma única plataforma recursos para alunos, professores e administração da escola. O sistema possui área pública, autenticação, gestão acadêmica e uma assistente de IA integrada.

### ✨ Principais recursos

- 👨‍🎓 Área do aluno com aulas, atividades, progresso e materiais
- 👨‍🏫 Área administrativa para professores e gestão de alunos
- 📅 Agenda, aulas, presença e reagendamentos
- 📚 Materiais e atividades educacionais
- 🧠 **Traduca AI** para suporte aos alunos e professores
- 📝 Quiz e páginas institucionais
- 📩 Formulário de contato
- 🔐 Autenticação e controle de acesso por perfil
- 🐳 Ambiente de desenvolvimento com Docker

## 🛠️ Stack

| Tecnologia | Uso |
|---|---|
| PHP | Backend |
| Laravel | Framework web |
| MySQL | Banco de dados |
| Docker | Ambiente e serviços |
| Bootstrap | Interface e responsividade |
| JavaScript | Interações e chatbot |
| Groq API | Inteligência artificial |
| Nginx | Servidor web |

## 🏗️ Arquitetura

O projeto utiliza uma arquitetura MVC baseada em Laravel, com separação entre as áreas pública, administrativa e do aluno. Serviços externos e credenciais são configurados por variáveis de ambiente.

```text
TraducaIdiomas
├── Área pública
├── Área do aluno
│   ├── Aulas
│   ├── Atividades
│   ├── Progresso
│   └── Materiais
├── Área administrativa
│   ├── Professores
│   ├── Alunos
│   ├── Agenda
│   ├── Matrículas
│   ├── Presença
│   └── Atividades
└── Traduca AI
```

## 🤖 Traduca AI

A plataforma conta com uma assistente baseada em inteligência artificial para auxiliar usuários em tarefas relacionadas ao ambiente educacional, utilizando a **Groq API** como serviço de inferência.

## 🔐 Segurança

Segredos e credenciais de serviços devem permanecer em variáveis de ambiente (`.env`) e nunca ser commitados no repositório. O projeto também utiliza autenticação separada para os diferentes perfis de acesso.

> **Importante:** este repositório é um projeto de desenvolvimento/portfólio. Antes de uma implantação em produção, revise as configurações de segurança, dados de teste, backups e credenciais.

## 👥 Equipe e créditos

Projeto desenvolvido em equipe por:

- **Felix Lopes** — Desenvolvimento do projeto
- **André Marcelino** — Desenvolvimento do projeto
- **Pedro Henrique** — Desenvolvimento do projeto

Todos os integrantes contribuíram para a construção e evolução do **TraducaIdiomas**.

## 🚧 Status

**Em desenvolvimento** — novas melhorias de interface, segurança e funcionalidades continuam sendo implementadas.

---

<p align="center">
  Desenvolvido como projeto educacional de integração de tecnologias web e backend.
</p>
