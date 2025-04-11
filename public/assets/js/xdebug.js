$(document).ready(function() {
    let currentPage = 1;
    
    function loadLogs(page = 1, search = '') {
        $('#log-table').html('<tr><td colspan="6" class="text-center">Carregando...</td></tr>');
        $.get(`http://localhost:8000/logs/${page}`, function(data) {
            if (!data || !data.clients.length) {
                $('#log-table').html('<tr><td colspan="6" class="text-danger text-center">Nenhum log encontrado.</td></tr>');
                return;
            }
            
            let logsHtml = '';
            data.clients.forEach(log => {
                if (log.route.includes(search) || log.ip.includes(search)) {
                    logsHtml += `<tr>
                        <td>${log.id}</td>
                        <td>${log.method}</td>
                        <td>${log.route}</td>
                        <td>${log.ip}</td>
                        <td>${log.user_agent}</td>
                        <td>${log.date_created}</td>
                    </tr>`;
                }
            });
            $('#log-table').html(logsHtml || '<tr><td colspan="6" class="text-danger text-center">Nenhum resultado encontrado.</td></tr>');
            
            let paginationHtml = '';
            for (let i = 1; i <= data.totalPages; i++) {
                paginationHtml += `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            $('#pagination').html(paginationHtml);
        }).fail(function() {
            $('#log-table').html('<tr><td colspan="6" class="text-danger text-center">Erro ao carregar os logs.</td></tr>');
        });
    }

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        currentPage = $(this).data('page');
        loadLogs(currentPage, $('#search').val());
    });
    
    $('#search').on('keyup', function() {
        loadLogs(currentPage, $(this).val());
    });
    
    loadLogs();
});