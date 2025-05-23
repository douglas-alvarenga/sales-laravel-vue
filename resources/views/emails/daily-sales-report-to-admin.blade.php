<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório Diário de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
            padding: 20px;
            color: #333;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h1 {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .info {
            font-size: 16px;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #2c3e50;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Relatório Diário de Vendas</h1>

        <div class="info">
            <span class="highlight">Data:</span> {{ date('d/m/Y', strtotime($date)) }}<br />
            <span class="highlight">Vendas realizadas:</span> {{ $salesCount }}<br />
            <span class="highlight">Ticket médio das vendas:</span> R$ {{ ($salesCount > 0) ? number_format(($totalAmount / $salesCount), 2, ',', '.') : '0,00' }}<br />
            <span class="highlight">Valor total das vendas:</span> R$ {{ number_format($totalAmount, 2, ',', '.') }}<br />
            <span class="highlight">Valor total de comissões:</span> R$ {{ number_format($totalCommission, 2, ',', '.') }}<br />
        </div>

        <div class="footer">
            Este é um relatório automático enviado pelo sistema.
        </div>
    </div>
</body>
</html>
