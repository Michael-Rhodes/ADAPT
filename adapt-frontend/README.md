# AdaptFrontend

This project was generated with [Angular CLI](https://github.com/angular/angular-cli) version 7.0.2.

## Development server

Run `ng serve` for a dev server. Navigate to `http://localhost:4200/`. The app will automatically reload if you change any of the source files.

## Code scaffolding

Run `ng generate component component-name` to generate a new component. You can also use `ng generate directive|pipe|service|class|guard|interface|enum|module`.

## Build

Run `ng build` to build the project. The build artifacts will be stored in the `dist/` directory. Use the `--prod` flag for a production build.

## Running unit tests

Run `ng test` to execute the unit tests via [Karma](https://karma-runner.github.io).

## Running end-to-end tests

Run `ng e2e` to execute the end-to-end tests via [Protractor](http://www.protractortest.org/).

## Further help

To get more help on the Angular CLI use `ng help` or go check out the [Angular CLI README](https://github.com/angular/angular-cli/blob/master/README.md).

## Making Docker Image:

1) make docker file
2) make nginx.conf file
3) run 

```
ng build --prod

```
4) build the docker image

```
docker image build -t my-angular-app .
```

5) check to see if the image built

```
docker image ls
```

6) run the docker image

```
docker run -p 3000:80 --rm my-angular-app
```

7) * could build a docker-compose.yml if you wanted to be able to run mult. instances

```
version: '3.1'

services:
    app:
        image: 'my-angular-app'
        build: '.'
        ports:
            - 3000:80
```

8) then run the docker-compose

```
docker-compose up
```
This will build and run the container

9) To tear down the image, but not destroy it

```
docker-compose down

```

10) destroy and tear down image

```
docker-compose down --rmi all
``` 


