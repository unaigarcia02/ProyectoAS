Portátiles SoloG


## Tareas realizadas

- [X] Desarrollo de la aplicación funcional
- [X] Crear imágenes de docker
- [X] Crear un entorno Docker Compose
- [X] Despliegue en Kubernetes
- [X] Uso de Kubernetes más allá de lo visto en clase


## Como desplegarlo

### Docker

#### Nos situamos en la raiz de la carpeta

```bash
docker-compose up -d --build
```

##Entramos en http://localhost:8080


### Kubernetes

#### Nos situamos en la carpeta kubernetes
#### Iniciamos minikube

```bash
minikube start
```

#### Instalam ingress

```bash
minikube addons enable ingress
```

#### Crear los kubernetes

```bash

kubectl apply -f release.yaml

```


#### Imagenes de Docker Hub

```bash
docker tag proyectoas-main-web unaigarcia02/web
docker push unaigarcia02/web

docker tag proyectoas-main-kaggle-data unaigarcia02/kaggle-data
docker push unaigarcia02/kaggle-data

docker tag postgres unaigarcia02/postgres
docker push unaigarcia02/postgres

docker tag rabbitmq unaigarcia02/rabbitmq
docker push unaigarcia02/rabbitmq
```
