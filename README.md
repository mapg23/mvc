# MVC Report

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mapg23/mvc/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/mapg23/mvc/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/mapg23/mvc/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/mapg23/mvc/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/mapg23/mvc/badges/build.png?b=main)](https://scrutinizer-ci.com/g/mapg23/mvc/build-status/main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/mapg23/mvc/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
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