<?php

/*
|--------------------------------------------------------------------------
| Perguntas do teste de nivelamento
|--------------------------------------------------------------------------
| 12 perguntas por idioma, em ordem crescente de dificuldade (2 por nível CEFR).
| "correta" é a posição (começando em 0) da alternativa certa em "alternativas".
| "explicacao" aparece para o aluno logo depois que ele responde.
|
| Importante: estas respostas NUNCA são enviadas ao navegador antes de a pessoa
| responder; a correção acontece no servidor (QuizController).
*/

return [

    'ingles' => [
        [
            'nivel' => 'A1',
            'enunciado' => 'She ______ a student.',
            'alternativas' => ['am', 'are', 'is', 'be'],
            'correta' => 2,
            'explicacao' => 'Com he/she/it usamos "is": She is a student.',
        ],
        [
            'nivel' => 'A1',
            'enunciado' => 'Excuse me. ______ a bank near here?',
            'alternativas' => ['Is there', 'There is', 'Have', 'Are there'],
            'correta' => 0,
            'explicacao' => 'Para perguntar se algo existe no singular, usamos "Is there ...?".',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'I ______ to the gym yesterday.',
            'alternativas' => ['go', 'goed', 'was go', 'went'],
            'correta' => 3,
            'explicacao' => '"Go" é irregular: o passado é "went".',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'My brother is ______ than me.',
            'alternativas' => ['taller', 'tall', 'more tall', 'the tallest'],
            'correta' => 0,
            'explicacao' => 'Adjetivos curtos formam o comparativo com -er: taller than.',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'I ______ English for three years.',
            'alternativas' => ['study', 'am studying', 'have studied', 'studied'],
            'correta' => 2,
            'explicacao' => 'Ação que começou no passado e continua: present perfect ("have studied" + for).',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'If it rains tomorrow, we ______ at home.',
            'alternativas' => ['stay', 'would stay', 'will stay', 'stayed'],
            'correta' => 2,
            'explicacao' => 'Primeira condicional: if + presente, will + verbo.',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'By the time we arrived, the movie ______.',
            'alternativas' => ['already started', 'has already started', 'was already starting', 'had already started'],
            'correta' => 3,
            'explicacao' => 'Ação anterior a outro momento do passado: past perfect ("had started").',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'She suggested ______ a taxi.',
            'alternativas' => ['to take', 'take', 'taking', 'took'],
            'correta' => 2,
            'explicacao' => 'Depois de "suggest" usamos o gerúndio: suggested taking.',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'Hardly ______ the office when the phone rang.',
            'alternativas' => ['he had left', 'did he left', 'had he left', 'he left'],
            'correta' => 2,
            'explicacao' => 'Depois de "hardly" no início da frase, o auxiliar vem antes do sujeito: had he left.',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'The project was delayed owing ______ unforeseen circumstances.',
            'alternativas' => ['of', 'to', 'from', 'by'],
            'correta' => 1,
            'explicacao' => '"Owing to" é a locução fixa, com o sentido de "devido a".',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => 'Had it not been for your help, I ______ the deadline.',
            'alternativas' => ['would miss', 'will miss', 'would have missed', 'had missed'],
            'correta' => 2,
            'explicacao' => 'Terceira condicional invertida: had + particípio, would have + particípio.',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => 'She is known for her ______ style: she never wastes a single word.',
            'alternativas' => ['verbose', 'loquacious', 'laconic', 'garrulous'],
            'correta' => 2,
            'explicacao' => '"Laconic" descreve quem usa poucas palavras; os demais indicam o contrário.',
        ],
    ],

    'portugues' => [
        [
            'nivel' => 'A1',
            'enunciado' => 'Eu ______ estudante.',
            'alternativas' => ['é', 'estou', 'sou', 'somos'],
            'correta' => 2,
            'explicacao' => 'Com "eu" usamos "sou" (verbo ser) para dizer quem somos.',
        ],
        [
            'nivel' => 'A1',
            'enunciado' => 'Ela ______ no Brasil.',
            'alternativas' => ['moro', 'mora', 'moras', 'moram'],
            'correta' => 1,
            'explicacao' => 'Com "ela" (3ª pessoa do singular), o verbo morar fica "mora".',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'Ontem eu ______ ao mercado e comprei frutas.',
            'alternativas' => ['vou', 'irei', 'fui', 'ir'],
            'correta' => 2,
            'explicacao' => 'Ação concluída no passado: pretérito perfeito de "ir" → "fui".',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'A Maria é ______ do que a Ana.',
            'alternativas' => ['alta', 'mais alta', 'altíssima', 'tão alta'],
            'correta' => 1,
            'explicacao' => 'O comparativo de superioridade usa "mais ... do que".',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'Quando eu era criança, ______ muito na rua.',
            'alternativas' => ['brinquei', 'brincarei', 'brincava', 'brinco'],
            'correta' => 2,
            'explicacao' => 'Hábito no passado pede o pretérito imperfeito: "brincava".',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'Se eu ______ mais tempo, viajaria com mais frequência.',
            'alternativas' => ['tenho', 'terei', 'tivesse', 'ter'],
            'correta' => 2,
            'explicacao' => 'Hipótese irreal: "se" + imperfeito do subjuntivo ("tivesse") + futuro do pretérito.',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'Espero que ele ______ a tempo.',
            'alternativas' => ['chega', 'chegue', 'chegou', 'chegaria'],
            'correta' => 1,
            'explicacao' => '"Esperar que" pede o presente do subjuntivo: "chegue".',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'Ele disse que ______ amanhã se tivesse tempo.',
            'alternativas' => ['veio', 'viria', 'vem', 'venha'],
            'correta' => 1,
            'explicacao' => 'Com "se tivesse tempo", a oração principal usa o futuro do pretérito: "viria".',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'Embora ______ cansado, ele continuou trabalhando.',
            'alternativas' => ['estava', 'esteve', 'está', 'estivesse'],
            'correta' => 3,
            'explicacao' => '"Embora" exige subjuntivo; aqui, o imperfeito: "estivesse".',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'Quando ______ o relatório, avise-me.',
            'alternativas' => ['terminar', 'terminou', 'terminará', 'terminaria'],
            'correta' => 0,
            'explicacao' => 'Ação futura depois de "quando": futuro do subjuntivo ("terminar").',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => 'Ela aspira ______ um cargo de direção.',
            'alternativas' => ['em', 'por', 'de', 'a'],
            'correta' => 3,
            'explicacao' => '"Aspirar" no sentido de desejar rege a preposição "a": aspirar a algo.',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => '______ vista o prazo apertado, precisamos agir já.',
            'alternativas' => ['Há', 'Haja', 'Haver', 'Havia'],
            'correta' => 1,
            'explicacao' => '"Haja vista" é a expressão fixa e significa "considerando".',
        ],
    ],

    'italiano' => [
        [
            'nivel' => 'A1',
            'enunciado' => 'Ciao! Io ______ Marco.',
            'alternativas' => ['è', 'sei', 'sono', 'siamo'],
            'correta' => 2,
            'explicacao' => 'Com "io" usamos "sono" (verbo essere).',
        ],
        [
            'nivel' => 'A1',
            'enunciado' => 'Noi ______ al bar ogni mattina.',
            'alternativas' => ['vado', 'andiamo', 'vai', 'vanno'],
            'correta' => 1,
            'explicacao' => 'Com "noi", o verbo andare fica "andiamo".',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'Ieri ______ al cinema con i miei amici.',
            'alternativas' => ['ho andato', 'vado', 'sono andato', 'andando'],
            'correta' => 2,
            'explicacao' => '"Andare" forma o passato prossimo com o auxiliar "essere": sono andato.',
        ],
        [
            'nivel' => 'A2',
            'enunciado' => 'Mi ______ molto questo libro.',
            'alternativas' => ['piaci', 'piacciono', 'piacere', 'piace'],
            'correta' => 3,
            'explicacao' => 'O verbo concorda com o que agrada (um livro, singular): "piace".',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'Quando ero piccolo, ______ spesso al mare con i nonni.',
            'alternativas' => ['sono andato', 'andavo', 'ero andato', 'andrò'],
            'correta' => 1,
            'explicacao' => 'Hábito no passado: imperfetto ("andavo").',
        ],
        [
            'nivel' => 'B1',
            'enunciado' => 'Se avessi più tempo, ______ un corso di inglese.',
            'alternativas' => ['farò', 'faccio', 'farei', 'fai'],
            'correta' => 2,
            'explicacao' => 'Período hipotético: congiuntivo imperfetto + condizionale presente ("farei").',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'Penso che Luca ______ già partito.',
            'alternativas' => ['è', 'era', 'fosse', 'sia'],
            'correta' => 3,
            'explicacao' => '"Penso che" pede o congiuntivo; com verbo principal no presente: "sia".',
        ],
        [
            'nivel' => 'B2',
            'enunciado' => 'Hai preso le chiavi? — Sì, ______ ho prese.',
            'alternativas' => ['le', 'li', 'gli', 'ne'],
            'correta' => 0,
            'explicacao' => 'Pronome direto feminino plural "le", que concorda com o particípio "prese".',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'Benché ______ stanco, ha continuato a lavorare.',
            'alternativas' => ['era', 'è', 'sarebbe', 'fosse'],
            'correta' => 3,
            'explicacao' => '"Benché" exige o congiuntivo: fosse.',
        ],
        [
            'nivel' => 'C1',
            'enunciado' => 'Se non ______ piovuto, saremmo andati in gita.',
            'alternativas' => ['ha', 'avesse', 'avrebbe', 'aveva'],
            'correta' => 1,
            'explicacao' => 'Terceiro tipo de período hipotético: congiuntivo trapassato ("avesse piovuto").',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => 'Il governo ha ______ nuove misure per contenere l\'inflazione.',
            'alternativas' => ['varcato', 'varato', 'vagato', 'variato'],
            'correta' => 1,
            'explicacao' => '"Varare misure" é a expressão usada para aprovar/lançar medidas.',
        ],
        [
            'nivel' => 'C2',
            'enunciado' => 'Quel politico è noto per la sua ______: non risponde mai direttamente alle domande.',
            'alternativas' => ['evidenza', 'evasività', 'evasione', 'evoluzione'],
            'correta' => 1,
            'explicacao' => '"Evasività" é a característica de quem foge de respostas diretas.',
        ],
    ],

];
