# Drupal 11 site

## Local development
Pull latest version: `ddev composer site-pull`

Export configurations: `ddev drush cex -y`

## Local install
```
ddev start
ddev composer site-install
```

## Server deploy
```
git pull origin main
composer site-deploy
```

## Site aliases
Sync DB: `ddev drush sql:sync @remote @self`

Sync files: `ddev drush rsync @remote:%files/ @self:/files`

Share host machine ssh: `ddev auth ssh`

Copy ssh to server: `ssh-copy-id user@server`
