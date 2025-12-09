# Backenders EduReuse project

### EduReuse – Geef educatieve Apparatuur een Tweede Leven
Website voor vraag & aanbod van educatieve hardware

### Achtergrond & Doel
Scholen hebben regelmatig afgeschreven of ongebruikte apparatuur (laptops, 3D-printers, educatieve robots). Wij willen een prototype van een website waar scholen:

1. E-waste (herbruikbare educatieve hardware) kunnen **aanmelden /opgeven**,
2. Andere scholen **hun behoefte** kunnen aangeven,
3. Ons als facilitator (Wailsalutem Foundation) **tussenzetten** voor verificatie, matching, ophalen en herverdeling,
4. Informatie vinden over **duurzaam hergebruik** en **e-waste-bewustwording**.

![logo-banner](/public/uploads/header-banner-foundation.png)

## Tech stack
### Frontend
 - **Frontend framework:** [tailwind css](https://tailwindcss.com/)
 - **Icon library:** [FontAwesome](https://fontawesome.com/)

### Backend
 - **Plain PHP** voor het grootste gedeelte
 - **PHP-PDO** databaseconnectie, zelfgeschreven seeders en migrations
 - **MySQL** database

### Libraries
 - **Environment variabelen:** [PHPDotEnv](https://github.com/vlucas/phpdotenv)
 - **Simpele routing:** [PHP ROUTER](https://phprouter.com/) (`public/routes.php` en `public/router.php`)


## Requirements
1. Zorg dat je [composer](https://getcomposer.org/download/) geinstalleerd hebt
2. Zorg dat je een server als [XAMPP](https://www.apachefriends.org/download.html) geinstalleerd hebt OF apache en MySQL apart hebt runnen

## Installation
### 1. Clone deze repository:
```
git clone https://github.com/Bit-Academy-Students/Backenders-EduReUse.git
```

### 2. Installeer dependencies

In je terminal:
```
composer install
```
en:
```
npm install
```

### 3. Maak een lege database:
 - Start je MySQL op via XAMPP/draai hem lokaal en maak een lege database genaamd `edureuse` aan (of hernoem deze naar eigen wens)

### 4. Set up environment variables:
1. Voer deze command uit om een `.env` te genereren:
```
cp ./.env.example ./.env
```

2. Vul `.env` zo in zoals je met je eigen database zou verbinden.

 - De standaard MySQL gebruikersnaam en wachtwoord zijn: 
```env
DB_USER="root" 
DB_PASS=""
```

### 5. Vul de database met seeders:
1. Voer de database migrations en seeder uit:
```
php database/databaseMigrator.php
```
```
php database/databaseSeeder.php
```

## Usage
1. Zorg dat je via XAMPP MySQL runt of lokaal
2. Stel een vhost in of start de server:
```
php -S localhost:8000 -t public
```
3. Open de applicatie in je browser: [localhost:8000](localhost:8000) (of de url naar je vhost)

## Development
Als je verder wilt werken aan dit project, zijn er enkele dingen waar je op moet letten:
1. Als je de **frontend** wil *aanpassen*, maar je ziet dat er niks veranderd, zorg dan dat je de **`Tailwindcss`-watcher** aan het runnen bent:
```bash
npx @tailwindcss/cli -i ./public/src/input.css -o ./public/src/output.css --watch
```
***P.S.*** *Mocht bovenstaande command een error geven, run dan het volgende:*
```bash
npm install
```