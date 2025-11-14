# API Delivcrous

Base URL : `http://localhost:8000/api`

Toutes les réponses sont au format JSON avec `success`, `data` et `message`.

## Routes CRUD

### Plats (`/api/plats`)

- `GET /api/plats` - Liste tous les plats
- `GET /api/plats/{id}` - Détails d'un plat
- `POST /api/plats` - Créer un plat
  ```json
  {
    "nom": "Pizza Margherita",
    "description": "Tomate, mozzarella, basilic",
    "prix": "8.90",
    "categorie": "Pizza",
    "image": "https://example.com/image.jpg"
  }
  ```
- `PUT /api/plats/{id}` - Modifier un plat
- `DELETE /api/plats/{id}` - Supprimer un plat
- `GET /api/plats/categorie/{categorie}` - Plats par catégorie

### Étudiants (`/api/etudiants`)

- `GET /api/etudiants` - Liste tous les étudiants
- `GET /api/etudiants/{id}` - Détails d'un étudiant
- `POST /api/etudiants` - Créer un étudiant
  ```json
  {
    "nom": "Doe",
    "prenom": "Jane",
    "email": "jane.doe@example.com",
    "filiere": "Informatique",
    "niveau": "Master 1",
    "numeroCarte": "CROUS123456",
    "solde": "45.50"
  }
  ```
- `PUT /api/etudiants/{id}` - Modifier un étudiant
- `DELETE /api/etudiants/{id}` - Supprimer un étudiant

### Lignes de Panier (`/api/lignes-panier`)

- `GET /api/lignes-panier` - Liste toutes les lignes de panier
- `GET /api/lignes-panier/{id}` - Détails d'une ligne de panier
- `POST /api/lignes-panier` - Créer une ligne de panier
  ```json
  {
    "etudiant_id": 1,
    "plat_id": 1,
    "quantite": 2
  }
  ```
- `PUT /api/lignes-panier/{id}` - Modifier une ligne de panier
- `DELETE /api/lignes-panier/{id}` - Supprimer une ligne de panier
- `GET /api/lignes-panier/etudiant/{etudiantId}` - Panier d'un étudiant

## Exemples

```bash
# Liste des plats
curl http://localhost:8000/api/plats

# Créer un plat
curl -X POST http://localhost:8000/api/plats \
  -H "Content-Type: application/json" \
  -d '{"nom":"Burger","prix":"9.50","categorie":"Burger"}'

# Panier d'un étudiant
curl http://localhost:8000/api/lignes-panier/etudiant/1
```
