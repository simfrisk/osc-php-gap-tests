# osc-php-gap-tests

Small test apps for the Eyevinn Open Source Cloud PHP My App runner (Apache + mod_php, PHP 8.3).
Each branch is one test:

- `mysql`: PDO MySQL against an OSC MariaDB instance, config from a bound parameter store.
- `upload-a`: raise upload limits with `public/.htaccess` php_value.
- `upload-b`: same plus `display_errors off` and `log_errors on` in `.htaccess`.
- `upload-c`: raise limits from `setup.sh` by writing a conf.d ini file.
- `extensions`: `setup.sh` installs gd, intl and zip with `-j$(nproc)`.
- `extensions-j2`: same with `-j2`.

`public/index.php` is a JSON status page on every branch. It never calls ini_set, so it shows the real runner defaults.
