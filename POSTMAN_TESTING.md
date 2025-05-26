# Postman Testing Guide

## Setup Instructions

### 1. Environment Setup
1. Create a new environment in Postman named "Blog API"
2. Add the following variables:
   ```
   base_url: http://localhost:8000
   api_token: <your-token>
   csrf_token: <your-csrf-token>
   ```

### 2. Collection Setup
1. Create a new collection named "Blog API Tests"
2. Add the following folder structure:
   - Authentication
   - Users
   - Posts
   - Admin

## Authentication Tests

### 1. Register User
- **Request Name**: Register New User
- **Method**: POST
- **URL**: `{{base_url}}/api/auth/register`
- **Headers**:
  ```
  Content-Type: application/json
  ```
- **Body**:
  ```json
  {
    "email": "test@example.com",
    "password": "password123",
    "firstName": "Test",
    "lastName": "User"
  }
  ```
- **Tests**:
  ```javascript
  pm.test("Status code is 201", function () {
      pm.response.to.have.status(201);
  });
  
  pm.test("Response has success flag", function () {
      var jsonData = pm.response.json();
      pm.expect(jsonData.success).to.be.true;
  });
  ```

### 2. Login
- **Request Name**: User Login
- **Method**: POST
- **URL**: `{{base_url}}/api/auth/login`
- **Headers**:
  ```
  Content-Type: application/json
  ```
- **Body**:
  ```json
  {
    "email": "test@example.com",
    "password": "password123"
  }
  ```
- **Tests**:
  ```javascript
  pm.test("Status code is 200", function () {
      pm.response.to.have.status(200);
  });
  
  pm.test("Response contains token", function () {
      var jsonData = pm.response.json();
      pm.expect(jsonData.data.token).to.exist;
      pm.environment.set("api_token", jsonData.data.token);
  });
  ```

## Post Management Tests

### 1. Create Post
- **Request Name**: Create New Post
- **Method**: POST
- **URL**: `{{base_url}}/api/posts`
- **Headers**:
  ```
  Content-Type: multipart/form-data
  X-API-TOKEN: {{api_token}}
  ```
- **Body**:
  ```
  title: Test Post
  content: This is a test post content
  image: [file]
  ```
- **Tests**:
  ```javascript
  pm.test("Status code is 201", function () {
      pm.response.to.have.status(201);
  });
  ```

### 2. List Posts
- **Request Name**: Get All Posts
- **Method**: GET
- **URL**: `{{base_url}}/api/posts`
- **Headers**:
  ```
  X-API-TOKEN: {{api_token}}
  ```
- **Tests**:
  ```javascript
  pm.test("Status code is 200", function () {
      pm.response.to.have.status(200);
  });
  
  pm.test("Response contains posts array", function () {
      var jsonData = pm.response.json();
      pm.expect(jsonData.data.posts).to.be.an('array');
  });
  ```

## User Management Tests

### 1. Get User Profile
- **Request Name**: Get User Profile
- **Method**: GET
- **URL**: `{{base_url}}/api/users/me`
- **Headers**:
  ```
  X-API-TOKEN: {{api_token}}
  ```
- **Tests**:
  ```javascript
  pm.test("Status code is 200", function () {
      pm.response.to.have.status(200);
  });
  ```

## Test Collection Runner

### Setup
1. Create a new collection runner
2. Select the "Blog API Tests" collection
3. Select the "Blog API" environment
4. Set the following order:
   1. Register User
   2. Login
   3. Create Post
   4. List Posts
   5. Get User Profile

### Running Tests
1. Click "Run" to execute all tests
2. Monitor the test results in the Postman console
3. Check the environment variables are being set correctly

## Common Test Cases

### 1. Authentication Tests
- Test invalid credentials
- Test expired token
- Test missing token
- Test invalid token format

### 2. Post Management Tests
- Test post creation with missing fields
- Test post update with invalid data
- Test post deletion with invalid ID
- Test image upload with invalid file type

### 3. User Management Tests
- Test profile update with invalid data
- Test password change with invalid current password
- Test user deletion with existing posts

## Error Handling Tests

### 1. 400 Bad Request
```javascript
pm.test("Bad Request Response", function () {
    pm.response.to.have.status(400);
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.be.false;
    pm.expect(jsonData.errors).to.exist;
});
```

### 2. 401 Unauthorized
```javascript
pm.test("Unauthorized Response", function () {
    pm.response.to.have.status(401);
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.be.false;
    pm.expect(jsonData.message).to.equal("Invalid credentials");
});
```

### 3. 403 Forbidden
```javascript
pm.test("Forbidden Response", function () {
    pm.response.to.have.status(403);
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.be.false;
    pm.expect(jsonData.message).to.equal("Access denied");
});
```

## Environment Variables

### Required Variables
```
base_url: http://localhost:8000
api_token: <token>
csrf_token: <token>
user_id: <id>
post_id: <id>
```

### Optional Variables
```
test_email: test@example.com
test_password: password123
test_first_name: Test
test_last_name: User
```

## Pre-request Scripts

### CSRF Token
```javascript
pm.sendRequest({
    url: pm.environment.get("base_url") + "/csrf-token",
    method: "GET"
}, function (err, res) {
    if (err) {
        console.error(err);
    } else {
        pm.environment.set("csrf_token", res.json().token);
    }
});
```

## Postman Collection Export

To use this testing suite:
1. Import the collection JSON file
2. Set up the environment variables
3. Run the collection runner
4. Monitor the test results

## Best Practices

1. **Environment Management**
   - Use separate environments for development and production
   - Never commit sensitive data in environment files
   - Use environment variables for all dynamic values

2. **Test Organization**
   - Group related tests together
   - Use descriptive test names
   - Include both positive and negative test cases

3. **Error Handling**
   - Test all possible error scenarios
   - Verify error messages and codes
   - Check error response format

4. **Data Management**
   - Clean up test data after tests
   - Use unique identifiers for test data
   - Avoid hardcoding test values

5. **Security**
   - Never store sensitive data in tests
   - Use environment variables for credentials
   - Test security-related endpoints thoroughly 