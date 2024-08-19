# Dockerfile para Nginx

FROM nginx:alpine

# Copiar configuração do Nginx para o container
COPY default.conf /etc/nginx/conf.d/

# Definir o diretório de trabalho
WORKDIR /var/www

# Expor a porta 80 para o Nginx
EXPOSE 80
