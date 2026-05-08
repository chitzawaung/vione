# Vending Machine Product API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
Most endpoints require authentication using Laravel Sanctum tokens. Include the token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## Endpoints

### Public Endpoints (No Authentication Required)

#### GET /api/products
Get a paginated list of all products.

**Query Parameters:**
- `search` (string, optional): Search products by name or description
- `in_stock` (boolean, optional): Filter products that are in stock
- `sort_by` (string, optional): Sort field (id, name, price, quantity_available, created_at)
- `sort_direction` (string, optional): Sort direction (asc, desc)
- `per_page` (integer, optional): Items per page (default: 15, max: 100)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Coca Cola",
            "description": "Refreshing cola drink",
            "price": 1.50,
            "quantity_available": 25,
            "in_stock": true,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75,
        "from": 1,
        "to": 15
    },
    "links": {
        "first": "http://your-domain.com/api/products?page=1",
        "last": "http://your-domain.com/api/products?page=5",
        "prev": null,
        "next": "http://your-domain.com/api/products?page=2"
    }
}
```

#### GET /api/products/{id}
Get a single product by ID.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Coca Cola",
        "description": "Refreshing cola drink",
        "price": 1.50,
        "quantity_available": 25,
        "in_stock": true,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }
}
```

### Protected Endpoints (Authentication Required)

#### POST /api/products
Create a new product.

**Request Body:**
```json
{
    "name": "New Product",
    "description": "Product description",
    "price": 2.99,
    "quantity_available": 50
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `description`: optional, string, max 1000 characters
- `price`: required, numeric, min 0, max 999999.99
- `quantity_available`: required, integer, min 0, max 999999

**Response:**
```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 76,
        "name": "New Product",
        "description": "Product description",
        "price": 2.99,
        "quantity_available": 50,
        "in_stock": true,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T00:00:00.000000Z"
    }
}
```

#### PUT /api/products/{id}
Update an existing product.

**Request Body:** Same as POST endpoint (all fields optional)

**Response:**
```json
{
    "success": true,
    "message": "Product updated successfully",
    "data": {
        "id": 1,
        "name": "Updated Product Name",
        "description": "Updated description",
        "price": 3.99,
        "quantity_available": 30,
        "in_stock": true,
        "created_at": "2023-01-01T00:00:00.000000Z",
        "updated_at": "2023-01-01T12:00:00.000000Z"
    }
}
```

#### DELETE /api/products/{id}
Delete a product.

**Response:**
```json
{
    "success": true,
    "message": "Product deleted successfully"
}
```

### Admin Endpoints (Admin Role Required)

#### GET /api/products/reports/low-stock
Get products with low stock (less than 5 items).

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Rare Item",
            "description": "Hard to find item",
            "price": 10.00,
            "quantity_available": 3,
            "in_stock": true,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### GET /api/products/reports/out-of-stock
Get products that are out of stock.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 2,
            "name": "Sold Out Item",
            "description": "Currently unavailable",
            "price": 5.00,
            "quantity_available": 0,
            "in_stock": false,
            "created_at": "2023-01-01T00:00:00.000000Z",
            "updated_at": "2023-01-01T00:00:00.000000Z"
        }
    ]
}
```

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "price": ["The price must be at least 0."]
    }
}
```

### Not Found (404)
```json
{
    "success": false,
    "message": "Product not found"
}
```

### Unauthorized (401)
```json
{
    "success": false,
    "message": "Unauthenticated"
}
```

### Forbidden (403)
```json
{
    "success": false,
    "message": "This action is unauthorized"
}
```

## Rate Limiting
API requests are limited to 60 requests per minute per IP address.

## Usage Examples

### JavaScript (Fetch API)
```javascript
// Get all products
const response = await fetch('http://your-domain.com/api/products');
const data = await response.json();

// Create a product (with authentication)
const createResponse = await fetch('http://your-domain.com/api/products', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer your_token_here'
    },
    body: JSON.stringify({
        name: 'New Product',
        price: 2.99,
        quantity_available: 50
    })
});
```

### cURL Examples
```bash
# Get products
curl -X GET "http://your-domain.com/api/products"

# Create product
curl -X POST "http://your-domain.com/api/products" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here" \
  -d '{
    "name": "New Product",
    "price": 2.99,
    "quantity_available": 50
  }'

# Update product
curl -X PUT "http://your-domain.com/api/products/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here" \
  -d '{
    "price": 3.99
  }'

# Delete product
curl -X DELETE "http://your-domain.com/api/products/1" \
  -H "Authorization: Bearer your_token_here"
```
