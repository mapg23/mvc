# MVC Report

![alt text](/public/img/github-picture.jpg)

## Requirements
    PHP >= 8.2
    Composer
    Node

## Installation
Clone the repo
```
git clone https://github.com/mapg23/mvc.git
```

Controlls that all necessary php extensions is installed.
```
composer require webapp
```

Run the Website, (be sure to run this program from the root directory)
```
php -S localhost:8888 -t public
```

## Updating website

Recompile scss
```
npm run sass
```

Recompile css & assets
```
npm run build
```

Recompile scss & assets on changes
```
npm run build-sass
```