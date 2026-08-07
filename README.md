<p align="center">
  <img src="https://img.shields.io/badge/status-em%20desenvolvimento-yellow" alt="status">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white" alt="php">
  <img src="https://img.shields.io/badge/Laravel-Blade-FF2D20?logo=laravel&logoColor=white" alt="laravel">
  <img src="https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?logo=javascript&logoColor=black" alt="javascript">
  <img src="https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white" alt="docker">
  <img src="https://img.shields.io/github/license/André-Marcelino-Desenvolvimento/traducaidiomas" alt="license">
</p>

<h1 align="center">🌐 Traducaidiomas</h1>

<p align="center">
  Projeto integrador Back-end — plataforma de tradução de idiomas.
</p>

<p align="center">
  <a href="#-sobre-o-projeto">Sobre</a> •
  <a href="#-funcionalidades">Funcionalidades</a> •
  <a href="#-tecnologias">Tecnologias</a> •
  <a href="#-como-executar">Como executar</a> •
  <a href="#-estrutura-do-projeto">Estrutura</a> •
  <a href="#-contribuindo">Contribuindo</a> •
  <a href="#-licença">Licença</a>
</p>

---

## 📖 Sobre o projeto

O **Traducaidiomas** é um projeto integrador de back-end que tem como objetivo oferecer uma plataforma de tradução de idiomas, com foco em uma arquitetura organizada, containerizada com Docker e pronta para deploy automatizado via GitHub Actions.

> ✏️ *Substitua este parágrafo por uma descrição mais detalhada do que o sistema faz, para quem ele é destinado e qual problema ele resolve.*

---

## ✨ Funcionalidades

- [ ] Cadastro e autenticação de usuários
- [ ] Tradução de textos entre idiomas
- [ ] Histórico de traduções
- [ ] Painel administrativo
- [ ] Envio de e-mails automáticos
- [ ] Deploy simplificado via `git pull`

> ✏️ *Marque as funcionalidades já implementadas e ajuste a lista conforme o escopo real do projeto.*

---

## 🛠️ Tecnologias

Este projeto foi desenvolvido com:

- **PHP** + **Blade** (Laravel)
- **JavaScript**
- **HTML5** e **CSS3**
- **Docker** e **Docker Compose**
- **GitHub Actions** (CI/CD)

---

## 🚀 Como executar

### Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/) instalados
- Git

### Passo a passo

```bash
# Clone o repositório
git clone https://github.com/André-Marcelino-Desenvolvimento/traducaidiomas.git

# Acesse a pasta do projeto
cd traducaidiomas

# Copie o arquivo de variáveis de ambiente
cp .env.example .env

# Suba os containers
docker-compose up -d --build

# Acesse o container da aplicação (se necessário)
docker-compose exec app bash

# Instale as dependências
composer install
npm install

# Rode as migrations
php artisan migrate
```

A aplicação estará disponível em `http://localhost:8000` (ajuste conforme a porta configurada no `docker-compose.yml`).

> ✏️ *Confirme e ajuste os comandos acima de acordo com o `docker-compose.yml` real do projeto.*

---

## 📁 Estrutura do projeto

```
traducaidiomas/
├── .github/workflows/   # Pipelines de CI/CD
├── .vscode/             # Configurações do editor
├── docker/              # Configurações dos containers
├── src/                 # Código-fonte da aplicação
├── traducaidiomas/      # Submódulo/pasta do projeto principal
├── docker-compose.yml   # Orquestração dos containers
└── .gitignore
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. Faça um **fork** do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/minha-feature`)
3. Faça o commit das suas alterações (`git commit -m 'feat: minha nova feature'`)
4. Faça o push para a branch (`git push origin feature/minha-feature`)
5. Abra um **Pull Request**

---

## 👥 Colaboradores

<a href="https://github.com/André-Marcelino-Desenvolvimento">
  <img src="https://github.com/André-Marcelino-Desenvolvimento.png" width="60px" style="border-radius:50%" alt="André Marcelino"/>
</a>

---

## 📄 Licença

Este projeto está sob a licença especificada em [LICENSE](LICENSE). Caso ainda não exista, considere adicionar uma (ex: [MIT](https://choosealicense.com/licenses/mit/)).

---

<p align="center">Feito com 💙 por André Marcelino</p>
