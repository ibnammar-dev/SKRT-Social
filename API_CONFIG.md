# API Configuration

## Base URL
```
API_BASE_URL=http://localhost:8000/api
```

## Authentication Endpoints
```
AUTH_LOGIN=/auth/login
AUTH_REGISTER=/auth/register
AUTH_LOGOUT=/auth/logout
```

## User Endpoints
```
USERS_LIST=/users
USER_DETAILS=/users/{id}
USER_UPDATE=/users/{id}
USER_DELETE=/users/{id}
USER_CHANGE_PASSWORD=/users/{id}/password
```

## Post Endpoints
```
POSTS_LIST=/posts
POST_DETAILS=/posts/{id}
POST_CREATE=/posts
POST_UPDATE=/posts/{id}
POST_DELETE=/posts/{id}
```

## Headers
```
Content-Type: application/json
X-API-TOKEN: <token>
```

## Response Format
All API responses follow this format:
```json
{
    "success": true|false,
    "message": "Optional message",
    "data": {
        // Response data
    }
}
```

## Error Format
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Error messages"]
    }
}
```

## Pagination Parameters
```
page: Page number (default: 1)
limit: Items per page (default: 10, max: 50)
```

## Authentication
- All authenticated endpoints require `X-API-TOKEN` header
- Token is obtained from login response
- Token expires after 24 hours
- Invalid/expired tokens return 401 Unauthorized

## Role-Based Access
- Regular users can:
  - View/edit their own profile
  - Create/edit/delete their own posts
  - View all posts
  - View other users' profiles

- Admin users can:
  - Manage all users
  - Manage all posts
  - Access admin dashboard
  - Change user roles

## Rate Limiting
- 60 requests per minute per IP
- 1000 requests per hour per user

## File Upload Limits
- Profile pictures: max 2MB, jpg/png only
- Post images: max 5MB, jpg/png only

## Security Notes
1. Always validate input before sending
2. Handle token expiration gracefully
3. Implement proper error handling
4. Store tokens securely
5. Implement request retry mechanism
6. Handle network errors appropriately 