# JavaFX Blog Application Project Brief

## Project Overview
A JavaFX desktop application that connects to a Symfony-based REST API for managing a blog platform. The application provides a rich desktop interface for users to manage posts, user accounts, and administrative functions.

## Technical Stack
- **Frontend**: JavaFX 17+
- **Backend**: Symfony REST API
- **Authentication**: Custom token-based authentication
- **Data Format**: JSON
- **HTTP Client**: Java HttpClient or Apache HttpClient
- **JSON Processing**: Jackson library

## Core Features

### Authentication System
- Login screen with email/password
- Token-based authentication
- Auto-login capability with token storage
- Session management
- Logout functionality
- Role-based access control (User/Admin)

### User Management
- User registration
- Profile management
  - View profile
  - Edit profile information
  - Change password
  - Upload profile picture
- Admin features
  - User list management
  - User deletion
  - Role management

### Post Management
- Post listing with pagination
- Post creation with rich text editor
- Post editing
- Post deletion
- Image upload for posts
- Post preview
- Search and filtering capabilities

### Admin Dashboard
- User statistics
- Post statistics
- Recent activity tracking
- System monitoring

## UI/UX Requirements

### General Layout
- Modern Material Design or custom theme
- Responsive layout
- Dark/Light theme support
- Loading indicators for async operations
- Toast notifications for user feedback

### Main Views
1. **Login/Register View**
   - Clean, minimal design
   - Form validation
   - Error messaging
   - Password strength indicator

2. **Dashboard View**
   - Statistics cards
   - Recent posts list
   - Quick action buttons
   - Activity feed

3. **Posts Management View**
   - Data table with sorting/filtering
   - Pagination controls
   - Search functionality
   - Bulk actions
   - Quick edit/delete options

4. **User Management View**
   - User list with filters
   - Role management
   - Status indicators
   - Action buttons

5. **Profile View**
   - Profile information display
   - Edit form
   - Password change section
   - Profile picture upload

### Components
- Custom dialog boxes
- Loading spinners
- Toast notifications
- Confirmation dialogs
- Error handling displays
- Progress indicators
- Search bars
- Filter panels

## Technical Requirements

### API Integration
- Token management
- Request/Response handling
- Error handling
- Retry mechanism
- Timeout handling
- Offline capability

### Data Management
- Local caching
- State management
- Data validation
- Error handling
- Data persistence

### Security
- Secure token storage
- Input validation
- XSS prevention
- CSRF protection
- Secure password handling

## Project Structure

```
src/
├── main/
│   ├── java/
│   │   └── com/blog/
│   │       ├── App.java
│   │       ├── config/
│   │       │   ├── ApiConfig.java
│   │       │   └── AppConfig.java
│   │       ├── controllers/
│   │       │   ├── AuthController.java
│   │       │   ├── DashboardController.java
│   │       │   ├── PostController.java
│   │       │   └── UserController.java
│   │       ├── models/
│   │       │   ├── Post.java
│   │       │   ├── User.java
│   │       │   └── ApiResponse.java
│   │       ├── services/
│   │       │   ├── AuthService.java
│   │       │   ├── PostService.java
│   │       │   └── UserService.java
│   │       ├── utils/
│   │       │   ├── ApiClient.java
│   │       │   ├── TokenManager.java
│   │       │   └── ValidationUtils.java
│   │       └── views/
│   │           ├── components/
│   │           └── fxml/
│   └── resources/
│       ├── css/
│       ├── fxml/
│       └── images/
└── test/
    └── java/
        └── com/blog/
```

## Development Guidelines

### Code Style
- Follow Java naming conventions
- Use proper JavaFX patterns (MVC/MVVM)
- Implement proper error handling
- Write comprehensive documentation
- Include unit tests

### Best Practices
- Implement proper thread management
- Use Platform.runLater() for UI updates
- Implement proper resource cleanup
- Use dependency injection
- Follow SOLID principles
- Implement proper logging

### Error Handling
- User-friendly error messages
- Proper exception handling
- Graceful degradation
- Network error handling
- API error handling

## API Integration Details

### Authentication
```java
public class AuthService {
    private final ApiClient apiClient;
    private final TokenManager tokenManager;

    public CompletableFuture<User> login(String email, String password) {
        // Implementation
    }

    public CompletableFuture<Void> logout() {
        // Implementation
    }

    public CompletableFuture<User> register(UserRegistration registration) {
        // Implementation
    }
}
```

### Post Management
```java
public class PostService {
    private final ApiClient apiClient;

    public CompletableFuture<Page<Post>> getPosts(int page, int size) {
        // Implementation
    }

    public CompletableFuture<Post> createPost(PostCreation post) {
        // Implementation
    }

    public CompletableFuture<Post> updatePost(Long id, PostUpdate post) {
        // Implementation
    }

    public CompletableFuture<Void> deletePost(Long id) {
        // Implementation
    }
}
```

### User Management
```java
public class UserService {
    private final ApiClient apiClient;

    public CompletableFuture<Page<User>> getUsers(int page, int size) {
        // Implementation
    }

    public CompletableFuture<User> updateUser(Long id, UserUpdate user) {
        // Implementation
    }

    public CompletableFuture<Void> deleteUser(Long id) {
        // Implementation
    }
}
```

## Testing Strategy
1. Unit Tests
   - Service layer tests
   - Utility class tests
   - Model validation tests

2. Integration Tests
   - API integration tests
   - Database interaction tests
   - Authentication flow tests

3. UI Tests
   - Component tests
   - Navigation tests
   - Form validation tests

## Deployment
- Application packaging
- Auto-update mechanism
- Configuration management
- Logging setup
- Error reporting

## Performance Considerations
- Lazy loading of data
- Efficient caching
- Background processing
- Memory management
- Resource cleanup 