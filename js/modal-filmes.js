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
    let ultimoFoco = null;

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

        // Editar e Excluir só existem na página para administradores
        if (btnEditar) btnEditar.href = `atualizar.php?id=${filme.id}`;
        if (btnExcluir) btnExcluir.href = `excluir.php?id=${filme.id}`;
        btnAvaliar.href = `../avaliacoes/avaliar.php?filme_id=${filme.id}`;

        renderComentarios(filme.comentarios);

        ultimoFoco = document.activeElement;
        modal.classList.add('aberto');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        btnFechar.focus();
    }

    function fecharModal() {
        modal.classList.remove('aberto');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        // Devolve o foco ao botão "Ver mais" que abriu o modal
        if (ultimoFoco) ultimoFoco.focus();
    }

    // Mantém o Tab dentro do modal enquanto ele está aberto
    function prenderFoco(e) {
        const focaveis = modal.querySelectorAll('a[href], button');
        const primeiro = focaveis[0];
        const ultimo = focaveis[focaveis.length - 1];

        if (e.shiftKey && document.activeElement === primeiro) {
            e.preventDefault();
            ultimo.focus();
        } else if (!e.shiftKey && document.activeElement === ultimo) {
            e.preventDefault();
            primeiro.focus();
        }
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
        if (!modal.classList.contains('aberto')) return;
        if (e.key === 'Escape') {
            fecharModal();
        } else if (e.key === 'Tab') {
            prenderFoco(e);
        }
    });
});