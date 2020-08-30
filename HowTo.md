## How Configure Database

In `config/config.yaml` file do this:

```yaml
database:
  server: sqldriver
  # Connection options
  options:
    # One of 'mysql'|'pgsql'
    connection: mysql
    # Optional, defaults to 'localhost'
    host: localhost
    # Optional, defaults to 3306 (MySQL) or 5432 (PostgreSQL)
    port: 3306
    dbname: DATABASE_NAME
    username: USER
    password: PASSWORD
    # Optional, defaults to 'UTF8'
    charset: UTF8
  # Connection specific options
  # General: https://www.php.net/manual/en/pdo.setattribute.php
  # MySQL specific: https://www.php.net/manual/en/ref.pdo-mysql.php#pdo-mysql.constants
  driverOptions:
```
