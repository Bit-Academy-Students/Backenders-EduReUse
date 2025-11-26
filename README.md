# Backenders EduReuse project

### EduReuse – Geef educatieve Apparatuur een Tweede Leven
Website voor vraag & aanbod van educatieve hardware

### Achtergrond & Doel
Scholen hebben regelmatig afgeschreven of ongebruikte apparatuur (laptops, 3D-printers, educatieve robots). Wij willen een prototype van een website waar scholen:

1. E-waste (herbruikbare educatieve hardware) kunnen **aanmelden /opgeven**,
2. Andere scholen **hun behoefte** kunnen aangeven,
3. Ons als facilitator (Wailsalutem Foundation) **tussenzetten** voor verificatie, matching, ophalen en herverdeling,
4. Informatie vinden over **duurzaam hergebruik** en **e-waste-bewustwording**.


## Tech stack
### Frontend
 - **Frontend framework:** [tailwind css](https://tailwindcss.com/)

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
**1. Clone deze repository:**
```
git clone https://github.com/Bit-Academy-Students/Backenders-EduReUse.git
```

**2. Installeer dependencies**

In je terminal:
```
composer install
```

**3. Set up environment variables:**
1. Voer deze command uit om een `.env` te genereren:
```
cp ./.env.example ./.env
```

2. Start je MySQL op via XAMPP/lokaal en maak een lege database genaamd `edureuse` aan (of hernoem deze naar eigen wens)

3. Vul `.env` zo in zoals je met je eigen database zou verbinden. De standaard username en password zijn 'root' en ''

**4. Set up database:**
1. Voer de database migrations en seeder uit:
```
php database/databaseMigrator.php
```
```
php database/databaseSeeder.php
```

## Usage
1. Zorg dat je via XAMPP MySQL runt of lokaal
2. Start de server:
```
php -S localhost:8000 -t public
```