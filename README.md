# Multiline env vars parser

Parsing script for multiline environment variables to be used in docker container init script. This saves values of environment variable into file (e.g. SSH envvars file).

This script is an extension of (following solution)[https://omgdebugging.com/2018/10/05/how-to-export-environment-variables-in-azure-web-app-for-containers/] to catch environment variables to a file inside docker container on startup. It supports multiline variables.

This solution was developed for docker based app (running in Azure Web App platform) therefore it targets only variables prefixed by `APPSETTING_` string (can be adjusted in parser.php).


## Usage

In your docker init scipt e.g. `docker-entrypoint.sh` which is wired using `ENTRYPOINT ["/docker-entrypoint.sh"]` in your Dockerfile use following:

```
# Get environment variables to show up in SSH session
eval $(while read -r -d '' line; do printf '%s\n' "$line" >> /tmp/envvars; done < <(env -0))
cat /tmp/envvars | php /var/www/html/parser.php >> /etc/profile
rm /tmp/envvars
```


## Requirements

Requires `php` interpreter (v7+) available inside docker contianer.
