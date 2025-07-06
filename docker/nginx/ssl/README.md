# Atalanta SSL Certificates

Place your SSL certificate and private key here:

- `atalanta.crt`  — full certificate chain (or just cert for self-signed)
- `atalanta.key`  — private key

The files are mounted into the Nginx container at `/etc/nginx/ssl`.

For production with a real domain, generate via Let's Encrypt:
```
sudo certbot certonly --standalone -d example.com
```
Then copy `fullchain.pem` → `atalanta.crt`, `privkey.pem` → `atalanta.key` and restart stack:
```
docker compose up -d --build
```
For a quick self-signed cert (development):
```
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout atalanta.key -out atalanta.crt \
  -subj "/CN=atalanta.local"
``` 