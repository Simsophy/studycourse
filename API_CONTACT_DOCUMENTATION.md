# Contact API Documentation

## Overview
This API allows users to submit contact messages and admins to manage and reply to those messages.

## Authentication
All endpoints require authentication using Sanctum tokens. Include the token in the `Authorization` header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## User Contact Endpoints

### 1. Get User's Contacts
**Endpoint:** `GET /api/user/contacts`

**Authentication:** Required (User)

**Response:**
```json
{
  "success": true,
  "message": "Contacts retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "subject": "Course Inquiry",
        "message": "I have a question about...",
        "user_id": 1,
        "status": "pending",
        "admin_reply": null,
        "created_at": "2026-03-02T12:00:00Z",
        "updated_at": "2026-03-02T12:00:00Z"
      }
    ],
    "per_page": 10,
    "total": 1
  }
}
```

---

### 2. Submit New Contact Message
**Endpoint:** `POST /api/user/contacts`

**Authentication:** Required (User)

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "Course Inquiry",
  "message": "I would like to know more about..."
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: required, valid email, max 255 characters
- `subject`: required, string, max 255 characters
- `message`: required, string, minimum 10 characters

**Response (201):**
```json
{
  "success": true,
  "message": "Contact message submitted successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Course Inquiry",
    "message": "I would like to know more about...",
    "user_id": 1,
    "status": "pending",
    "admin_reply": null,
    "created_at": "2026-03-02T12:00:00Z",
    "updated_at": "2026-03-02T12:00:00Z"
  }
}
```

---

### 3. Get Specific Contact
**Endpoint:** `GET /api/user/contacts/{contact_id}`

**Authentication:** Required (User - must own the contact)

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Course Inquiry",
    "message": "I would like to know more about...",
    "user_id": 1,
    "status": "read",
    "admin_reply": "Thank you for your inquiry...",
    "created_at": "2026-03-02T12:00:00Z",
    "updated_at": "2026-03-02T12:00:00Z"
  }
}
```

---

### 4. Update Own Contact Message
**Endpoint:** `PUT /api/user/contacts/{contact_id}`

**Authentication:** Required (User - must own the contact)

**Note:** Only contacts with status "pending" can be updated

**Request Body:** (all fields optional)
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "subject": "Updated Subject",
  "message": "Updated message content..."
}
```

**Response:**
```json
{
  "success": true,
  "message": "Contact updated successfully",
  "data": { /* updated contact object */ }
}
```

---

### 5. Delete Own Contact Message
**Endpoint:** `DELETE /api/user/contacts/{contact_id}`

**Authentication:** Required (User - must own the contact)

**Response:**
```json
{
  "success": true,
  "message": "Contact deleted successfully"
}
```

---

## Admin Contact Endpoints

### 1. Get All Contacts (With Filtering)
**Endpoint:** `GET /api/admin/contacts`

**Authentication:** Required (Admin only)

**Query Parameters:**
- `status`: Filter by status (pending, read, replied) - optional
- `search`: Search in name, email, subject, message - optional
- `per_page`: Items per page (default: 15) - optional
- `page`: Page number - optional

**Example:** `/api/admin/contacts?status=pending&search=course`

**Response:**
```json
{
  "success": true,
  "message": "All contacts retrieved",
  "data": {
    "current_page": 1,
    "data": [ /* array of contacts */ ],
    "per_page": 15,
    "total": 10
  }
}
```

---

### 2. Get Contact Statistics
**Endpoint:** `GET /api/admin/contacts/statistics`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "data": {
    "total": 45,
    "pending": 12,
    "read": 20,
    "replied": 13
  }
}
```

---

### 3. Get Specific Contact
**Endpoint:** `GET /api/admin/contacts/{contact_id}`

**Authentication:** Required (Admin only)

**Note:** Automatically marks contact as "read" if currently pending

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Course Inquiry",
    "message": "I would like to know more about...",
    "user_id": 1,
    "status": "read",
    "admin_reply": null,
    "created_at": "2026-03-02T12:00:00Z",
    "updated_at": "2026-03-02T12:00:00Z"
  }
}
```

---

### 4. Reply to Contact Message
**Endpoint:** `POST /api/admin/contacts/{contact_id}/reply`

**Authentication:** Required (Admin only)

**Request Body:**
```json
{
  "admin_reply": "Thank you for contacting us! Your message has been received and will be addressed shortly. We appreciate your inquiry about..."
}
```

**Validation Rules:**
- `admin_reply`: required, string, minimum 10 characters

**Response:**
```json
{
  "success": true,
  "message": "Reply sent successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Course Inquiry",
    "message": "I would like to know more about...",
    "user_id": 1,
    "status": "replied",
    "admin_reply": "Thank you for contacting us!...",
    "created_at": "2026-03-02T12:00:00Z",
    "updated_at": "2026-03-02T12:00:00Z"
  }
}
```

---

### 5. Mark Contact as Read
**Endpoint:** `PATCH /api/admin/contacts/{contact_id}/mark-read`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "message": "Contact marked as read",
  "data": {
    "id": 1,
    "status": "read",
    /* ... other fields ... */
  }
}
```

---

### 6. Delete Contact
**Endpoint:** `DELETE /api/admin/contacts/{contact_id}`

**Authentication:** Required (Admin only)

**Response:**
```json
{
  "success": true,
  "message": "Contact deleted successfully"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized - Admin access required"
}
```

### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Cannot update contact that has been read or replied to"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Contact not found"
}
```

---

## Contact Status Definitions

- **pending**: Contact message submitted but not yet read by admin
- **read**: Contact message has been read by admin but not yet replied to
- **replied**: Admin has replied to the contact message

---

## cURL Examples

### Submit a contact message
```bash
curl -X POST http://localhost/api/user/contacts \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Course Inquiry",
    "message": "I would like to know more about your web development course."
  }'
```

### Get pending contacts (Admin)
```bash
curl -X GET "http://localhost/api/admin/contacts?status=pending" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Reply to contact (Admin)
```bash
curl -X POST http://localhost/api/admin/contacts/1/reply \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "admin_reply": "Thank you for your inquiry! We will get back to you soon."
  }'
```

---

## Database Structure

### contacts table
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| name | STRING | Sender name |
| email | STRING | Sender email |
| subject | STRING | Message subject |
| message | LONGTEXT | Message content |
| user_id | INT (FK) | Associated user (nullable) |
| status | ENUM | pending, read, replied |
| admin_reply | LONGTEXT | Admin response (nullable) |
| created_at | TIMESTAMP | Creation time |
| updated_at | TIMESTAMP | Last update time |
