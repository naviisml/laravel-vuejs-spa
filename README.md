# Laravel Vue 3 Boilerplate

This boilerplate contains the basic features needed to start on a fresh laravel vuejs single page application.

## Features

- Routing
	- Route Guards
- Layouts
- Store
- Languages
- Authentication
- RBAC: Role Based Authentication System

## Prerequisites

### Ubuntu

#### PHP 8

**Step 1. Register PHP 8 repository**

```
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt upgrade
```

**Step 2. Install PHP 8**

```
sudo apt install php8.0-fpm -y
```

**Step 3. Install Laravel Dependencies**

```
sudo apt-get install php-mysql php8.0-mbstring php8.0-xml php8.0-bcmath -y
```


#### Composer

**Step 1. Dependencies**

```
sudo apt update
sudo apt install php-cli unzip php-curl php-zip php-xml -y
```

**Step 2. Download Composer**

```
curl -sS https://getcomposer.org/installer -o ~/composer-setup.php  
```

**Step 3. Verify Installer**

```
HASH=`curl -sS https://composer.github.io/installer.sig`
php -r "if (hash_file('SHA384', '~/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('~/composer-setup.php'); } echo PHP_EOL;"
```

**Step 3. Install Composer**

```
sudo php ~/composer-setup.php --install-dir=/usr/local/bin --filename=composer  
```

#### NPM

**Step 1. Download NVM**

```
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
```

**Step 2. Activate**

Either restart the terminal, or execute this to get direct access.
```
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
```

**Step 3. Install Nodejs**

```
nvm install node
```

## Installation

**Step 1. Clone/download this repository**

```
git clone https://github.com/naviisml/laravel-vuejs3-boilerplate.git <path/to/folder>
```
*or you can click 'Use this template' to create a GitHub repository for yourself*

**Step 2. Install the dependencies**

```
cd <path/to/folder>
npm install
composer install
```

**Step 3. Configure your project**

Rename `.env.example` to `.env`, and adjust the values accordingly.

**Step 4. Build the assets**

Development:
```
npm run dev
```

Production:
```
npm run dev
```

**Development server**

To test this project on your local machine, you can run the following command;
```
php artisan serve
```

# Requirements

- [PHP 8](https://www.php.net/downloads.php#v8.1.2)
- [NPM](https://www.npmjs.com/)

## Depencencies

- [Laravel 9](https://laravel.com/)
- [Vite 2.6](https://github.com/vitejs/vite)
- [Vue.js 3.2](https://vuejs.org)
- [Vue Router 4](https://router.vuejs.org)
- [Vuex 4](https://vuex.vuejs.org)

<div align=center>Made with tons of ☕ and ❤️ by <a href="https://github.com/naviisml">Navi</a></div>
