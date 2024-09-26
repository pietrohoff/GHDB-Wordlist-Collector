<?php
// Caminho para o arquivo JSON
$jsonFile = 'Untitled-1.json';

// Lê o conteúdo do arquivo JSON
$jsonData = file_get_contents($jsonFile);

// Decodifica o JSON em um array
$data = json_decode($jsonData, true);

// Inicializa um array para armazenar os títulos das categorias
$categories = [];

// Percorre todos os itens dentro de "data"
foreach ($data['data'] as $item) {
    // Verifica se existe a chave 'category' e 'cat_title'
    if (isset($item['category']['cat_title'])) {
        // Adiciona o valor de 'cat_title' ao array de categorias
        $categories[$item['category']['cat_title']][] = $item['url_title'];
    }
}

// Remove duplicatas das categorias
$categories = array_unique(array_keys($categories));

// Cria um arquivo txt para cada categoria e adiciona as URLs
foreach ($categories as $category) {
    // Substitui caracteres inválidos no nome do arquivo
    $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $category) . '.txt';
    
    // Cria e abre o arquivo
    $file = fopen($fileName, 'w');
    
    // Escreve o título da categoria no arquivo
    // fwrite($file, "Categoria: $category" . PHP_EOL);
    
    // Adiciona as URLs formatadas
    foreach ($data['data'] as $item) {
        if (isset($item['category']['cat_title']) && $item['category']['cat_title'] === $category) {
            // Remove as tags HTML do url_title
            $cleanUrl = strip_tags($item['url_title']);
            
            // Escreve a URL formatada no arquivo
            fwrite($file, $cleanUrl . PHP_EOL);
        }
    }
    
    // Fecha o arquivo
    fclose($file);
}

echo "Arquivos de categorias criados e URLs adicionadas com sucesso.";
