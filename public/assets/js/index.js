$(document).ready(function() {

    // Exibir o loader antes de começar o carregamento
    $('#loading-spinner').show();

    // Array com IDs dos filmes e caminhos das imagens
    const movieImages = {
        1: 'assets/images/a_new_hope.jpg',
        2: 'assets/images/the_empire_strikes_back.jpg',
        3: 'assets/images/return_of_the_jedi.jpg',
        4: 'assets/images/the_phantom_menace.jpg',
        5: 'assets/images/attack_of_the_clones.jpg',
        6: 'assets/images/revenge_of_the_sith.jpg',
    };

    // Requisição para o endpoint
    $.get('http://localhost:8000/films', function(data) {
         // Exemplo de uso:
        logRequest("GET", "/films");
        const films = data.catalog;
        
        // Loop pelos filmes e exibição no catálogo
        films.forEach(film => {
            const filmId = film.id;
            const filmImage = movieImages[filmId] || 'assets/images/default.jpg'; // Caso não encontre imagem, usa uma imagem padrão
            const favorite = film.favorite; // true or false
            const favoriteText = favorite ? 'Desfavoritar' : 'Adicionar aos Favoritos';
            
            const movieCard = `
                <div class="movie-card ${favorite ? 'favoritado' : ''}" data-id="${filmId}">
                    <a href="view.php?id=${filmId}"> 
                    <img src="${filmImage}" alt="${film.title}">
                    <h5 class="mt-2">${film.title}</h5>
                    </a>
                    <p>Data de lançamento: ${film.release_date}</p>
                    <button class="favorite-btn">${favoriteText}</button>
                </div>
            `;
            $('#movie-list').append(movieCard);

            // Evento de clique no botão de favorito
            $(`.movie-card[data-id="${filmId}"] .favorite-btn`).on('click', function() {
                // Altera o estado de favorito do filme
                film.favorite = !film.favorite;

                // Atualiza a classe do card dinamicamente
                $(this).closest('.movie-card').toggleClass('favoritado', film.favorite);

                // Atualiza o texto do botão
                const newText = film.favorite ? 'Desfavoritar' : 'Adicionar aos Favoritos';
                $(this).text(newText);

                // Envia a requisição DELETE se o filme for desfavoritado
                if (!film.favorite) {
                    logRequest("DELETE", `/favorites/${filmId}`);
                    $.ajax({
                        url: `http://localhost:8000/favorites/${filmId}`,
                        type: 'DELETE',
                        success: function(response) {
                            showAlert(`Filme ${film.title} removido dos favoritos`, 'alert-info');
                        },
                        error: function(error) {
                            showAlert('Erro ao desfavoritar o filme', 'alert-danger');
                        }
                    });
                } else {
                    logRequest("POST", `/favorites/${filmId}`);
                    $.ajax({
                        url: 'http://localhost:8000/favorites',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ "id": filmId }),
                        success: function(response) {
                            showAlert(`Filme ${film.title} adicionado aos favoritos`);
                        },
                        error: function(xhr, status, error) {
                            alert('Erro: ' + xhr.responseText);
                        }
                    });
                }
            });
        });

        // Esconde o loader e mostra o conteúdo
        $('#loading-spinner').hide();

        // Evento de clique no botão de favoritar
        $('.favorite-btn').on('click', function() {
            const movieCard = $(this).closest('.movie-card');
            const movieId = movieCard.data('id');
        });
    }).fail(function() {
        alert('Erro ao carregar os filmes.');
        $('#loading-spinner').hide(); // Esconde o loader em caso de erro
    });
});