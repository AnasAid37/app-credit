# 📦 Système de Gestion de Stock & Crédits

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Application web complète de gestion de stock, crédits clients et sorties avec système multi-utilisateurs.

## 🎯 Fonctionnalités

### 📊 Gestion de Stock
- ✅ Suivi des produits en temps réel
- ✅ Alertes de stock faible
- ✅ Historique complet des mouvements
- ✅ Import/Export CSV

### 💳 Gestion des Crédits
- ✅ Création et suivi des crédits clients
- ✅ Paiements partiels ou complets
- ✅ Historique des paiements
- ✅ Calcul automatique des montants restants
- ✅ Recherche et filtrage avancés
- ✅ Suppression multiple (bulk delete)

### 📤 Sorties de Stock
- ✅ Enregistrement des sorties
- ✅ **Mode de paiement dual**: Comptant ou Crédit
- ✅ Création automatique de crédit depuis sortie
- ✅ Liaison automatique sortie ↔ crédit
- ✅ Statistiques en temps réel

### 👥 Multi-Utilisateurs
- ✅ Isolation complète des données par utilisateur
- ✅ Système d'authentification sécurisé
- ✅ Gestion des profils utilisateurs
- ✅ Historique des activités

### 🎨 Interface Moderne
- ✅ Design responsive (mobile-first)
- ✅ Interface intuitive et rapide
- ✅ Recherche en temps réel (AJAX)
- ✅ Pagination avancée
- ✅ Notifications toast élégantes

---

## 🚀 Installation Locale

### Prérequis

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18.x
- NPM ou Yarn

### Étape 1: Cloner le projet

```bash
git clone https://github.com/AnasAid37/app-credit.git
cd app-credit
```

### Étape 2: Installer les dépendances

```bash
# Dépendances PHP
composer install

# Dépendances JavaScript
npm install
```

### Étape 3: Configuration

```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate
```

### Étape 4: Configuration Base de données

Éditez le fichier `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_stock
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### Étape 5: Migrations

```bash
# Créer les tables
php artisan migrate

# (Optionnel) Données de test
php artisan db:seed
```

### Étape 6: Lancer l'application

```bash
# Compiler les assets
npm run build

# Lancer le serveur
php artisan serve
```

Accédez à: `http://localhost:8000`

---

## 🐳 Déploiement avec Docker

### Prérequis
- Docker Desktop
- Docker Compose

### Lancer avec Docker

```bash
# Construire et lancer
docker-compose up -d

# Migrations
docker-compose exec app php artisan migrate --seed

# Accéder
open http://localhost:8000
```

### Commandes Docker utiles

```bash
# Voir les logs
docker-compose logs -f

# Arrêter
docker-compose down

# Rebuild
docker-compose up -d --build

# Accéder au container
docker-compose exec app bash
```

---

## ☁️ Déploiement sur Render

### Option 1: Via Docker (Recommandé)

1. **Créer un compte sur Render.com**

2. **Créer un nouveau Web Service**
   - Type: Docker
   - Repository: Votre GitHub repo
   - Branch: main

3. **Configuration automatique**
   - Render détectera automatiquement le `Dockerfile`
   - Build Command: (vide)
   - Start Command: (vide)

4. **Variables d'environnement**
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:... (générer avec: php artisan key:generate --show)
   
   DB_CONNECTION=mysql
   DB_HOST=votre-db-host.render.com
   DB_PORT=3306
   DB_DATABASE=gestion_stock
   DB_USERNAME=root
   DB_PASSWORD=votre_mot_de_passe
   
   SESSION_DRIVER=database
   CACHE_DRIVER=database
   ```

5. **Créer une base de données MySQL**
   - Dans Render Dashboard → New → MySQL
   - Copier les informations de connexion dans les variables d'environnement

6. **Déployer**
   - Cliquez sur "Create Web Service"
   - Render construira et déploiera automatiquement

### Option 2: Via Build Command

Si vous préférez ne pas utiliser Docker:

**Build Command:**
```bash
composer install --no-dev --optimize-autoloader && 
npm ci && 
npm run build && 
php artisan config:cache && 
php artisan route:cache && 
php artisan view:cache
```

**Start Command:**
```bash
php artisan migrate --force && 
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 📁 Structure du Projet

```
gestion-stock/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CreditController.php
│   │   │   ├── SortieController.php
│   │   │   └── ProductController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Credit.php
│   │   ├── Sortie.php
│   │   ├── Product.php
│   │   ├── Client.php
│   │   └── Scopes/
│   │       └── OwnedByUser.php (Data Isolation)
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── credits/
│   │   ├── sorties/
│   │   └── products/
│   └── js/
├── public/
├── routes/
│   └── web.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## 🔐 Sécurité

### Data Isolation
- ✅ **Global Scope automatique** pour filtrer les données par utilisateur
- ✅ Chaque utilisateur ne voit que ses propres données
- ✅ Protection contre l'accès non autorisé

### Autres mesures
- ✅ CSRF Protection
- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ XSS Protection
- ✅ Password Hashing (Bcrypt)

---

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 11.x** - Framework PHP
- **MySQL 8.0** - Base de données
- **Eloquent ORM** - Gestion des données

### Frontend
- **Blade Templates** - Moteur de templates
- **Bootstrap 5.3** - Framework CSS
- **Font Awesome 6.4** - Icônes
- **jQuery 3.7** - JavaScript
- **Select2** - Sélecteurs avancés

### DevOps
- **Docker** - Conteneurisation
- **Docker Compose** - Orchestration
- **Render** - Hébergement cloud

---

## 📊 Base de Données

### Tables Principales

| Table | Description |
|-------|-------------|
| `users` | Utilisateurs et authentification |
| `products` | Produits en stock |
| `clients` | Informations clients |
| `credits` | Crédits clients |
| `sorties` | Sorties de stock |
| `payments` | Paiements des crédits |

### Relations
- `User` → `hasMany` → `Products`, `Credits`, `Sorties`
- `Credit` → `belongsTo` → `Client`, `User`
- `Credit` → `hasMany` → `Payments`
- `Sortie` → `belongsTo` → `Product`, `Credit`, `User`

---

## 🧪 Tests

```bash
# Tests unitaires
php artisan test

# Tests de fonctionnalités
php artisan test --filter=CreditTest

# Coverage
php artisan test --coverage
```

---

## 📝 API Documentation

(À venir)

---

## 🤝 Contribution

Les contributions sont les bienvenues!

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit (`git commit -m 'Add some AmazingFeature'`)
4. Push (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

---

## 📧 Contact

- **Email**: anasdaitdaouf@gmail.com
- **GitHub**: [@Anasaid37](https://github.com/AnasAid37)

---

## 📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

## 🙏 Remerciements

- Laravel Community
- Bootstrap Team
- Font Awesome
- Tous les contributeurs

---

## 📸 Captures d'écran

### Dashboard
![Dashboard](docs/screenshots/dashboard.png)

### Gestion des Crédits
![Credits](docs/screenshots/credits.png)

### Sortie de Stock
![Sortie](docs/screenshots/sortie.png)

---

## 🔄 Mises à jour

### Version 1.0.0 (2025-12-28)
- ✅ Lancement initial
- ✅ Gestion complète des crédits
- ✅ Système de sortie avec mode crédit
- ✅ Data isolation multi-utilisateurs
- ✅ Interface moderne et responsive

### Roadmap
- 🔜 API REST
- 🔜 Export PDF des rapports
- 🔜 Notifications email
- 🔜 Dashboard analytics avancé
- 🔜 Application mobile

---

**Fait avec ❤️ par [Anas AIT-DAOUD]**