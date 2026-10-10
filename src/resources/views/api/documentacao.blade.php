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


    <h2>Autenticação</h2>

    <p>
        Todas as rotas abaixo exigem um token. Faça login e envie o token no header
        <code>Authorization: Bearer SEU_TOKEN</code>. O token vale 30 dias.
    </p>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/professor/login</code>
        <p>
            Corpo: <code>email_professor</code> e <code>senha_professor</code>.
            Devolve o token do professor. Rotas de <strong>alunos</strong> e de
            criar/editar/excluir <strong>módulos</strong> exigem professor administrador.
        </p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/login</code>
        <p>
            Corpo: <code>email_aluno</code> e <code>senha_aluno</code>.
            Devolve o token do aluno (usado pelo app), que só acessa as rotas
            <code>/api/v1/aluno/*</code>.
        </p>
    </div>

    <p>
        Limites: 5 tentativas de login por minuto e 60 requisições por minuto por token.
        Acima disso a API responde <code>429</code>. Sem token ou com token do tipo errado:
        <code>401</code> / <code>403</code>.
    </p>


    <h2>App do aluno (traduca-APP)</h2>

    <p>Todas exigem o token do aluno (<code>POST /api/v1/aluno/login</code>).</p>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/cursos</code>
        <p>Cursos em que o aluno tem matrícula ativa.</p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/cursos/{idCurso}/modulos</code>
        <p>
            Tela <strong>Curso</strong>: carga horária, <code>total_aulas</code>, <code>total_modulos</code>,
            <code>percentual_geral</code> e a lista de módulos com <code>concluido</code>,
            <code>liberado</code>, <code>em_andamento</code> e <code>percentual</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/modulos/{idModulo}</code>
        <p>
            Tela <strong>Módulo</strong>: dados do módulo, <code>progresso</code>
            (<code>aulas_concluidas</code> / <code>total_aulas</code>), <code>proximo_modulo</code>
            e a lista de <code>aulas</code> com <code>numero</code>, <code>titulo</code>, <code>data</code>,
            <code>hora</code>, <code>duracao_minutos</code>, <code>ao_vivo</code>, <code>link_aula</code>
            e <code>concluida</code>. Módulo ainda bloqueado responde <code>403</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/cursos/{idCurso}/materiais</code>
        <p>
            Aba <strong>Materiais</strong>. Filtro opcional <code>?modulo=ID</code>.
            Cada item traz <code>concluido</code> e <code>url_download</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/materiais/{idMaterial}/download</code>
        <p>Baixa o arquivo (enviar o token) e marca o material como concluído.</p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/perfil</code>
        <p>Tela <strong>Perfil</strong>: nome, e-mail, telefone, foto, situação e <code>cursos</code> (idioma e nível).</p>
    </div>

    <div class="endpoint">
        <span class="method put">PUT</span>
        <code>/api/v1/aluno/perfil/email</code>
        <p>Troca o e-mail. Corpo: <code>email_aluno</code> e <code>senha_atual</code>. E-mail já usado ou senha errada: <code>422</code>.</p>
    </div>

    <div class="endpoint">
        <span class="method put">PUT</span>
        <code>/api/v1/aluno/perfil/senha</code>
        <p>
            Troca a senha. Corpo: <code>senha_atual</code>, <code>nova_senha</code> (mín. 6) e
            <code>nova_senha_confirmation</code>. Desconecta os outros aparelhos do aluno.
        </p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/perfil/foto</code>
        <p>Troca a foto (multipart, campo <code>foto_aluno</code>: JPG, PNG ou WEBP até 2 MB).</p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/agenda</code>
        <p>Tela <strong>Agenda</strong>: <code>proxima_aula</code> e as <code>aulas</code> de todos os cursos matriculados.</p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/reagendamento/solicitar</code>
        <p>
            Pede o reagendamento de uma aula. Corpo: <code>aula_id</code> e <code>motivo</code> (mín. 10 caracteres).
            Já existe pedido pendente para a aula: <code>409</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/atividades</code>
        <p>
            Tela <strong>Atividades</strong>: <code>total</code>, <code>concluidas</code>, <code>pendentes</code> e a lista
            com <code>categoria</code> (<code>label</code>, <code>cor</code>), <code>finalidade</code>, <code>curso</code>,
            <code>professor</code>, <code>data_entrega</code>, <code>status</code>
            (<code>pendente</code> | <code>enviada</code> | <code>corrigida</code>) e <code>nota</code> (só depois de corrigida).
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/atividades/{id}</code>
        <p>
            Abre a atividade: <code>questoes</code> (<code>tipo</code> <code>multipla_escolha</code> com <code>opcoes</code>,
            ou <code>texto</code>), a <code>resposta_aluno</code> e se ela está <code>correta</code> (a resposta certa
            nunca é enviada), <code>feedback_professor</code> e <code>pode_responder</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/atividades/{id}/audio</code>
        <p>Áudio da atividade (enviar o token). Sem áudio: <code>404</code>.</p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/atividades/{id}/responder</code>
        <p>
            Envia as respostas. Corpo: <code>{"respostas": {"&lt;id_questao&gt;": "B" ou "texto"}}</code>.
            Atividade já corrigida: <code>409</code>.
        </p>
    </div>

    <div class="endpoint">
        <span class="method get">GET</span>
        <code>/api/v1/aluno/notificacoes</code>
        <p>
            Sino do app: <code>nao_lidas</code> e os últimos 50 avisos (<code>mensagem</code>, <code>link</code> da tela
            do app, <code>lida</code>, <code>data</code>). Criados quando o professor cadastra aula, atividade ou material,
            corrige atividade, responde dúvida ou responde reagendamento.
        </p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/notificacoes/{id}/lida</code>
        <p>Marca um aviso como lido. Aviso de outro aluno: <code>404</code>.</p>
    </div>

    <div class="endpoint">
        <span class="method post">POST</span>
        <code>/api/v1/aluno/notificacoes/lidas</code>
        <p>Marca todos os avisos do aluno como lidos.</p>
    </div>

    <p>
        <strong>Regra de conclusão:</strong> uma aula fica <em>concluída</em> quando o professor
        marca presença <code>presente</code> ou <code>justificado</code>; um material, quando o
        aluno abre ou baixa o arquivo. O módulo é concluído quando todas as aulas e materiais
        estão concluídos, e isso libera o próximo módulo.
    </p>


    <h2>Endpoints</h2>


    <div class="endpoint">

        <span class="method get">GET</span>

        <code>/api/v1/alunos</code>

        <p>
            Retorna todos os alunos, ordenados pelo nome.
            Aceita o filtro opcional <code>?status=EM CURSO</code>.
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
        "status_aluno": "EM CURSO",
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
        A API será expandida para
        professores, cursos, matrículas,
        agendamentos, materiais e atividades.
    </p>

</main>

</body>
</html>