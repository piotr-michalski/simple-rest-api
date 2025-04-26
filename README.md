# **restapi-service**
## **Stack**
#### **Symfony 6.4 LTS**
#### **PHP 8.4 **
## **Development**
### **Running the Application**
#### **Start Containers**
- In interactive mode (logs visible in the console):
  ```sh
  cd ./php
  docker compose up --build
  ```  
- In detached mode (running in the background):
  ```sh
  docker compose up --build -d
  ```
#### **Stop Containers**
```sh
docker compose down
docker compose down -v
```
#### **Remove Docker Volumes**
```sh
docker volume rm <volume_name>
```  
### **Setup**
1. Start the PHP container.
2. Change `.env` file if needed
3. Install dependencies:
   ```sh
   composer install
   ```
---
## **API Documentation**
- API documentation is available at:  
  [http://localhost:8080/api/doc.json](http://localhost:8080/api/doc.json)
  [http://localhost:8080/api/doc](http://localhost:8080/api/doc)
---
## **Testing**
Symfony PHPUnit Bridge:
- Place test cases in the `tests/` folder.
- Generate tests using:
  ```sh
  php bin/console make:test
  ```  
- Run tests:
  ```sh
  php bin/phpunit
  ```  
- Run tests inside the Docker container:
  ```sh
  docker compose exec <container_name> php bin/phpunit <test_path_as_option>
  ```
---
## **PHPStorm Configuration (Debugging)**

1. **PHPStorm Settings**
    - **Settings > PHP > Debug** (use default IDE settings)
    - **Settings > Servers > Add New**
        - **Name**: `_`
        - **Host**: `localhost`
        - **Port**: `8080`
        - **[✔] Use path mappings**
        - Mappings:
            - `./src` → `/var/www/html/app`
            - `./src/public` → `/var/www/html/app/public`
            - `./src/public/index.php` → `/var/www/html/app/public/index.php`

2. **Set the environment variable:**
   ```sh
   export PHP_IDE_CONFIG="serverName=_"
   ```
---
## **MySQL**
### **Configuration**
```sh
export MYSQL_ROOT_PASSWORD=!ChangeMe!
export MYSQL_DATABASE=app
export MYSQL_USER=app
export MYSQL_PASSWORD=!ChangeMe!
```
### **Grant Permissions to User**
Run the following commands inside the MySQL container as `root`:
```sh
 docker compose exec -it db mysql -Uroot service_db -p
```
```sql
GRANT ALL PRIVILEGES ON *.* TO 'service_user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```
---
## **PHP-FPM Debugging**
```sh
docker compose exec app php-fpm -tt
```
---
## **Doctrine (ORM)**

- Regenerate entity classes:
  ```sh
  php bin/console make:entity --regenerate --overwrite
  ```  
- Create a migration:
  ```sh
  php bin/console make:migration
  ```  
- Apply migrations:
  ```sh
  php bin/console doctrine:migrations:migrate
  ```
---
## **Fixtures**
- Install the required package:
  ```sh
  composer require --dev orm-fixtures
  ```  
- Load sample data:
  ```sh
  php bin/console doctrine:fixtures:load
  ```
---
## **Serializer Debugging**
- Check how an object is serialized:
  ```sh
  php bin/console debug:serializer 'App\Entity\App'
  ```
---
