<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug de Logs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/view.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="icon" type="image/png" href="assets/images/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/functionLog.js"></script>
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container mt-4" style="max-width: 80vw;">
        <h2 class="mb-3">Logs do Sistema</h2>
        
        <input type="text" id="search" class="form-control mb-3" placeholder="Pesquisar ROTAS...">
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Método</th>
                        <th>Rota</th>
                        <th>IP</th>
                        <th>User Agent</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody id="log-table">
                    <!-- Logs serão carregados aqui -->
                </tbody>
            </table>
        </div>
        
        <nav>
            <ul class="pagination" id="pagination">
                <!-- Paginação será carregada aqui -->
            </ul>
        </nav>
    </div>
    <script src="assets/js/xdebug.js"></script>
</body>
</html>
