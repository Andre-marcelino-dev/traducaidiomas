<?php

/*
|--------------------------------------------------------------------------
| Teste de nivelamento (quiz)
|--------------------------------------------------------------------------
| Configurações do quiz público. As perguntas ficam em config/quiz_perguntas.php.
| Para incluir um idioma novo: adicione aqui em "idiomas" e crie as 12 perguntas
| dele em quiz_perguntas.php (mesma chave).
*/

return [

    // Quantidade de perguntas por teste (2 para cada nível, de A1 a C2).
    'total_perguntas' => 12,

    // Chave = slug usado na URL (/quiz/{idioma}/...).
    'idiomas' => [
        'ingles' => [
            'nome'      => 'Inglês',
            'descricao' => 'Para trabalho, viagens, entrevistas e fluência no dia a dia.',
            'bandeira'  => 'traducaidiomas/img/icons8-united-kingdom-48.png',
        ],
        'portugues' => [
            'nome'      => 'Português',
            'descricao' => 'Português para quem quer comunicar com clareza e segurança.',
            'bandeira'  => 'traducaidiomas/img/icons8-brazil-48.png',
        ],
        'italiano' => [
            'nome'      => 'Italiano',
            'descricao' => 'Do primeiro "ciao" à conversa fluente, com foco no uso real.',
            'bandeira'  => 'traducaidiomas/img/icons8-italy-48.png',
        ],
    ],

    // Níveis CEFR (Quadro Europeu Comum de Referência). "ate" = maior pontuação
    // (acertos) que ainda cai nesse nível; a ordem importa (do menor para o maior).
    'niveis' => [
        'A1' => [
            'rotulo'    => 'Iniciante',
            'ate'       => 2,
            'descricao' => 'Entende e usa expressões muito básicas: se apresentar, pedir e dar informações simples.',
            'curso'     => 'Primeiros passos em :idioma: vocabulário essencial, apresentações e frases do dia a dia.',
            'sugestao'  => 'Comece do zero, no seu ritmo. O foco é construir base sólida de vocabulário e pronúncia.',
        ],
        'A2' => [
            'rotulo'    => 'Básico',
            'ate'       => 4,
            'descricao' => 'Se comunica em situações rotineiras: compras, família, trabalho e lugares.',
            'curso'     => 'Conversas do cotidiano em :idioma: passado, planos e situações práticas de rotina.',
            'sugestao'  => 'Você já tem base. Agora vale ganhar confiança falando e ampliando o vocabulário.',
        ],
        'B1' => [
            'rotulo'    => 'Intermediário',
            'ate'       => 6,
            'descricao' => 'Lida com a maioria das situações de viagem e conta experiências, planos e opiniões.',
            'curso'     => 'Comunicação autônoma em :idioma: narrar fatos, opinar e resolver imprevistos.',
            'sugestao'  => 'Você se vira bem. O próximo salto é fluência: mais prática oral e estruturas variadas.',
        ],
        'B2' => [
            'rotulo'    => 'Intermediário superior',
            'ate'       => 8,
            'descricao' => 'Interage com espontaneidade e entende textos complexos sobre assuntos concretos e abstratos.',
            'curso'     => 'Fluência prática em :idioma: reuniões, debates, textos e argumentação.',
            'sugestao'  => 'Seu nível é sólido. Refine precisão, naturalidade e vocabulário profissional.',
        ],
        'C1' => [
            'rotulo'    => 'Avançado',
            'ate'       => 10,
            'descricao' => 'Se expressa com fluidez e precisão, inclusive em contextos acadêmicos e profissionais.',
            'curso'     => 'Domínio avançado de :idioma: nuances, registro formal e comunicação estratégica.',
            'sugestao'  => 'Você tem ótimo domínio. O trabalho agora é lapidar nuances, estilo e repertório.',
        ],
        'C2' => [
            'rotulo'    => 'Proficiente',
            'ate'       => 12,
            'descricao' => 'Compreende praticamente tudo e se expressa com naturalidade, mesmo em temas complexos.',
            'curso'     => 'Proficiência em :idioma: refinamento, redação sofisticada e preparação para exames.',
            'sugestao'  => 'Nível de proficiência. Podemos focar em refinamento, escrita e exames de certificação.',
        ],
    ],
];
