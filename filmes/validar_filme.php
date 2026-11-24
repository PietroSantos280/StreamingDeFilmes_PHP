<?php
// Valida os dados do formulário de filme (cadastrar e atualizar).
// Retorna [dados limpos, mensagem de erro ou null].
function validar_filme(array $post): array
{
    $dados = [
        'titulo'          => trim($post['titulo'] ?? ''),
        'sinopse'         => trim($post['sinopse'] ?? ''),
        'ano_lancamento'  => trim($post['ano_lancamento'] ?? ''),
        'duracao_minutos' => trim($post['duracao_minutos'] ?? ''),
        'avaliacao'       => str_replace(',', '.', trim($post['avaliacao'] ?? '')),
        'genero'          => trim($post['genero'] ?? ''),
    ];

    foreach ($dados as $valor) {
        if ($valor === '') {
            return [$dados, 'Preencha todos os campos obrigatórios.'];
        }
    }

    if (mb_strlen($dados['titulo']) > 150) {
        return [$dados, 'O título pode ter no máximo 150 caracteres.'];
    }
    if (mb_strlen($dados['genero']) > 50) {
        return [$dados, 'O gênero pode ter no máximo 50 caracteres.'];
    }

    $ano = filter_var($dados['ano_lancamento'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1901, 'max_range' => 2155]]);
    if ($ano === false) {
        return [$dados, 'Informe um ano de lançamento entre 1901 e 2155.'];
    }

    $duracao = filter_var($dados['duracao_minutos'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 999]]);
    if ($duracao === false) {
        return [$dados, 'Informe uma duração entre 1 e 999 minutos.'];
    }

    $avaliacao = filter_var($dados['avaliacao'], FILTER_VALIDATE_FLOAT);
    if ($avaliacao === false || $avaliacao < 0 || $avaliacao > 5) {
        return [$dados, 'Informe uma avaliação entre 0 e 5.'];
    }

    $dados['ano_lancamento'] = $ano;
    $dados['duracao_minutos'] = $duracao;
    $dados['avaliacao'] = round($avaliacao, 2);

    return [$dados, null];
}

// Escapa um valor do formulário para devolver no campo
function valor_campo(array $dados, string $campo): string
{
    return htmlspecialchars((string) ($dados[$campo] ?? ''));
}
