<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## REST APIs — Laravel Sanctum Authentication

This project implements a full-featured **REST API** using **Laravel** and **Laravel Sanctum** for token-based authentication. It includes user management with **CRUD operations**, **Soft Deletes**, and **Restore** functionality.

---

## Features

- 🔐 Token-based authentication via Laravel Sanctum
- 👤 User CRUD (Create, Read, Update, Delete)
- 🗑️ Soft Deletes with Restore support
- 📋 Paginated user listing
- ✅ Form Request validation with consistent JSON error responses
- 🔄 Token refresh and multi-device logout

---

## API Endpoints

### Authentication

| Method | Endpoint            | Auth Required | Description                        |
|--------|---------------------|---------------|------------------------------------|
| POST   | `/api/register`     | ❌             | Register a new user                |
| POST   | `/api/login`        | ❌             | Login and receive access token     |
| GET    | `/api/profile`      | ✅             | Get authenticated user's profile   |
| GET    | `/api/logout`       | ✅             | Logout (revoke current token)      |
| GET    | `/api/logout-all`   | ✅             | Logout from all devices            |
| GET    | `/api/refresh`      | ✅             | Refresh the access token           |

### User Management

| Method | Endpoint                      | Auth Required | Description                    |
|--------|-------------------------------|---------------|--------------------------------|
| GET    | `/api/users-list`             | ✅             | List all active users          |
| GET    | `/api/users-profile`          | ✅             | Get a specific user's profile  |
| GET    | `/api/update-profile`         | ✅             | Update user profile            |
| GET    | `/api/delete`                 | ✅             | Soft delete a user             |
| GET    | `/api/deleted-users-list`     | ✅             | List all soft-deleted users    |
| GET    | `/api/restore-user`           | ✅             | Restore a soft-deleted user    |

---

## Soft Deletes & Restore

Users are **soft deleted** — they are not permanently removed from the database. Instead, a `deleted_at` timestamp is recorded, allowing them to be restored later.

- `delete` — sets `deleted_at`, hides user from normal queries
- `deleted-users-list` — returns only soft-deleted users (using `onlyTrashed()`)
- `restore-user` — clears `deleted_at`, making the user active again

To enable soft deletes, the `User` model uses the `SoftDeletes` trait and the `users` table includes a `deleted_at` column.

---

## Authentication

All protected routes require a Bearer token in the `Authorization` header:

```
Authorization: Bearer {your_token}
Accept: application/json
```

**Example (cURL):**
```bash
curl -X GET http://localhost:8000/api/users-list \
  -H "Authorization: Bearer 1|your_token_here" \
  -H "Accept: application/json"
```

---

## Response Format

All responses follow a consistent envelope:

```json
{
  "status": true,
  "message": "Success message.",
  "data": { }
}
```

**Validation Error (422):**
```json
{
  "status": false,
  "message": "Validation failed.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

**Unauthorized (401):**
```json
{
  "message": "Unauthenticated."
}
```

---
