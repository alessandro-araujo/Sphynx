<?php
function validarItens($ar_itens, $ar_itens_banco)
{
    $erros = [];

    // Organizar dados do banco por produto
    $produtos_banco = [];

    foreach ($ar_itens_banco as $item_banco) {
        $pro_id = $item_banco['pro_id'];

        // Se o produto tem propriedades no banco
        if (!empty($item_banco['ppr_id'])) {
            if (!isset($produtos_banco[$pro_id])) {
                $produtos_banco[$pro_id] = [];
            }

            $produtos_banco[$pro_id][] = [
                'ppr_id' => $item_banco['ppr_id'],
                'ppr_nome' => $item_banco['ppr_nome'],
                'opc_id' => $item_banco['opc_id'],
                'opc_nome' => $item_banco['opc_nome']
            ];
        }
    }
//    var_dump($produtos_banco);die;

    // Validar cada produto enviado pelo usuário
    foreach ($ar_itens as $index => $produto_usuario) {
        $produto_id = $produto_usuario['id'];
        $propriedades_usuario = $produto_usuario['propriedades'] ?? [];

        // Se o produto não existe no banco, pular validação
        if (!isset($produtos_banco[$produto_id])) {
            continue;
        }

        // Agrupar propriedades do banco por ID/Nome
        $propriedades_banco = [];
        foreach ($produtos_banco[$produto_id] as $prop_banco) {
            $ppr_id = $prop_banco['ppr_id'];
            $ppr_nome = $prop_banco['ppr_nome'];

            if (!isset($propriedades_banco[$ppr_id])) {
                $propriedades_banco[$ppr_id] = [
                    'nome' => $ppr_nome,
                    'opcoes' => []
                ];
            }

            $propriedades_banco[$ppr_id]['opcoes'][] = [
                'id' => $prop_banco['opc_id'],
                'nome' => $prop_banco['opc_nome']
            ];
        }

        // Verificar se todas as propriedades obrigatórias estão presentes
        $propriedades_encontradas = [];

        foreach ($propriedades_usuario as $prop_usuario) {
            $prop_id_usuario = $prop_usuario['id'];
            $opcao_usuario = $prop_usuario['id_opcao'];

            // Verificar se a propriedade existe no banco (por ID ou Nome)
            $propriedade_encontrada = false;
            $propriedade_banco_info = null;

            foreach ($propriedades_banco as $ppr_id => $prop_info) {
                if ($ppr_id == $prop_id_usuario || $prop_info['nome'] == $prop_id_usuario) {
                    $propriedade_encontrada = true;
                    $propriedade_banco_info = $prop_info;
                    $propriedades_encontradas[] = $ppr_id;
                    break;
                }
            }

            if (!$propriedade_encontrada) {
                $erros[] = "Produto {$produto_id} (índice {$index}): Propriedade '{$prop_id_usuario}' não existe no banco de dados";
                continue;
            }

            // Verificar se a opção existe para esta propriedade
            $opcao_encontrada = false;
            foreach ($propriedade_banco_info['opcoes'] as $opcao_banco) {
                if ($opcao_banco['id'] == $opcao_usuario || $opcao_banco['nome'] == $opcao_usuario) {
                    $opcao_encontrada = true;
                    break;
                }
            }

            if (!$opcao_encontrada) {
                $erros[] = "Produto {$produto_id} (índice {$index}): Opção '{$opcao_usuario}' não existe para a propriedade '{$prop_id_usuario}'";
            }
        }

        // Verificar se todas as propriedades obrigatórias foram fornecidas
        $propriedades_obrigatorias = array_keys($propriedades_banco);
        $propriedades_faltantes = array_diff($propriedades_obrigatorias, $propriedades_encontradas);

        foreach ($propriedades_faltantes as $prop_faltante) {
            $nome_propriedade = $propriedades_banco[$prop_faltante]['nome'];
            $erros[] = "Produto {$produto_id} (índice {$index}): Propriedade obrigatória '{$nome_propriedade}' (ID: {$prop_faltante}) não foi fornecida";
        }
    }

    return [
        'valido' => empty($erros),
        'erros' => $erros
    ];
}
