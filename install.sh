#!/bin/bash

# ============================================
# Script d'Installation Automatique
# Gestion de Stock & Crédits
# ============================================

set -e

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonctions
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_header() {
    echo ""
    echo -e "${BLUE}╔════════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║  🚀 Installation - Gestion de Stock  ║${NC}"
    echo -e "${BLUE}╚════════════════════════════════════════╝${NC}"
    echo ""
}

# Vérifications
check_requirements() {
    print_info "Vérification des prérequis..."
    
    # PHP
    if ! command -v php &> /dev/null; then
        print_error "PHP n'est pas installé"
        exit 1
    fi
    print_success "PHP $(php -r 'echo PHP_VERSION;') détecté"
    
    # Composer
    if ! command -v composer &> /dev/null; then
        print_error "Composer n'est pas installé"
        exit 1
    fi
    print_success "Composer détecté"
    
    # Node.js
    if ! command -v node &> /dev/null; then
        print_error "Node.js n'est pas installé"
        exit 1
    fi
    print_success "Node.js $(node -v) détecté"
    
    # MySQL
    if ! command -v mysql &> /dev/null; then
        print_warning "MySQL CLI n'est pas détecté (optionnel)"
    else
        print_success "MySQL détecté"
    fi
}

# Configuration
setup_env() {
    print_info "Configuration de l'environnement..."
    
    if [ ! -f .env ]; then
        cp .env.example .env
        print_success ".env créé"
    else
        print_warning ".env existe déjà"
    fi
    
    # Générer APP_KEY
    php artisan key:generate
    print_success "APP_KEY générée"
}

# Base de données
setup_database() {
    print_info "Configuration de la base de données..."
    
    read -p "Nom de la base de données [gestion_stock]: " DB_NAME
    DB_NAME=${DB_NAME:-gestion_stock}
    
    read -p "Utilisateur MySQL [root]: " DB_USER
    DB_USER=${DB_USER:-root}
    
    read -sp "Mot de passe MySQL: " DB_PASS
    echo ""
    
    # Mettre à jour .env
    sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
    sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env
    
    print_success "Configuration DB mise à jour"
    
    # Créer la base si possible
    if command -v mysql &> /dev/null; then
        read -p "Créer la base de données maintenant? (y/n) " CREATE_DB
        if [ "$CREATE_DB" = "y" ]; then
            mysql -u"$DB_USER" -p"$DB_PASS" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
            print_success "Base de données créée"
        fi
    fi
}

# Installer dépendances
install_dependencies() {
    print_info "Installation des dépendances PHP..."
    composer install --optimize-autoloader
    print_success "Dépendances PHP installées"
    
    print_info "Installation des dépendances JavaScript..."
    npm install
    print_success "Dépendances JS installées"
}

# Migrations
run_migrations() {
    print_info "Exécution des migrations..."
    
    php artisan migrate --force
    print_success "Migrations exécutées"
    
    read -p "Créer des données de test? (y/n) " SEED_DB
    if [ "$SEED_DB" = "y" ]; then
        php artisan db:seed
        print_success "Données de test créées"
    fi
}

# Build assets
build_assets() {
    print_info "Compilation des assets..."
    npm run build
    print_success "Assets compilés"
}

# Permissions
set_permissions() {
    print_info "Configuration des permissions..."
    
    chmod -R 755 storage
    chmod -R 755 bootstrap/cache
    
    print_success "Permissions configurées"
}

# Créer admin
create_admin() {
    print_info "Création du compte administrateur..."
    
    read -p "Email admin [admin@example.com]: " ADMIN_EMAIL
    ADMIN_EMAIL=${ADMIN_EMAIL:-admin@example.com}
    
    read -sp "Mot de passe admin: " ADMIN_PASS
    echo ""
    
    php artisan tinker --execute="
        \$user = App\Models\User::create([
            'nom' => 'Admin',
            'email' => '$ADMIN_EMAIL',
            'password' => bcrypt('$ADMIN_PASS'),
            'is_admin' => true,
            'is_active' => true,
            'subscription_type' => 'lifetime'
        ]);
        echo 'Utilisateur créé avec ID: ' . \$user->id;
    "
    
    print_success "Administrateur créé"
}

# Créer les dossiers Docker
create_docker_files() {
    print_info "Création des fichiers Docker..."
    
    if [ -d "docker" ]; then
        print_warning "Le dossier docker existe déjà"
        return
    fi
    
    bash docker/create-configs.sh 2>/dev/null || print_warning "Fichiers Docker non créés (optionnel)"
}

# Installation complète
install_all() {
    print_header
    
    check_requirements
    echo ""
    
    setup_env
    echo ""
    
    setup_database
    echo ""
    
    install_dependencies
    echo ""
    
    run_migrations
    echo ""
    
    build_assets
    echo ""
    
    set_permissions
    echo ""
    
    read -p "Créer un compte administrateur? (y/n) " CREATE_ADMIN
    if [ "$CREATE_ADMIN" = "y" ]; then
        create_admin
        echo ""
    fi
    
    # Résumé
    echo ""
    echo -e "${GREEN}╔════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║     ✓ Installation terminée! 🎉       ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════╝${NC}"
    echo ""
    print_info "Pour démarrer l'application:"
    echo -e "  ${BLUE}php artisan serve${NC}"
    echo ""
    print_info "Accédez à:"
    echo -e "  ${BLUE}http://localhost:8000${NC}"
    echo ""
    
    if [ "$CREATE_ADMIN" = "y" ]; then
        print_info "Identifiants:"
        echo -e "  Email: ${YELLOW}$ADMIN_EMAIL${NC}"
        echo -e "  Password: ${YELLOW}[celui que vous avez saisi]${NC}"
    fi
    
    echo ""
}

# Menu principal
show_menu() {
    echo ""
    echo "Que souhaitez-vous faire?"
    echo ""
    echo "1) Installation complète (recommandé)"
    echo "2) Installer les dépendances uniquement"
    echo "3) Configurer la base de données"
    echo "4) Exécuter les migrations"
    echo "5) Créer un administrateur"
    echo "6) Créer les fichiers Docker"
    echo "0) Quitter"
    echo ""
    read -p "Choix: " choice
    
    case $choice in
        1) install_all ;;
        2) install_dependencies ;;
        3) setup_database ;;
        4) run_migrations ;;
        5) create_admin ;;
        6) create_docker_files ;;
        0) exit 0 ;;
        *) print_error "Choix invalide" ; show_menu ;;
    esac
}

# Démarrage
if [ $# -eq 0 ]; then
    show_menu
else
    case $1 in
        --full) install_all ;;
        --deps) install_dependencies ;;
        --db) setup_database ;;
        --migrate) run_migrations ;;
        --admin) create_admin ;;
        --docker) create_docker_files ;;
        --help)
            echo "Usage: $0 [option]"
            echo ""
            echo "Options:"
            echo "  --full     Installation complète"
            echo "  --deps     Installer les dépendances"
            echo "  --db       Configurer la base de données"
            echo "  --migrate  Exécuter les migrations"
            echo "  --admin    Créer un administrateur"
            echo "  --docker   Créer les fichiers Docker"
            echo "  --help     Afficher cette aide"
            ;;
        *) print_error "Option invalide. Utilisez --help" ;;
    esac
fi