# Demo 

## Set up

### Install dependencies
```
composer install
```
### copy env

```
cp .env.example .env
```

### Start the container
```
./vendor/bin/sail up
```

### Migrate and seed the DB
```
./vendor/bin/sail artisan migrate --seed
```



## Configure alias
- to use `sail` instead of `./vendor/bin/sail`
```
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```