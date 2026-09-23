#!/bin/sh
set -e

# /var/www/html e' um volume montado do host, entao qualquer diretorio
# criado durante o build da imagem fica escondido pelo mount. Por isso
# as pastas de upload usadas via base_path('traducaidiomas/...') sao
# garantidas aqui, toda vez que o container sobe.
mkdir -p /var/www/html/traducaidiomas/materiais \
         /var/www/html/traducaidiomas/professor \
         /var/www/html/traducaidiomas/alunos \
         /var/www/html/traducaidiomas/servicos \
         /var/www/html/traducaidiomas/forum \
         /var/www/html/traducaidiomas/img \
         /var/www/html/traducaidiomas/banners

chown -R www-data:www-data /var/www/html/traducaidiomas

exec "$@"
