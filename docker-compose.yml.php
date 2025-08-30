version:"3.9"

services:
  app:
    build: .
    container_name: laravel - app
    volumes:
      - .:/var/www
    depends_on:
      -db
      - redis

  web:
    image: nginx:alpine
    container_name: laravel - nginx
    ports:
      -"8080:80"
    volumes:
      - .:/var/www
    - ./nginx . conf:/etc / nginx / conf . d /default.conf
    depends_on:
      -app

  db:
    image: postgres:15
    container_name: laravel - db
    restart: always
    environment:
      POSTGRES_DB: laravel
      POSTGRES_USER: laravel
      POSTGRES_PASSWORD: secret
    ports:
      -"5432:5432"
    volumes:
      -db - data:/var/lib / postgresql / data

  redis:
    image: redis:alpine
    container_name: laravel - redis
    ports:
      -"6379:6379"

volumes:
  db - data:
