# Backenders EduReuse project

### EduReuse – Geef educatieve Apparatuur een Tweede Leven
Website voor vraag & aanbod van educatieve hardware

### Achtergrond & Doel
Scholen hebben regelmatig afgeschreven of ongebruikte apparatuur (laptops, 3D-printers, educatieve robots). Wij willen een prototype van een website waar scholen:

1. E-waste (herbruikbare educatieve hardware) kunnen **aanmelden /opgeven**,
2. Andere scholen **hun behoefte** kunnen aangeven,
3. Ons als facilitator (Wailsalutem Foundation) **tussenzetten** voor verificatie, matching, ophalen en herverdeling,
4. Informatie vinden over **duurzaam hergebruik** en **e-waste-bewustwording**.

## Requirements
1. Zorg dat je [composer](https://getcomposer.org/download/) geinstalleerd hebt
2. Zorg dat je een server als [XAMPP](https://www.apachefriends.org/download.html) geinstalleerd hebt OF apache en MySQL apart hebt runnen

## Installation
**1. Clone project:**
```
git clone https://github.com/Bit-Academy-Students/Backenders-EduReUse.git
```

**2. Install dependencies**

In je terminal:
```
composer install
```

**3. Set up database:**
1. Voer de database migrations en seeder uit:
```
php database/databaseMigrator.php && php database/databaseSeeder.php
```

## Usage
1. Zorg dat je via XAMPP MySQL runt of lokaal
2. Start de server:
```
php -S localhost:8000 -t public
```