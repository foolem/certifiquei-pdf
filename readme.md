
# Tesis Certificate Service  
 
![alt text](https://img.shields.io/badge/status-development-yellow)
![alt text](https://img.shields.io/badge/language-PHP-blue)
![alt text](https://img.shields.io/badge/framework-Lumen-orange)

  
Tesis certificate issuer microservice.  
   
  
### Stack
  
- [PHP](https://php.net)
- [Lumen Framework](https://lumen.laravel.com)
- [Docker](https://www.docker.com)
- [MySQL](https://www.mysql.com)
  
### Installation
  
1. `git clone https://<YOUR_BITBUCKET_USERNAME>@bitbucket.org/tesisdigital/microservico.certificado.git`  
  
2. `cd microservico.certificado/`   
  
3. `docker-compose up` to launch containers
  
  
**Alternatively you can run the application via PHP built in server:**

1. `cd microservico.certificado/public`

2. `php -S localhost:8080`

  
**Notice:**

- Application runs at `http://localhost:8080` via Docker;
- API documentation available at Postman (Tesis Workspace).
  
### Testing

In the project root directory, run `phpunit`.
