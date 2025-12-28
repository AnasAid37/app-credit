# 💼 Application de Gestion de Stock et Crédit

Application Laravel simple et efficace pour gérer les stocks et les dettes avec système d'abonnement manuel.

---

## 🌟 Fonctionnalités

### Pour les utilisateurs :
- ✅ Gestion complète des produits (CRUD)
- ✅ Suivi des mouvements de stock
- ✅ Gestion des dettes (crédits) avec suivi des paiements
- ✅ Tableau de bord avec statistiques détaillées
- ✅ Alertes automatiques pour stock bas
- ✅ Rapports exportables
- ✅ Interface utilisateur facile en français

### Pour l'administrateur :
- 🔧 Tableau de bord simple pour gérer les utilisateurs
- ⚡ Activation/désactivation des comptes en un clic
- 📅 Gestion des abonnements (mensuel/à vie)
- 🔄 Extension facile des abonnements
- 📊 Visualisation des statistiques utilisateurs

---

## 🚀 Installation rapide

```bash
# 1. Cloner le projet
git clone https://github.com/AnasAid37/app-credit.git
cd credit-app

# 2. Installer les dépendances
composer install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Configurer la base de données dans .env
# DB_DATABASE=credit_app
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Exécuter les migrations
php artisan migrate

# 7. Lancer le serveur
php artisan serve
```

---

## 👤 Créer un compte administrateur

```bash
php artisan tinker
```

```php
User::create([
    'nom' => 'Admin',
    'email' => 'admin@admin.com',
    'password' => bcrypt('password'),
    'is_admin' => true,
    'is_active' => true,
]);
```

---

## 📖 Mode d'emploi

### Pour l'administrateur :
1. Connectez-vous : `/login`
2. Allez sur : `/admin`
3. Activez les utilisateurs et choisissez le type d'abonnement

### Pour les utilisateurs :
1. Créez un compte : `/register`
2. Attendez l'activation par l'administrateur
3. Après activation, connectez-vous et profitez de toutes les fonctionnalités !

---

## 🔒 Système d'abonnement

### Mensuel :
- Durée limitée en mois
- Nécessite un renouvellement
- Adapté pour les abonnements périodiques

### À vie :
- Pas de date d'expiration
- Paiement unique
- Accès permanent

---

## 🛠️ Technologies utilisées

- Laravel 12.x
- PHP 8.2+
- MySQL
- Bootstrap 5
- jQuery

---

## 📁 Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php       # Gestion des utilisateurs
│   │   ├── SubscribeController.php   # Page d'abonnement
│   │   ├── ProductController.php     # Gestion des produits
│   │   └── CreditController.php      # Gestion des dettes
│   └── Middleware/
│       └── CheckAccess.php           # Vérification d'abonnement
├── Models/
│   └── User.php                      # Modèle utilisateur
resources/
├── views/
│   ├── admin/
│   │   └── index.blade.php          # Tableau de bord admin
│   └── subscribe.blade.php          # Page d'abonnement
routes/
└── web.php                          # Routes principales
```

---

## 🔐 Sécurité

- ✅ Middleware protégé pour vérifier l'abonnement
- ✅ Séparation des permissions Admin/User
- ✅ Protection CSRF automatique
- ✅ Chiffrement des mots de passe
- ✅ Validation complète

---

## 📞 Support

Pour aide ou questions :
- WhatsApp : 0605816821
- GitHub Issues : https://github.com/AnasAid37/app-credit

---

## 📄 Licence

Licence MIT - Open Source

---

## 🙏 Remerciements

Développé par [votre nom]
Pour usage commercial et personnel

---

**Bon succès ! 🚀**