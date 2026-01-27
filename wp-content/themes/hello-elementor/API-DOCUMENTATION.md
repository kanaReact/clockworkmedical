# ClockWork Medical - Mobile App API Documentation

**Base URL:** `https://your-domain.com/wp-json`
**API Version:** v1
**Last Updated:** January 2026

---

## Table of Contents

1. [Authentication](#authentication)
2. [User Registration](#1-user-registration)
3. [User Login](#2-user-login)
4. [User Logout](#3-user-logout)
5. [Get Profile](#4-get-profile)
6. [Update Profile](#5-update-profile)
7. [Change Password](#6-change-password)
8. [Forgot Password](#7-forgot-password)
9. [Meetings API](#meetings-api)
10. [Error Codes](#error-codes)

---

## Authentication

Protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer <token>
```

**Token Details:**
- Tokens are returned on successful login/registration
- Token validity: 30 days
- Store token securely on device
- Token is invalidated on logout or password change

---

## User Authentication APIs

### 1. User Registration

Register a new user account.

**Endpoint:** `POST /clockwork/v1/register`
**Authentication:** Not required

#### Request Body

```json
{
    "email": "user@example.com",
    "password": "password123",
    "first_name": "John",
    "last_name": "Doe",
    "username": "johndoe"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Valid email address |
| password | string | Yes | Minimum 6 characters |
| first_name | string | No | User's first name |
| last_name | string | No | User's last name |
| username | string | No | Unique username (auto-generated from email if not provided) |

#### Success Response (201 Created)

```json
{
    "status": "success",
    "message": "Registration successful",
    "data": {
        "id": 123,
        "username": "johndoe",
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "name": "John Doe",
        "registered": "2026-01-27 10:30:00",
        "role": "customer",
        "active_memberships": []
    },
    "token": "abc123xyz..."
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 400 | Email is required |
| 400 | Invalid email format |
| 400 | Password is required |
| 400 | Password must be at least 6 characters |
| 409 | Email already registered |
| 409 | Username already taken |

---

### 2. User Login

Authenticate user and receive access token.

**Endpoint:** `POST /clockwork/v1/login`
**Authentication:** Not required

#### Request Body

```json
{
    "email": "user@example.com",
    "password": "password123",
    "role": "customer"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Registered email address |
| password | string | Yes | User password |
| role | string | No | Filter by role (customer, subscriber, cm_exhibitor) |

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "id": 123,
        "username": "johndoe",
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "name": "John Doe",
        "registered": "2026-01-27 10:30:00",
        "role": "customer",
        "active_memberships": [
            {
                "membership_id": 1,
                "plan_name": "Premium Plan",
                "start_date": "2026-01-01 00:00:00",
                "end_date": "2026-12-31 23:59:59",
                "status": "active"
            }
        ]
    },
    "token": "abc123xyz..."
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 400 | Email and password are required |
| 401 | Invalid email or password |
| 403 | Access denied for this role |

---

### 3. User Logout

Invalidate user's authentication token.

**Endpoint:** `POST /clockwork/v1/logout`
**Authentication:** Required (Bearer token)

#### Request Headers

```
Authorization: Bearer <token>
Content-Type: application/json
```

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Logged out successfully"
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 401 | Authorization token is required |
| 401 | Invalid or expired token |

---

### 4. Get Profile

Retrieve authenticated user's profile information.

**Endpoint:** `GET /clockwork/v1/profile`
**Authentication:** Required (Bearer token)

#### Request Headers

```
Authorization: Bearer <token>
```

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Profile retrieved successfully",
    "data": {
        "id": 123,
        "username": "johndoe",
        "email": "user@example.com",
        "first_name": "John",
        "last_name": "Doe",
        "name": "John Doe",
        "registered": "2026-01-27 10:30:00",
        "role": "customer",
        "active_memberships": [],
        "billing": {
            "phone": "+1234567890",
            "address": "123 Main Street",
            "address2": "Apt 4B",
            "city": "London",
            "state": "Greater London",
            "postcode": "SW1A 1AA",
            "country": "GB"
        }
    }
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 401 | Authorization token is required |
| 401 | Invalid or expired token |

---

### 5. Update Profile

Update authenticated user's profile information.

**Endpoint:** `PUT /clockwork/v1/profile`
**Authentication:** Required (Bearer token)

#### Request Headers

```
Authorization: Bearer <token>
Content-Type: application/json
```

#### Request Body

```json
{
    "first_name": "John",
    "last_name": "Smith",
    "email": "newemail@example.com",
    "billing": {
        "phone": "+1234567890",
        "address": "123 Main Street",
        "address2": "Apt 4B",
        "city": "London",
        "state": "Greater London",
        "postcode": "SW1A 1AA",
        "country": "GB"
    }
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| first_name | string | No | User's first name |
| last_name | string | No | User's last name |
| email | string | No | New email address (must be unique) |
| billing | object | No | Billing/shipping address details |
| billing.phone | string | No | Phone number |
| billing.address | string | No | Street address line 1 |
| billing.address2 | string | No | Street address line 2 |
| billing.city | string | No | City |
| billing.state | string | No | State/Province |
| billing.postcode | string | No | Postal/ZIP code |
| billing.country | string | No | Country code (e.g., GB, US) |

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Profile updated successfully",
    "data": {
        "id": 123,
        "username": "johndoe",
        "email": "newemail@example.com",
        "first_name": "John",
        "last_name": "Smith",
        "name": "John Smith",
        "registered": "2026-01-27 10:30:00",
        "role": "customer",
        "active_memberships": [],
        "billing": {
            "phone": "+1234567890",
            "address": "123 Main Street",
            "address2": "Apt 4B",
            "city": "London",
            "state": "Greater London",
            "postcode": "SW1A 1AA",
            "country": "GB"
        }
    }
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 400 | Invalid email format |
| 401 | Authorization token is required |
| 401 | Invalid or expired token |
| 409 | Email already in use |

---

### 6. Change Password

Change authenticated user's password.

**Endpoint:** `POST /clockwork/v1/change-password`
**Authentication:** Required (Bearer token)

#### Request Headers

```
Authorization: Bearer <token>
Content-Type: application/json
```

#### Request Body

```json
{
    "current_password": "oldpassword123",
    "new_password": "newpassword456"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| current_password | string | Yes | Current password |
| new_password | string | Yes | New password (minimum 6 characters) |

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Password changed successfully",
    "token": "newtoken123xyz..."
}
```

**Note:** A new token is returned after password change. The old token becomes invalid.

#### Error Responses

| Status | Message |
|--------|---------|
| 400 | Current password and new password are required |
| 400 | New password must be at least 6 characters |
| 401 | Authorization token is required |
| 401 | Invalid or expired token |
| 401 | Current password is incorrect |

---

### 7. Forgot Password

Request password reset email.

**Endpoint:** `POST /clockwork/v1/forgot-password`
**Authentication:** Not required

#### Request Body

```json
{
    "email": "user@example.com"
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | Registered email address |

#### Success Response (200 OK)

```json
{
    "status": "success",
    "message": "Password reset email sent",
    "data": [
        "ID: 123",
        "username: johndoe",
        "name: John Doe",
        "email: user@example.com",
        "role: customer",
        "reset_link: https://your-domain.com/wp-login.php?action=rp&key=..."
    ]
}
```

#### Error Responses

| Status | Message |
|--------|---------|
| 400 | Email is required |
| 404 | No user found with this email |
| 500 | Could not generate reset key |

---

## Meetings API

### List Meetings

Get paginated list of meetings.

**Endpoint:** `GET /clockwork/v1/meetings`
**Authentication:** Not required

#### Query Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |

**Note:** Results are limited to 5 items per page.

#### Success Response (200 OK)

```json
{
    "success": true,
    "message": "Clockwork Medical Meetings",
    "current_page": 1,
    "per_page": 5,
    "total_items": 25,
    "total_pages": 5,
    "data": [
        {
            "id": 101,
            "name": "Annual Medical Conference 2026",
            "date": "2026-03-15",
            "date_end": "2026-03-17",
            "price": "299.00",
            "url": "https://your-domain.com/product/annual-conference-2026/",
            "product_id": 101,
            "sku": "AMC2026",
            "stock_status": "instock",
            "stock_quantity": 50,
            "description": "Join us for the annual medical conference...",
            "image": "https://your-domain.com/wp-content/uploads/2026/01/conference.jpg"
        }
    ]
}
```

---

### Get Single Meeting

Get detailed information about a specific meeting.

**Endpoint:** `GET /clockwork/v1/meetings/{id}`
**Authentication:** Not required

#### URL Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| id | integer | Yes | Meeting/Product ID |

#### Success Response (200 OK)

```json
{
    "success": true,
    "message": "Single Clockwork Meeting",
    "data": {
        "id": 101,
        "name": "Annual Medical Conference 2026",
        "date": "2026-03-15",
        "date_end": "2026-03-17",
        "price": "299.00",
        "url": "https://your-domain.com/product/annual-conference-2026/",
        "product_id": 101,
        "sku": "AMC2026",
        "stock_status": "instock",
        "stock_quantity": 50,
        "image": "https://your-domain.com/wp-content/uploads/2026/01/conference.jpg",
        "is_in_stock": true,
        "speaker": [
            {
                "id": 201,
                "name": "Dr. Jane Smith",
                "image": "https://your-domain.com/wp-content/uploads/speaker.jpg",
                "bio_teaser": "Leading expert in cardiology..."
            }
        ],
        "meeting_convenors": [
            {
                "id": 202,
                "name": "Prof. John Brown",
                "image": "https://your-domain.com/wp-content/uploads/convenor.jpg",
                "bio_teaser": "Department head at..."
            }
        ],
        "sponsors": {
            "premium_sponsors": [
                {
                    "title": "Medical Corp",
                    "image_url": "https://your-domain.com/wp-content/uploads/sponsor1.jpg",
                    "link_url": "https://sponsor-website.com"
                }
            ],
            "gold_sponsors": []
        }
    }
}
```

---

## Other APIs

### List Customers

**Endpoint:** `GET /custom-api/v1/customers`
**Authentication:** Not required

### List Subscribers

**Endpoint:** `GET /custom-api/v1/subscribers`
**Authentication:** Not required

### List Exhibitors

**Endpoint:** `GET /custom-api/v1/exhibitors`
**Authentication:** Not required

---

## Error Codes

| HTTP Status | Meaning |
|-------------|---------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Invalid or missing token |
| 403 | Forbidden - Access denied |
| 404 | Not Found - Resource not found |
| 409 | Conflict - Resource already exists |
| 500 | Internal Server Error |

---

## Response Format

All API responses follow this structure:

```json
{
    "status": "success|error",
    "message": "Human readable message",
    "data": { },
    "token": "Only included for auth endpoints"
}
```

---

## Mobile App Implementation Notes

### Storing Token
```javascript
// After login/register, store token securely
await SecureStorage.setItem('auth_token', response.token);
```

### Making Authenticated Requests
```javascript
const response = await fetch('https://your-domain.com/wp-json/clockwork/v1/profile', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    }
});
```

### Handling Token Expiry
If you receive a 401 response, redirect user to login screen and clear stored token.

---

## Contact

For API issues or questions, contact the development team.
