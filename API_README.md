# API Delivcrous

Base URL : `http://localhost:8000/api`

Toutes les réponses sont au format JSON avec `success`, `data` et `message`.

## Routes disponibles

### Menu
- `GET /api/menu` - Liste des plats
- `GET /api/menu/{id}` - Détails d'un plat
- `GET /api/menu/categorie/{categorie}` - Plats par catégorie
- `GET /api/categories` - Liste des catégories

### Panier
- `GET /api/panier` - Récupérer le panier
- `POST /api/panier` - Ajouter un produit (body: `{"produit_id": 1, "quantite": 2}`)
- `DELETE /api/panier/{id}` - Supprimer un produit
- `DELETE /api/panier` - Vider le panier

### Étudiant
- `GET /api/etudiant` - Informations de l'étudiant

## Exemples

```bash
# Menu
curl http://localhost:8000/api/menu

# Ajouter au panier
curl -X POST http://localhost:8000/api/panier \
  -H "Content-Type: application/json" \
  -d '{"produit_id": 1, "quantite": 2}'

# Panier
curl http://localhost:8000/api/panier
```
