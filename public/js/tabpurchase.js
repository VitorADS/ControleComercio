window.addEventListener('DOMContentLoaded', (e) => {
    document.querySelector('#payments-link').addEventListener('click', (e) => {
        document.querySelector('#itens').setAttribute('hidden', true);
        document.querySelector('#payments').removeAttribute('hidden');
        document.querySelector('#payments-link').classList.add('active');
        document.querySelector('#itens-link').classList.remove('active');
    });

    document.querySelector('#itens-link').addEventListener('click', (e) => {
        document.querySelector('#itens').removeAttribute('hidden');
        document.querySelector('#payments').setAttribute('hidden', true);
        document.querySelector('#payments-link').classList.remove('active');
        document.querySelector('#itens-link').classList.add('active');
    });
});