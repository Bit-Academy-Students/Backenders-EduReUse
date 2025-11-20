## Requirements
1. Zorg dat je [composer](https://getcomposer.org/download/) geinstalleerd hebt

## Installation
**1. Install dependencies**
In je terminal:
```
composer install
```

**2. Set up database:**
1. run database migrations
```
php database/databaseMigrator.php
```
2. run database seeder: 
```
php database/databaseSeeder.php
```