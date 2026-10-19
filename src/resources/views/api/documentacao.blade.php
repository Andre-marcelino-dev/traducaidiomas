@php
    // ─────────────────────────────────────────────────────────────────────
    // Documentação pública da API do Traduca Idiomas (/api/documentacao).
    // Cada rota: [método, caminho, acesso, descrição, parâmetros, exemplo].
    // Acesso: publica | aluno | professor | admin.
    // Parâmetro: [nome, onde (caminho|query|corpo), tipo, obrigatório, descrição].
    // Ao criar/alterar rota em routes/api.php, atualizar esta lista.
    // ─────────────────────────────────────────────────────────────────────
    // URL base de verdade. Fora do computador local, sempre https (o servidor pode
    // repassar o acesso como http e a documentação mostraria o endereço errado).
    $base = rtrim(url('/api/v1'), '/');
    if (!preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/#', $base)) {
        $base = preg_replace('#^http://#', 'https://', $base);
    }

    $acessos = [
        'publica'   => ['Pública', 'Não precisa de token.'],
        'aluno'     => ['Aluno', 'Token de aluno (POST /aluno/login).'],
        'professor' => ['Professor', 'Token de professor (POST /professor/login).'],
        'admin'     => ['Administrador', 'Token de professor com permissão de administrador.'],
    ];

    $p = fn ($nome, $onde, $tipo, $obrig, $desc) => compact('nome', 'onde', 'tipo', 'obrig', 'desc');

    $grupos = [
        [
            'id' => 'autenticacao',
            'titulo' => 'Autenticação e senha',
            'descricao' => 'Login devolve o token usado em todas as rotas protegidas.',
            'rotas' => [
                ['POST', '/aluno/login', 'publica', 'Login do aluno (app traduca-APP). Devolve o token e os dados do aluno. Aluno com cadastro INATIVO recebe 403.', [
                    $p('email_aluno', 'corpo', 'e-mail', true, 'E-mail cadastrado do aluno.'),
                    $p('senha_aluno', 'corpo', 'texto', true, 'Senha do aluno.'),
                ], "{\n  \"success\": true,\n  \"data\": {\n    \"token\": \"12|aBcD...\",\n    \"aluno\": { \"id_aluno\": 1, \"nome_aluno\": \"Aluno Exemplo\", \"email_aluno\": \"aluno@exemplo.com\", \"foto_aluno\": \"aluno_exemplo.png\" }\n  }\n}"],
                ['POST', '/aluno/logout', 'aluno', 'Encerra a sessão: apaga o token atual.', [], null],
                ['GET', '/aluno/me', 'aluno', 'Dados básicos do aluno do token (id, nome, e-mail, foto).', [], null],
                ['POST', '/aluno/senha/esqueci', 'publica', 'Envia por e-mail um código de 6 dígitos para redefinir a senha (vale 30 minutos). A resposta é a mesma exista ou não o e-mail, para não revelar quem é aluno.', [
                    $p('email_aluno', 'corpo', 'e-mail', true, 'E-mail do aluno.'),
                ], null],
                ['POST', '/aluno/senha/redefinir', 'publica', 'Troca a senha usando o código recebido por e-mail.', [
                    $p('email_aluno', 'corpo', 'e-mail', true, 'E-mail do aluno.'),
                    $p('codigo', 'corpo', 'texto', true, 'Código de 6 dígitos recebido por e-mail.'),
                    $p('nova_senha', 'corpo', 'texto', true, 'Nova senha (mínimo 6 caracteres).'),
                    $p('nova_senha_confirmation', 'corpo', 'texto', true, 'Repetição da nova senha.'),
                ], null],
                ['POST', '/professor/login', 'publica', 'Login do professor. Devolve o token do professor (usado nas rotas de gestão).', [
                    $p('email_professor', 'corpo', 'e-mail', true, 'E-mail do professor.'),
                    $p('senha_professor', 'corpo', 'texto', true, 'Senha do professor.'),
                ], null],
                ['POST', '/professor/logout', 'professor', 'Encerra a sessão do professor (apaga o token atual).', [], null],
                ['GET', '/professor/me', 'professor', 'Dados do professor do token.', [], null],
            ],
        ],
        [
            'id' => 'perfil',
            'titulo' => 'App do aluno — Perfil',
            'descricao' => 'Dados do aluno logado. Trocar e-mail ou senha sempre exige a senha atual.',
            'rotas' => [
                ['GET', '/aluno/perfil', 'aluno', 'Perfil completo: nome, e-mail, telefone, foto, situação e cursos (idioma e nível).', [], null],
                ['PUT', '/aluno/perfil/email', 'aluno', 'Troca o e-mail (que também é o login).', [
                    $p('email_aluno', 'corpo', 'e-mail', true, 'Novo e-mail (não pode estar em uso).'),
                    $p('senha_atual', 'corpo', 'texto', true, 'Senha atual do aluno.'),
                ], null],
                ['PUT', '/aluno/perfil/senha', 'aluno', 'Troca a senha e desconecta os outros aparelhos do aluno.', [
                    $p('senha_atual', 'corpo', 'texto', true, 'Senha atual.'),
                    $p('nova_senha', 'corpo', 'texto', true, 'Nova senha (mínimo 6 caracteres).'),
                    $p('nova_senha_confirmation', 'corpo', 'texto', true, 'Repetição da nova senha.'),
                ], null],
                ['POST', '/aluno/perfil/foto', 'aluno', 'Troca a foto do perfil. Enviar como multipart/form-data.', [
                    $p('foto_aluno', 'corpo', 'arquivo', true, 'Imagem JPG, PNG ou WEBP de até 2 MB.'),
                ], null],
            ],
        ],
        [
            'id' => 'curso',
            'titulo' => 'App do aluno — Cursos, módulos e materiais',
            'descricao' => 'Só aparecem cursos com matrícula ativa. Módulos são liberados em ordem (o próximo libera quando o anterior é concluído).',
            'rotas' => [
                ['GET', '/aluno/cursos', 'aluno', 'Cursos em que o aluno tem matrícula ativa (curso e nível).', [], null],
                ['GET', '/aluno/cursos/{idCurso}/modulos', 'aluno', 'Tela Curso: carga horária, total de aulas e módulos, % geral e a lista de módulos com status (concluído, liberado, em andamento).', [
                    $p('idCurso', 'caminho', 'número', true, 'Id do curso (de /aluno/cursos).'),
                ], "{\n  \"success\": true,\n  \"data\": {\n    \"curso\": \"Inglês\", \"nivel\": \"Iniciante\",\n    \"carga_horaria_minutos\": 600, \"total_modulos\": 5, \"total_aulas\": 40,\n    \"aulas_concluidas\": 12, \"percentual_geral\": 62,\n    \"modulos\": [ { \"id_modulo\": 3, \"ordem_modulo\": 1, \"nome_modulo\": \"Fundamentos\", \"percentual\": 100, \"concluido\": true, \"liberado\": true, \"em_andamento\": false } ]\n  }\n}"],
                ['GET', '/aluno/modulos/{idModulo}', 'aluno', 'Tela Módulo: dados do módulo, progresso, próximo módulo e lista de aulas (data, hora, duração, link da aula ao vivo, presença). Módulo ainda bloqueado: 403.', [
                    $p('idModulo', 'caminho', 'número', true, 'Id do módulo.'),
                ], null],
                ['GET', '/aluno/cursos/{idCurso}/materiais', 'aluno', 'Materiais de apoio do curso (título, tipo/extensão, tamanho, se já foi concluído e link de download).', [
                    $p('idCurso', 'caminho', 'número', true, 'Id do curso.'),
                    $p('modulo', 'query', 'número', false, 'Filtra pelos materiais de um módulo.'),
                ], null],
                ['GET', '/aluno/materiais/{idMaterial}/download', 'aluno', 'Baixa o arquivo do material (precisa do token) e marca o material como concluído.', [
                    $p('idMaterial', 'caminho', 'número', true, 'Id do material.'),
                ], null],
                ['GET', '/aluno/cursos/{idCurso}/desempenho', 'aluno', 'Tela Desempenho: frequência (presenças, faltas, justificadas), materiais concluídos e últimas presenças.', [
                    $p('idCurso', 'caminho', 'número', true, 'Id do curso.'),
                ], null],
                ['POST', '/aluno/presencas/{idPresenca}/justificar', 'aluno', 'Envia a justificativa de uma falta para o professor avaliar.', [
                    $p('idPresenca', 'caminho', 'número', true, 'Id da presença (falta).'),
                    $p('motivo_justificativa', 'corpo', 'texto', true, 'Motivo da falta (10 a 1000 caracteres).'),
                ], null],
            ],
        ],
        [
            'id' => 'agenda',
            'titulo' => 'App do aluno — Agenda e reagendamento',
            'descricao' => '',
            'rotas' => [
                ['GET', '/aluno/agenda', 'aluno', 'Próxima aula e as aulas de todos os cursos matriculados.', [], null],
                ['POST', '/aluno/reagendamento/solicitar', 'aluno', 'Pede o reagendamento de uma aula. Se já existe pedido pendente para a mesma aula: 409.', [
                    $p('aula_id', 'corpo', 'número', true, 'Id da aula.'),
                    $p('motivo', 'corpo', 'texto', true, 'Motivo (10 a 500 caracteres).'),
                ], null],
            ],
        ],
        [
            'id' => 'atividades',
            'titulo' => 'App do aluno — Atividades',
            'descricao' => 'A resposta certa das questões nunca é enviada ao app.',
            'rotas' => [
                ['GET', '/aluno/atividades', 'aluno', 'Lista de atividades com total, concluídas e pendentes; cada uma com categoria, finalidade, professor, entrega, status (pendente, enviada, corrigida) e nota.', [], null],
                ['GET', '/aluno/atividades/{id}', 'aluno', 'Abre a atividade: questões (múltipla escolha com opções, ou texto), resposta do aluno, se acertou, feedback do professor e se ainda pode responder.', [
                    $p('id', 'caminho', 'número', true, 'Id da atividade.'),
                ], null],
                ['GET', '/aluno/atividades/{id}/audio', 'aluno', 'Áudio da atividade (precisa do token). Sem áudio: 404.', [
                    $p('id', 'caminho', 'número', true, 'Id da atividade.'),
                ], null],
                ['POST', '/aluno/atividades/{id}/responder', 'aluno', 'Envia as respostas de texto/múltipla escolha. Não mexe em questões de áudio. Atividade já corrigida: 409.', [
                    $p('id', 'caminho', 'número', true, 'Id da atividade.'),
                    $p('respostas', 'corpo', 'objeto', true, 'Respostas por questão: {"<id_questao>": "B"} ou {"<id_questao>": "texto"}.'),
                ], "Corpo:\n{\n  \"respostas\": { \"15\": \"B\", \"16\": \"I woke up early.\" }\n}"],
                ['POST', '/aluno/atividades/{id}/questoes/{idQuestao}/audio', 'aluno', 'Envia (ou substitui) a gravação de uma questão de áudio; marca a atividade como enviada na hora. Multipart, campo "audio". Atividade já corrigida: 409.', [
                    $p('id', 'caminho', 'número', true, 'Id da atividade.'),
                    $p('idQuestao', 'caminho', 'número', true, 'Id da questão (tipo "audio").'),
                    $p('audio', 'corpo', 'arquivo', true, 'Gravação (mp3, wav, ogg, m4a, aac, webm). Até 20 MB.'),
                ], null],
                ['GET', '/aluno/atividades/{id}/questoes/{idQuestao}/audio', 'aluno', 'Gravação que o próprio aluno enviou para essa questão. Sem gravação: 404.', [
                    $p('id', 'caminho', 'número', true, 'Id da atividade.'),
                    $p('idQuestao', 'caminho', 'número', true, 'Id da questão.'),
                ], null],
            ],
        ],
        [
            'id' => 'duvidas',
            'titulo' => 'App do aluno — Dúvidas, assistente e notificações',
            'descricao' => '',
            'rotas' => [
                ['GET', '/aluno/duvidas', 'aluno', 'Dúvidas enviadas pelo aluno e as respostas do professor.', [], null],
                ['POST', '/aluno/duvidas', 'aluno', 'Envia uma dúvida ao professor.', [
                    $p('assunto_duvida', 'corpo', 'texto', true, 'Assunto (até 150 caracteres).'),
                    $p('mensagem_duvida', 'corpo', 'texto', true, 'Mensagem (até 2000 caracteres).'),
                ], null],
                ['GET', '/aluno/chatbot/dados', 'aluno', 'Dados iniciais do assistente (nome do aluno e sugestões de perguntas).', [], null],
                ['POST', '/aluno/chatbot/mensagem', 'aluno', 'Pergunta ao assistente com IA. Limite: 10 por minuto e 200 por dia.', [
                    $p('mensagem', 'corpo', 'texto', true, 'Pergunta do aluno (até 4000 caracteres).'),
                ], null],
                ['GET', '/aluno/notificacoes', 'aluno', 'Sino do app: quantidade de não lidas e os últimos 50 avisos (mensagem, tela do app que abre, lida, data).', [], "{\n  \"success\": true,\n  \"data\": {\n    \"nao_lidas\": 2,\n    \"notificacoes\": [ { \"id\": 31, \"mensagem\": \"Nova aula: Verbo To Be — 12/12 às 18:00\", \"link\": \"/agenda\", \"lida\": false, \"data\": \"2026-10-10 10:17\" } ]\n  }\n}"],
                ['POST', '/aluno/notificacoes/{id}/lida', 'aluno', 'Marca um aviso como lido. Aviso de outro aluno: 404.', [
                    $p('id', 'caminho', 'número', true, 'Id do aviso.'),
                ], null],
                ['POST', '/aluno/notificacoes/lidas', 'aluno', 'Marca todos os avisos do aluno como lidos.', [], null],
            ],
        ],
        [
            'id' => 'gestao-alunos',
            'titulo' => 'Gestão — Alunos',
            'descricao' => 'Somente professor administrador.',
            'rotas' => [
                ['GET', '/alunos', 'admin', 'Lista os alunos (sem a senha), em ordem alfabética.', [
                    $p('status', 'query', 'texto', false, 'Filtra pela situação: EM CURSO, CONCLUIDO ou INATIVO.'),
                ], null],
                ['GET', '/alunos/{id}', 'admin', 'Dados de um aluno.', [
                    $p('id', 'caminho', 'número', true, 'Id do aluno.'),
                ], null],
                ['POST', '/alunos', 'admin', 'Cadastra um aluno. Pode ser multipart/form-data por causa da foto.', [
                    $p('nome_aluno', 'corpo', 'texto', true, 'Nome (até 255).'),
                    $p('email_aluno', 'corpo', 'e-mail', true, 'E-mail (único).'),
                    $p('senha_aluno', 'corpo', 'texto', true, 'Senha (mínimo 6).'),
                    $p('senha_aluno_confirmation', 'corpo', 'texto', true, 'Repetição da senha.'),
                    $p('telefone_aluno', 'corpo', 'texto', true, 'Telefone (até 20).'),
                    $p('curso_aluno', 'corpo', 'texto', true, 'Curso (até 100).'),
                    $p('data_nasc_aluno', 'corpo', 'data', true, 'Data de nascimento (AAAA-MM-DD).'),
                    $p('nivel_aluno', 'corpo', 'texto', true, 'Nível (até 50).'),
                    $p('status_aluno', 'corpo', 'texto', true, 'EM CURSO, CONCLUIDO ou INATIVO.'),
                    $p('foto_aluno', 'corpo', 'arquivo', false, 'Foto JPG ou PNG até 2 MB.'),
                ], null],
                ['PUT', '/alunos/{id}', 'admin', 'Atualiza um aluno. Mesmos campos do cadastro; a senha é opcional (só troca se enviada).', [
                    $p('id', 'caminho', 'número', true, 'Id do aluno.'),
                    $p('(campos)', 'corpo', '—', true, 'Os mesmos de POST /alunos; senha_aluno e foto_aluno opcionais.'),
                ], null],
                ['PATCH', '/alunos/{id}/status', 'admin', 'Muda só a situação do aluno. INATIVO bloqueia o login no site e no app.', [
                    $p('id', 'caminho', 'número', true, 'Id do aluno.'),
                    $p('status_aluno', 'corpo', 'texto', true, 'EM CURSO, CONCLUIDO ou INATIVO.'),
                ], null],
                ['DELETE', '/alunos/{id}', 'admin', 'Exclui o aluno junto com matrículas, presenças, respostas, dúvidas e demais registros dele.', [
                    $p('id', 'caminho', 'número', true, 'Id do aluno.'),
                ], null],
            ],
        ],
        [
            'id' => 'gestao-aulas',
            'titulo' => 'Gestão — Aulas',
            'descricao' => 'Qualquer professor logado. Cadastrar aula ativa avisa os alunos do curso no sino do app.',
            'rotas' => [
                ['GET', '/aulas', 'professor', 'Lista as aulas (mais recentes primeiro), com o professor.', [
                    $p('status', 'query', 'texto', false, 'ATIVO, INATIVO ou CANCELADO.'),
                    $p('curso', 'query', 'número', false, 'Id do curso.'),
                    $p('data', 'query', 'data', false, 'Dia da aula (AAAA-MM-DD).'),
                ], null],
                ['GET', '/aulas/resumo', 'professor', 'Totais: aulas cadastradas, ativas e de hoje.', [], "{\n  \"success\": true,\n  \"data\": { \"total_aulas\": 42, \"aulas_ativas\": 38, \"aulas_hoje\": 2 }\n}"],
                ['GET', '/aulas/{id}', 'professor', 'Dados de uma aula.', [
                    $p('id', 'caminho', 'número', true, 'Id da aula.'),
                ], null],
                ['POST', '/aulas', 'professor', 'Cadastra uma aula.', [
                    $p('titulo_aulas', 'corpo', 'texto', true, 'Título (até 100).'),
                    $p('descricao_aulas', 'corpo', 'texto', true, 'Descrição.'),
                    $p('data_aulas', 'corpo', 'data', true, 'Data (AAAA-MM-DD).'),
                    $p('hora_aulas', 'corpo', 'hora', true, 'Hora (HH:MM).'),
                    $p('id_professor', 'corpo', 'número', true, 'Id do professor.'),
                    $p('id_curso', 'corpo', 'número', true, 'Id do curso.'),
                    $p('cursos_aulas', 'corpo', 'texto', true, 'Nome do curso (até 100).'),
                    $p('status_aulas', 'corpo', 'texto', true, 'ATIVO, INATIVO ou CANCELADO.'),
                    $p('id_modulo', 'corpo', 'número', false, 'Id do módulo da aula.'),
                    $p('ordem_aula', 'corpo', 'número', false, 'Nº da aula no módulo (1 a 999).'),
                    $p('duracao_minutos', 'corpo', 'número', false, 'Duração em minutos (1 a 600).'),
                    $p('link_teams', 'corpo', 'URL', false, 'Link da aula ao vivo.'),
                ], null],
                ['PUT', '/aulas/{id}', 'professor', 'Atualiza uma aula (mesmos campos do cadastro).', [
                    $p('id', 'caminho', 'número', true, 'Id da aula.'),
                    $p('(campos)', 'corpo', '—', true, 'Os mesmos de POST /aulas.'),
                ], null],
                ['DELETE', '/aulas/{id}', 'professor', 'Exclui uma aula.', [
                    $p('id', 'caminho', 'número', true, 'Id da aula.'),
                ], null],
            ],
        ],
        [
            'id' => 'gestao-modulos',
            'titulo' => 'Gestão — Módulos',
            'descricao' => 'Consultar: qualquer professor. Criar, alterar e excluir: somente administrador.',
            'rotas' => [
                ['GET', '/modulos', 'professor', 'Lista os módulos com curso e nível, em ordem.', [
                    $p('curso', 'query', 'número', false, 'Id do curso.'),
                    $p('nivel', 'query', 'número', false, 'Id do nível.'),
                    $p('status', 'query', 'texto', false, 'ATIVO ou INATIVO.'),
                ], null],
                ['GET', '/modulos/{id}', 'professor', 'Dados de um módulo.', [
                    $p('id', 'caminho', 'número', true, 'Id do módulo.'),
                ], null],
                ['POST', '/modulos', 'admin', 'Cadastra um módulo.', [
                    $p('id_curso', 'corpo', 'número', true, 'Id do curso.'),
                    $p('id_nivel', 'corpo', 'número', true, 'Id do nível.'),
                    $p('ordem_modulo', 'corpo', 'número', true, 'Ordem do módulo (a partir de 1).'),
                    $p('nome_modulo', 'corpo', 'texto', true, 'Nome (até 100).'),
                    $p('carga_horaria_minutos', 'corpo', 'número', true, 'Carga horária em minutos.'),
                    $p('status_modulo', 'corpo', 'texto', true, 'ATIVO ou INATIVO.'),
                    $p('descricao_modulo', 'corpo', 'texto', false, 'Descrição.'),
                ], null],
                ['PUT', '/modulos/{id}', 'admin', 'Atualiza um módulo (mesmos campos do cadastro).', [
                    $p('id', 'caminho', 'número', true, 'Id do módulo.'),
                    $p('(campos)', 'corpo', '—', true, 'Os mesmos de POST /modulos.'),
                ], null],
                ['DELETE', '/modulos/{id}', 'admin', 'Exclui um módulo.', [
                    $p('id', 'caminho', 'número', true, 'Id do módulo.'),
                ], null],
            ],
        ],
    ];

    $total = collect($grupos)->sum(fn ($g) => count($g['rotas']));
    $porAcesso = collect($grupos)->flatMap(fn ($g) => $g['rotas'])->countBy(fn ($r) => $r[2]);
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Traduca Idiomas — Documentação v1</title>
    <meta name="description" content="Documentação da API REST do Traduca Idiomas (versão v1): rotas, métodos, autenticação e parâmetros.">
    <style>
        :root { --azul: #1d3a6e; --borda: #e3e7ee; }
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f5f6f8; color: #2b2f36; margin: 0; line-height: 1.55; }
        header { background: var(--azul); color: #fff; padding: 32px 20px; }
        header .conteudo, main { max-width: 1100px; margin: 0 auto; }
        header h1 { margin: 0 0 6px; font-size: 30px; }
        header p { margin: 4px 0; opacity: .95; }
        main { padding: 24px 20px 60px; }
        h2 { margin: 40px 0 12px; color: var(--azul); border-bottom: 2px solid var(--borda); padding-bottom: 6px; }
        h3 { margin: 0; font-size: 16px; }
        a { color: #0d6efd; }
        .card { background: #fff; border: 1px solid var(--borda); border-radius: 8px; padding: 18px 20px; margin-bottom: 14px; }
        .grade { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
        .info b { display: block; font-size: 12px; text-transform: uppercase; color: #6b7280; letter-spacing: .04em; }
        code { background: #eef0f4; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
        pre { background: #1e1e1e; color: #e8e8e8; padding: 14px; overflow-x: auto; border-radius: 6px; font-size: 13px; margin: 10px 0 0; }
        .metodo { display: inline-block; min-width: 64px; text-align: center; padding: 3px 8px; border-radius: 4px; color: #fff; font-weight: bold; font-size: 12px; margin-right: 6px; }
        .GET { background: #198754; } .POST { background: #0d6efd; } .PUT { background: #b35c00; }
        .PATCH { background: #6f42c1; } .DELETE { background: #dc3545; }
        .selo { display: inline-block; padding: 2px 9px; border-radius: 12px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .selo-publica { background: #e6f4ea; color: #146c2e; }
        .selo-aluno { background: #e7f0ff; color: #0b4fc4; }
        .selo-professor { background: #fff3e0; color: #9a4b00; }
        .selo-admin { background: #fde8ea; color: #a51d2d; }
        .rota-topo { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .rota-topo code { font-size: 14px; font-weight: bold; background: transparent; padding: 0; }
        .rota-topo .selo { margin-left: auto; }
        .tabela { overflow-x: auto; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; background: #fff; }
        th, td { text-align: left; padding: 7px 10px; border-bottom: 1px solid var(--borda); vertical-align: top; }
        th { background: #f0f3f8; font-size: 13px; }
        .sim { color: #a51d2d; font-weight: bold; } .nao { color: #6b7280; }
        .sumario a { text-decoration: none; }
        .aviso { background: #fff8e1; border-left: 4px solid #e0a800; padding: 10px 14px; border-radius: 4px; margin-top: 10px; }
        footer { text-align: center; color: #6b7280; font-size: 13px; padding: 20px; }
    </style>
</head>
<body>

<header>
    <div class="conteudo">
        <h1>API Traduca Idiomas</h1>
        <p>API REST do sistema da escola de idiomas <strong>Traduca Idiomas</strong>: alimenta o app do aluno
            (<strong>traduca-APP</strong>) e a gestão de alunos, aulas e módulos pelos professores.</p>
        <p>Versão <strong>v1</strong> · {{ $total }} rotas · respostas em JSON</p>
    </div>
</header>

<main>

    {{-- 1. Identificação --}}
    <h2 id="informacoes">1. Identificação da API</h2>
    <div class="card grade">
        <div class="info"><b>Nome</b>API Traduca Idiomas</div>
        <div class="info"><b>URL base</b><code>{{ $base }}</code></div>
        <div class="info"><b>Versão</b><code>v1</code> (prefixo <code>/api/v1</code>)</div>
        <div class="info"><b>Formato</b>JSON (UTF-8)</div>
        <div class="info"><b>Autenticação</b>Token Bearer (Laravel Sanctum), válido por 30 dias</div>
        <div class="info"><b>Back-end</b>Laravel (PHP) + MySQL</div>
    </div>
    <div class="card">
        Todas as rotas abaixo começam pela URL base. Exemplo: a rota <code>GET /aluno/cursos</code> fica em
        <code>{{ $base }}/aluno/cursos</code>.
        <div class="aviso">Envie sempre o header <code>Accept: application/json</code>. Assim, os erros também
            voltam em JSON (ex.: validação com código <code>422</code>) em vez de um redirecionamento.</div>
    </div>

    {{-- 2. Autenticação --}}
    <h2 id="autenticacao-como">2. Autenticação e níveis de acesso</h2>
    <div class="card">
        <ol>
            <li>Faça login em <code>POST /aluno/login</code> (aluno) ou <code>POST /professor/login</code> (professor).</li>
            <li>Guarde o <code>token</code> que vem na resposta.</li>
            <li>Nas rotas protegidas, envie o header <code>Authorization: Bearer SEU_TOKEN</code>.</li>
        </ol>
<pre>curl -X GET "{{ $base }}/aluno/cursos" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 12|aBcD..."</pre>
        <div class="tabela">
            <table>
                <tr><th>Selo</th><th>Quem pode usar</th><th>Rotas</th></tr>
                @foreach ($acessos as $chave => [$nome, $desc])
                    <tr>
                        <td><span class="selo selo-{{ $chave }}">{{ $nome }}</span></td>
                        <td>{{ $desc }}</td>
                        <td>{{ $porAcesso[$chave] ?? 0 }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="aviso">Token de aluno só acessa as rotas <code>/aluno/*</code>; token de professor só acessa as
            rotas de professor e gestão. Usar o token errado devolve <code>403</code>; sem token, <code>401</code>.
            Aluno com cadastro INATIVO não consegue entrar.</div>
    </div>

    {{-- 3. Resumo de todas as rotas --}}
    <h2 id="resumo">3. Resumo de todas as rotas ({{ $total }})</h2>
    <div class="card tabela sumario">
        <table>
            <tr><th>Método</th><th>Rota</th><th>Acesso</th><th>Para que serve</th></tr>
            @foreach ($grupos as $g)
                @foreach ($g['rotas'] as [$metodo, $caminho, $acesso, $descricao])
                    <tr>
                        <td><span class="metodo {{ $metodo }}">{{ $metodo }}</span></td>
                        <td><a href="#{{ $g['id'] }}"><code>{{ $caminho }}</code></a></td>
                        <td><span class="selo selo-{{ $acesso }}">{{ $acessos[$acesso][0] }}</span></td>
                        <td>{{ \Illuminate\Support\Str::before($descricao, '.') }}.</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </div>

    {{-- 4. Detalhe das rotas --}}
    <h2 id="rotas">4. Rotas em detalhe</h2>
    <p>Em cada rota: método HTTP, caminho (a partir da URL base), quem pode acessar e os parâmetros.
        Em <em>Onde</em>: <strong>caminho</strong> = faz parte da URL (ex.: <code>{id}</code>),
        <strong>query</strong> = depois do <code>?</code> na URL, <strong>corpo</strong> = JSON enviado no corpo.</p>

    @foreach ($grupos as $g)
        <h2 id="{{ $g['id'] }}" style="font-size:20px">{{ $g['titulo'] }}</h2>
        @if ($g['descricao'])
            <p>{{ $g['descricao'] }}</p>
        @endif

        @foreach ($g['rotas'] as [$metodo, $caminho, $acesso, $descricao, $parametros, $exemplo])
            <div class="card">
                <div class="rota-topo">
                    <span class="metodo {{ $metodo }}">{{ $metodo }}</span>
                    <code>/api/v1{{ $caminho }}</code>
                    <span class="selo selo-{{ $acesso }}">{{ $acessos[$acesso][0] }}</span>
                </div>
                <p style="margin:10px 0 0">{{ $descricao }}</p>

                @if (count($parametros))
                    <div class="tabela">
                        <table>
                            <tr><th>Parâmetro</th><th>Onde</th><th>Tipo</th><th>Obrigatório</th><th>Descrição</th></tr>
                            @foreach ($parametros as $par)
                                <tr>
                                    <td><code>{{ $par['nome'] }}</code></td>
                                    <td>{{ $par['onde'] }}</td>
                                    <td>{{ $par['tipo'] }}</td>
                                    <td class="{{ $par['obrig'] ? 'sim' : 'nao' }}">{{ $par['obrig'] ? 'sim' : 'não' }}</td>
                                    <td>{{ $par['desc'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <p style="margin:8px 0 0;color:#6b7280">Sem parâmetros (além do token, quando exigido).</p>
                @endif

                @if ($exemplo)
<pre>{{ $exemplo }}</pre>
                @endif
            </div>
        @endforeach
    @endforeach

    {{-- 5. Respostas e erros --}}
    <h2 id="respostas">5. Formato das respostas e códigos HTTP</h2>
    <div class="card">
        <p>Sucesso: <code>{"success": true, "data": ...}</code> (às vezes com <code>"message"</code>).
            Erro: <code>{"success": false, "message": "..."}</code>.</p>
<pre>{
  "message": "O campo email aluno é obrigatório.",
  "errors": {
    "email_aluno": ["O campo email aluno é obrigatório."]
  }
}</pre>
        <div class="tabela">
            <table>
                <tr><th>Código</th><th>Significado</th></tr>
                <tr><td><code>200</code></td><td>Sucesso.</td></tr>
                <tr><td><code>201</code></td><td>Criado (cadastros da gestão).</td></tr>
                <tr><td><code>401</code></td><td>Sem token, token inválido/vencido ou e-mail/senha inválidos no login.</td></tr>
                <tr><td><code>403</code></td><td>Sem permissão (token do tipo errado, não é administrador, módulo bloqueado, aluno inativo).</td></tr>
                <tr><td><code>404</code></td><td>Registro não encontrado (ou de outro aluno/curso).</td></tr>
                <tr><td><code>409</code></td><td>Conflito (ex.: reagendamento já pedido, atividade já corrigida).</td></tr>
                <tr><td><code>422</code></td><td>Erro de validação: veja o campo <code>errors</code>.</td></tr>
                <tr><td><code>429</code></td><td>Muitas requisições: login 5 por minuto; API 60 por minuto por token; assistente 10 por minuto e 200 por dia.</td></tr>
            </table>
        </div>
    </div>

</main>

<footer>
    API Traduca Idiomas · versão v1 · {{ $base }}
</footer>

</body>
</html>
