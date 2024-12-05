<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
    <h1>Conversor de Moedas</h1>
    <?php
    // Cotação vinda da API do Banco Central
    $inicio = date("m-d-Y", strtotime("-7 days"));
    $fim = date("m-d-Y");

    $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\''.$inicio.'\'&@dataFinalCotacao=\''.$fim.'\'&$top=1&$orderby=dataHoraCotacao%20desc&$format=json&$select=cotacaoCompra,dataHoraCotacao';

    $dados = json_decode(file_get_contents($url), true);
    $cotacao = $dados["value"][0]["cotacaoCompra"];

    // Quanto $$ você tem?
    $real = $_REQUEST["din"] ?? 0;

    // Equivalência em dólar
    $dolar = $real / $cotacao;

    // Mostrar o resultado
    // echo "Seus R$" . number_format($real, 2, ",", ".") . " equivalem a US$" . number_format($dolar, 2, ".", ",");

    // Formatação de moedas com internacionalização!
    // Biblioteca intl (Internationalization PHP)

    $padrao = numfmt_create("pt_BR", NumberFormatter::CURRENCY);

    echo "<h3> Seus " . numfmt_format_currency($padrao, $real, "BRL") . 
        " equivalem a " . numfmt_format_currency($padrao, $dolar, "USD") . "</h3>";
    
    // Convertendo a data final ($fim) para o formato brasileiro
    $dataFormatada = DateTime::createFromFormat("m-d-Y", $fim)->format("d/m/Y");

    echo "<p> Valor referente a data: $dataFormatada</p>";    
    ?>

    <button onclick="javascript:history.go(-1)">Voltar</button>

</main>
    
</body>
</html>