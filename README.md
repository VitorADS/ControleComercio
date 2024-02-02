# Projeto de controle de compras / preparo de pedidos

<h3>Rotas:</h3>
<p>/home utilizada para login</p>
<p>todas as rotas /admin so podem ser acessadas por usuario com role ADMIN, demais rotas exigem apenas role USER</p>
<p>existe uma rota /kitchen que e utilizada para visualizar pedidos e alterar seu status para pronto</p>

<h3>Configuracao do projeot:</h3>
<p>Necessario criar um arquivo .env.local para configurar o acesso ao banco de dados:</p>

_DATABASE_URL="postgresql://usuario:senha@dominio:porta/nome-banco-dados?serverVersion=15&charset=utf8"_

<p>Rodar o composer install --no-dev e o npm install para instalar as dependencias do projeto</p>
