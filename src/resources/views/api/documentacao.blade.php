<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>API — Traducaidiomas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            margin: 0;
            line-height: 1.5;
        }

        header {
            background: #1d3a6e;
            color: white;
            padding: 30px;
        }

        header h1 {
            margin: 0 0 8px;
        }

        header p {
            margin: 0;
        }

        main {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            margin-top: 36px;
        }

        .endpoint {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }

        .endpoint p {
            margin: 12px 0 0;
        }

        .method {
            display: inline-block;
            min-width: 58px;
            text-align: center;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            margin-right: 6px;
        }

        .get    { background: #198754; }
        .post   { background: #0d6efd; }
        .put    { background: #b35c00; }
        .patch  { background: #6f42c1; }
        .delete { background: #dc3545; }

        code {
            background: #eee;
            padding: 4px 8px;
            border-radius: 4px;
        }

        pre {
            background: #1e1e1e;
            color: #eee;
            padding: 16px;
            overflow-x: auto;
            border-radius: 6px;
        }

        .tabela {
            overflow-x: auto;
            margin-top: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            text-align: left;
            padding: 8px 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        th {
            background: #f0f3f8;
        }

        .aviso {
            background: #fff8e1;
            border-left: 4px solid #e0a800;
            padding: 12px 16px;
            border-radius: 4px;
            margin-top: 12px;
        }
    </style>
</head>

<body>

<header>
    <h1>API — Traducaidiomas</h1>

    <p>
        Documentação da API de gerenciamento de alunos.
    </p>
</header>

<main>

    <h2>Informações</h2>

    <p>
        Versão:
        <strong>v1</strong>
    </p>

    <p>
        Base da API:
        <code>{{ url('/api/v1') }}</code>
    </p>

    <p>
        Envie o header <code>Accept: application/json</code> em todas as
        requisições. Assim, erros de validação voltam como JSON com o código
        422, em vez de um redirecionamento.
    </p>


    <h2>Endpoints</h2>


    <div class="endpoint">

        <span class="method get">GET</span>

        <code>/api/v1/alunos</code>

        <p>
            Retorna todos os alunos, ordenados pelo nome.
            Aceita o filtro opcional <code>?status=ATIVO</code>.
        </p>

    </div>


    <div class="endpoint">

        <span class="method get">GET</span>

        <code>/api/v1/alunos/{id}</code>

        <p>
            Retorna os dados de um aluno. Se o aluno não existir,
            retorna o código 404.
        </p>

    </div>


    <div class="endpoint">

        <span class="method post">POST</span>

        <code>/api/v1/alunos</code>

        <p>
            Cadastra um novo aluno e retorna o código 201.
            Para enviar a foto, use <code>multipart/form-data</code>.
        </p>

        <div class="tabela">
            <table>
                <thead>
                    <tr>
                        <th>Campo</th>
                        <th>Obrigatório</th>
                        <th>Regra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>nome_aluno</code></td>
                        <td>Sim</td>
                        <td>Texto, até 255 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>email_aluno</code></td>
                        <td>Sim</td>
                        <td>E-mail válido e único</td>
                    </tr>
                    <tr>
                        <td><code>senha_aluno</code></td>
                        <td>Sim</td>
                        <td>Mínimo de 6 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>senha_aluno_confirmation</code></td>
                        <td>Sim</td>
                        <td>Igual a <code>senha_aluno</code></td>
                    </tr>
                    <tr>
                        <td><code>telefone_aluno</code></td>
                        <td>Sim</td>
                        <td>Texto, até 20 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>curso_aluno</code></td>
                        <td>Sim</td>
                        <td>Texto, até 100 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>data_nasc_aluno</code></td>
                        <td>Sim</td>
                        <td>Data (ex.: 2005-03-14)</td>
                    </tr>
                    <tr>
                        <td><code>nivel_aluno</code></td>
                        <td>Sim</td>
                        <td>Texto, até 50 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>status_aluno</code></td>
                        <td>Sim</td>
                        <td>Texto, até 50 caracteres</td>
                    </tr>
                    <tr>
                        <td><code>foto_aluno</code></td>
                        <td>Não</td>
                        <td>Imagem jpg, jpeg ou png, até 2 MB</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


    <div class="endpoint">

        <span class="method put">PUT</span>

        <code>/api/v1/alunos/{id}</code>

        <p>
            Atualiza os dados de um aluno. Usa os mesmos campos do cadastro,
            mas a senha e a foto são opcionais: se não forem enviadas,
            continuam as mesmas.
        </p>

        <div class="aviso">
            Para atualizar com foto, envie como <code>POST</code> para
            <code>/api/v1/alunos/{id}</code> com o campo
            <code>_method=PUT</code> no corpo, porque o PHP não lê arquivos
            enviados em requisições <code>PUT</code>.
        </div>

    </div>


    <div class="endpoint">

        <span class="method patch">PATCH</span>

        <code>/api/v1/alunos/{id}/status</code>

        <p>
            Altera apenas o status do aluno.
            Campo obrigatório: <code>status_aluno</code>.
        </p>

    </div>


    <div class="endpoint">

        <span class="method delete">DELETE</span>

        <code>/api/v1/alunos/{id}</code>

        <p>
            Exclui o aluno junto com as matrículas, agendas, reagendamentos,
            notificações, feedbacks, presenças, progresso nos materiais
            e respostas de atividades.
        </p>

    </div>


    <h2>Exemplo de resposta</h2>

    <p>
        <code>GET /api/v1/alunos/1</code>
    </p>

    <pre>
{
    "success": true,
    "data": {
        "id_aluno": 1,
        "nome_aluno": "Maria Souza",
        "email_aluno": "maria@email.com",
        "telefone_aluno": "(11) 98765-4321",
        "curso_aluno": "Inglês",
        "data_nasc_aluno": "2005-03-14",
        "nivel_aluno": "Intermediário",
        "status_aluno": "ATIVO",
        "foto_aluno": "maria-souza.jpg"
    }
}
    </pre>


    <h2>Exemplo de erro de validação</h2>

    <p>
        Código <strong>422</strong>
    </p>

    <pre>
{
    "message": "The email aluno has already been taken.",
    "errors": {
        "email_aluno": [
            "The email aluno has already been taken."
        ]
    }
}
    </pre>


    <h2>Próximas etapas</h2>

    <p>
        A API será expandida para autenticação,
        professores, cursos, matrículas,
        agendamentos, materiais e atividades.
    </p>

</main>

</body>
</html>