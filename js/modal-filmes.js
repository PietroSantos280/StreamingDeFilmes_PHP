document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modal-filme');
    if (!modal) return;

    const tituloEl = document.getElementById('modal-titulo');
    const metaEl = document.getElementById('modal-meta');
    const generoEl = document.getElementById('modal-genero');
    const avaliacaoEl = document.getElementById('modal-avaliacao');
    const sinopseEl = document.getElementById('modal-sinopse');
    const comentariosListaEl = document.getElementById('modal-comentarios');

    const btnEditar = document.getElementById('btn-editar');
    const btnExcluir = document.getElementById('btn-excluir');
    const btnAvaliar = document.getElementById('btn-avaliar');
    const btnFechar = document.getElementById('btn-fechar-modal');

    function renderComentarios(comentarios) {
        comentariosListaEl.innerHTML = '';

        if (!Array.isArray(comentarios) || comentarios.length === 0) {
            comentariosListaEl.innerHTML = '<p class="sem-comentarios">Ainda não há avaliações para este filme.</p>';
            return;
        }

        comentarios.forEach((item) => {
            const card = document.createElement('div');
            card.className = 'comentario-item';

            const topo = document.createElement('div');
            topo.className = 'comentario-topo';

            const autor = document.createElement('strong');
            autor.textContent = item.usuario || 'Usuário';

            const nota = document.createElement('span');
            nota.className = 'comentario-nota';
            nota.textContent = `★ ${item.score}/5`;

            topo.appendChild(autor);
            topo.appendChild(nota);

            const texto = document.createElement('p');
            texto.textContent = item.comentario || 'Sem comentário.';

            card.appendChild(topo);
            card.appendChild(texto);
            comentariosListaEl.appendChild(card);
        });
    }

    function abrirModal(filme) {
        tituloEl.textContent = filme.titulo;
        metaEl.textContent = `${filme.ano} • ${filme.duracao} min`;
        generoEl.textContent = filme.genero;
        avaliacaoEl.textContent = filme.avaliacao;
        sinopseEl.textContent = filme.sinopse;

        btnEditar.href = `atualizar.php?id=${filme.id}`;
        btnExcluir.href = `excluir.php?id=${filme.id}`;
        btnAvaliar.href = `../avaliacoes/avaliar.php?filme_id=${filme.id}`;

        renderComentarios(filme.comentarios);

        modal.classList.add('aberto');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function fecharModal() {
        modal.classList.remove('aberto');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.btn-ver-mais').forEach((btn) => {
        btn.addEventListener('click', () => {
            try {
                const dados = JSON.parse(btn.dataset.filme || '{}');
                abrirModal(dados);
            } catch (err) {
                console.error('Erro ao ler dados do filme:', err);
            }
        });
    });

    btnFechar.addEventListener('click', fecharModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            fecharModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('aberto')) {
            fecharModal();
        }
    });
});