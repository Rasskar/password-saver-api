```bash
git clone git@github.com:Rasskar/password-saver-api.git
```
```bash
cd password-saver-api
```
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```
```bash
cp .env.example .env
```
```bash
./vendor/bin/sail up -d
```
```bash
./vendor/bin/sail artisan key:generate
```
```bash
./vendor/bin/sail artisan migrate
```
```bash
./vendor/bin/sail artisan test
```
```bash
http://localhost
```
```bash
http://localhost/api/documentation/
```
