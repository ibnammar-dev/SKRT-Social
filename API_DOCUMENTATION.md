# API Documentation

## Authentication

All authenticated endpoints require an `X-API-TOKEN` header with a valid API token.

### Register
- **URL**: `/api/auth/register`
- **Method**: `POST`
- **Auth required**: No
- **Request body**:
```json
{
    "email": "user@example.com",
    "password": "password123",
    "firstName": "John",
    "lastName": "Doe"
}
```
- **Success Response**:
  - **Code**: 201
  - **Content**:
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "id": 1
    }
}
```
- **Error Response**:
  - **Code**: 400
  - **Content**:
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["This value is already used."],
        "password": ["This value is too short."]
    }
}
```

### Login
- **URL**: `/api/auth/login`
- **Method**: `POST`
- **Auth required**: No
- **Request body**:
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "message": "",
    "data": {
        "token": "your-api-token-here",
        "user": {
            "id": 1,
            "email": "user@example.com",
            "firstName": "John",
            "lastName": "Doe",
            "roles": ["ROLE_USER"]
        }
    }
}
```
- **Error Response**:
  - **Code**: 401
  - **Content**:
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Logout
- **URL**: `/api/auth/logout`
- **Method**: `POST`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

## Users

### List Users (Admin only)
- **URL**: `/api/users`
- **Method**: `GET`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "data": {
        "users": [
            {
                "id": 1,
                "email": "user@example.com",
                "firstName": "John",
                "lastName": "Doe",
                "roles": ["ROLE_USER"],
                "createdAt": "2024-02-02T22:50:48+00:00"
            }
        ]
    }
}
```

### Get User Details
- **URL**: `/api/users/{id}`
- **Method**: `GET`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "email": "user@example.com",
        "firstName": "John",
        "lastName": "Doe",
        "roles": ["ROLE_USER"],
        "createdAt": "2024-02-02T22:50:48+00:00"
    }
}
```

### Update User
- **URL**: `/api/users/{id}`
- **Method**: `PUT`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Request body**:
```json
{
    "firstName": "John",
    "lastName": "Doe",
    "email": "new.email@example.com"
}
```
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "message": "User updated successfully",
    "data": {
        "id": 1,
        "email": "new.email@example.com",
        "firstName": "John",
        "lastName": "Doe"
    }
}
```

### Delete User (Admin only)
- **URL**: `/api/users/{id}`
- **Method**: `DELETE`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 204
  - **Content**: No content

## Posts

### List Posts
- **URL**: `/api/posts`
- **Method**: `GET`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Query Parameters**:
  - `page` (optional): Page number (default: 1)
  - `limit` (optional): Items per page (default: 10)
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "data": {
        "posts": [
            {
                "id": 1,
                "title": "Post Title",
                "content": "Post content...",
                "author": {
                    "id": 1,
                    "firstName": "John",
                    "lastName": "Doe"
                },
                "createdAt": "2024-02-02T22:50:48+00:00",
                "updatedAt": "2024-02-02T22:50:48+00:00"
            }
        ],
        "pagination": {
            "page": 1,
            "limit": 10,
            "total": 1,
            "pages": 1
        }
    }
}
```

### Get Post Details
- **URL**: `/api/posts/{id}`
- **Method**: `GET`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Post Title",
        "content": "Post content...",
        "author": {
            "id": 1,
            "firstName": "John",
            "lastName": "Doe"
        },
        "createdAt": "2024-02-02T22:50:48+00:00",
        "updatedAt": "2024-02-02T22:50:48+00:00"
    }
}
```

### Create Post
- **URL**: `/api/posts`
- **Method**: `POST`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Request body**:
```json
{
    "title": "Post Title",
    "content": "Post content..."
}
```
- **Success Response**:
  - **Code**: 201
  - **Content**:
```json
{
    "success": true,
    "message": "Post created successfully",
    "data": {
        "id": 1,
        "title": "Post Title",
        "content": "Post content..."
    }
}
```

### Update Post
- **URL**: `/api/posts/{id}`
- **Method**: `PUT`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Request body**:
```json
{
    "title": "Updated Title",
    "content": "Updated content..."
}
```
- **Success Response**:
  - **Code**: 200
  - **Content**:
```json
{
    "success": true,
    "message": "Post updated successfully",
    "data": {
        "id": 1,
        "title": "Updated Title",
        "content": "Updated content..."
    }
}
```

### Delete Post
- **URL**: `/api/posts/{id}`
- **Method**: `DELETE`
- **Auth required**: Yes
- **Headers**:
  - `X-API-TOKEN`: `<api-token>`
- **Success Response**:
  - **Code**: 204
  - **Content**: No content

## Error Responses

### Common Error Responses

#### Unauthorized
- **Code**: 401
- **Content**:
```json
{
    "success": false,
    "message": "No API token provided"
}
```

#### Forbidden
- **Code**: 403
- **Content**:
```json
{
    "success": false,
    "message": "Access denied"
}
```

#### Not Found
- **Code**: 404
- **Content**:
```json
{
    "success": false,
    "message": "Resource not found"
}
```

#### Validation Error
- **Code**: 400
- **Content**:
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field": ["Error message"]
    }
}
``` 