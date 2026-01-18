# Nizaam REST API Documentation

## Base URL
```
http://localhost:8080/nizaam/public/api
```

## Authentication

### Bearer Token Authentication
All API endpoints (except authentication endpoints) require a valid Bearer token in the Authorization header.

**How to Authenticate:**

1. **Login to get a token:**
   ```bash
   POST /api/auth/login
   ```
   
2. **Include the token in all subsequent requests:**
   ```
   Authorization: Bearer your_token_here
   ```

3. **Token expires in 24 hours** - use `/api/auth/refresh` to get a new token

**Authorization Header Format:**
```
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

---

## Authentication Endpoints

### 1. Login (Get Token)
**POST** `/api/auth/login`

**No authentication required**

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "your_password"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "token": "a1b2c3d4e5f6...your_bearer_token...xyz",
  "token_type": "Bearer",
  "expires_in": 86400,
  "expires_at": "2026-01-19 14:30:00",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "role": "employee",
    "employee_id": 5,
    "full_name": "John Doe"
  }
}
```

**Error Response (401):**
```json
{
  "error": "Invalid credentials"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost:8080/nizaam/public/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

---

### 2. Logout (Revoke Token)
**POST** `/api/auth/logout`

**Requires authentication**

Invalidates the current Bearer token.

**Request Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost:8080/nizaam/public/api/auth/logout \
  -H "Authorization: Bearer your_token_here"
```

---

### 3. Refresh Token
**POST** `/api/auth/refresh`

**Requires authentication**

Get a new token before the current one expires. The old token will be invalidated.

**Request Headers:**
```
Authorization: Bearer your_current_token
```

**Success Response (200):**
```json
{
  "success": true,
  "token": "new_bearer_token_here",
  "token_type": "Bearer",
  "expires_in": 86400,
  "expires_at": "2026-01-19 14:30:00"
}
```

**cURL Example:**
```bash
curl -X POST http://localhost:8080/nizaam/public/api/auth/refresh \
  -H "Authorization: Bearer your_current_token"
```

---

### 4. Get Current User Info
**GET** `/api/auth/me`

**Requires authentication**

Get information about the currently authenticated user.

**Request Headers:**
```
Authorization: Bearer your_token_here
```

**Success Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "email": "user@example.com",
    "role": "employee",
    "employee_id": 5,
    "employee_code": "EMP001",
    "full_name": "John Doe",
    "designation": "Software Developer",
    "department": "Engineering"
  }
}
```

**cURL Example:**
```bash
curl -X GET http://localhost:8080/nizaam/public/api/auth/me \
  -H "Authorization: Bearer your_token_here"
```

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "error": "Error message description"
}
```

### HTTP Status Codes
- `200 OK` - Request successful
- `400 Bad Request` - Invalid input or missing required fields
- `401 Unauthorized` - Missing, invalid, or expired token
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

---

## Work Items API

**All endpoints require Bearer token authentication**

### 1. List All Work Items
**GET** `/api/work-items`

**Query Parameters:**
- `type` (optional): Filter by type (task, leave_request, expense, timesheet)
- `project_id` (optional): Filter by project ID
- `priority` (optional): Filter by priority (low, medium, high, urgent)
- `status_id` (optional): Filter by status ID

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Complete project documentation",
      "description": "<p>Rich text content with <img src='data:image/png;base64,...'/></p>",
      "type": "task",
      "priority": "high",
      "status_name": "In Progress",
      "assigned_to": 2,
      "assignee_name": "John Doe",
      "created_at": "2026-01-15 10:30:00"
    }
  ]
}
```

---

### 2. Get Single Work Item
**GET** `/api/work-items/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Complete project documentation",
    "description": "<p>Detailed description...</p>",
    "type": "task",
    "priority": "high",
    "due_date": "2026-01-25",
    "current_status_id": 3,
    "status_name": "In Progress",
    "status_color": "#3b82f6",
    "created_by": 1,
    "creator_name": "System Administrator",
    "assigned_to": 2,
    "assignee_name": "John Doe",
    "project_id": 5,
    "project_name": "Website Redesign",
    "metadata": null,
    "created_at": "2026-01-15 10:30:00",
    "updated_at": "2026-01-18 14:20:00"
  }
}
```

---

### 3. Create Work Item
**POST** `/api/work-items`

**Request Body:**
```json
{
  "type": "task",
  "title": "New task title",
  "description": "<p>Rich text description with images</p>",
  "priority": "high",
  "assigned_to": 2,
  "due_date": "2026-01-30",
  "project_id": 5
}
```

**Required Fields:** `type`, `title`, `priority`

**Response:**
```json
{
  "success": true,
  "message": "Work item created successfully",
  "data": { /* work item object */ }
}
```

---

### 4. Update Work Item
**PUT** `/api/work-items/{id}`

**Request Body:**
```json
{
  "title": "Updated title",
  "description": "<p>Updated description</p>",
  "assigned_to": 3,
  "priority": "urgent",
  "due_date": "2026-02-01",
  "project_id": 6
}
```

**Note:** Only include fields you want to update.

---

### 5. Delete Work Item
**DELETE** `/api/work-items/{id}`

**Response:**
```json
{
  "success": true,
  "message": "Work item deleted successfully",
  "data": null
}
```

---

### 6. Update Work Item Status
**POST** `/api/work-items/{id}/status`

**Request Body:**
```json
{
  "to_status_id": 4,
  "comment": "Moving to testing phase"
}
```

**Required Fields:** `to_status_id`

---

### 7. Add Comment to Work Item
**POST** `/api/work-items/{id}/comments`

**Request Body:**
```json
{
  "comment": "<p>This is a comment with <strong>rich text</strong></p>"
}
```

**Required Fields:** `comment`

---

## Projects API

### 1. List All Projects
**GET** `/api/projects`

**Query Parameters:**
- `status` (optional): Filter by status (active, on_hold, completed, archived)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Website Redesign",
      "description": "<p>Complete overhaul of company website</p>",
      "status": "active",
      "owner_id": 1,
      "owner_name": "John Doe",
      "created_at": "2026-01-10 09:00:00"
    }
  ]
}
```

---

### 2. Get Single Project
**GET** `/api/projects/{id}`

---

### 3. Create Project
**POST** `/api/projects`

**Request Body:**
```json
{
  "name": "New Project",
  "description": "<p>Project description with rich text</p>",
  "owner_id": 2,
  "status": "active"
}
```

**Required Fields:** `name`

---

### 4. Update Project
**PUT** `/api/projects/{id}`

**Request Body:**
```json
{
  "name": "Updated Project Name",
  "description": "<p>Updated description</p>",
  "owner_id": 3,
  "status": "on_hold"
}
```

---

### 5. Delete Project
**DELETE** `/api/projects/{id}`

---

### 6. Add Project Member
**POST** `/api/projects/{id}/members`

**Request Body:**
```json
{
  "employee_id": 5
}
```

---

### 7. Remove Project Member
**DELETE** `/api/projects/{id}/members/{employeeId}`

---

## Employees API

### 1. List All Employees
**GET** `/api/employees`

**Query Parameters:**
- `department` (optional): Filter by department
- `status` (optional): Filter by employment status (active, on_leave, terminated, resigned)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "employee_id": "EMP001",
      "full_name": "John Doe",
      "email": "john@example.com",
      "designation": "Senior Developer",
      "department": "Engineering",
      "employment_status": "active",
      "join_date": "2024-01-15",
      "manager_id": null,
      "manager_name": null
    }
  ]
}
```

---

### 2. Get Single Employee
**GET** `/api/employees/{id}`

---

### 3. Create Employee
**POST** `/api/employees` (Admin only)

**Request Body:**
```json
{
  "email": "newemployee@example.com",
  "password": "securepassword",
  "full_name": "Jane Smith",
  "employee_id": "EMP025",
  "designation": "Software Engineer",
  "department": "Engineering",
  "join_date": "2026-02-01",
  "manager_id": 5,
  "employment_status": "active",
  "role": "user"
}
```

**Required Fields:** `email`, `password`, `full_name`, `employee_id`, `designation`, `department`, `join_date`

---

### 4. Update Employee
**PUT** `/api/employees/{id}`

**Request Body:**
```json
{
  "full_name": "Jane Smith-Johnson",
  "designation": "Senior Software Engineer",
  "department": "Engineering",
  "manager_id": 3,
  "employment_status": "active"
}
```

---

### 5. Delete Employee
**DELETE** `/api/employees/{id}` (Admin only)

---

## Leaves API

### 1. List All Leave Requests
**GET** `/api/leaves`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 10,
      "title": "Leave Request: 2026-02-01 to 2026-02-05",
      "description": "<p>Vacation leave</p>",
      "type": "leave_request",
      "status_name": "Pending Approval",
      "created_by": 2,
      "creator_name": "John Doe",
      "metadata": {
        "leave_type_id": 1,
        "start_date": "2026-02-01",
        "end_date": "2026-02-05"
      }
    }
  ]
}
```

---

### 2. Get Single Leave Request
**GET** `/api/leaves/{id}`

---

### 3. Create Leave Request
**POST** `/api/leaves`

**Request Body:**
```json
{
  "leave_type_id": 1,
  "start_date": "2026-02-01",
  "end_date": "2026-02-05",
  "reason": "<p>Taking vacation leave for family trip</p>"
}
```

**Required Fields:** `leave_type_id`, `start_date`, `end_date`, `reason`

---

### 4. Get Leave Types
**GET** `/api/leave-types`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Annual Leave",
      "code": "AL",
      "description": "Paid annual vacation leave",
      "is_paid": true,
      "requires_approval": true
    }
  ]
}
```

---

### 5. Get Leave Balance
**GET** `/api/leave-balance`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "leave_type_id": 1,
      "leave_type_name": "Annual Leave",
      "quota": 20,
      "used": 5,
      "remaining": 15
    }
  ]
}
```

---

## Expenses API

### 1. List All Expenses
**GET** `/api/expenses`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 15,
      "title": "Expense: Travel - $450.00",
      "description": "<p>Flight and hotel expenses</p>",
      "type": "expense",
      "status_name": "Pending Approval",
      "metadata": {
        "amount": "450.00",
        "category": "Travel",
        "date": "2026-01-15"
      }
    }
  ]
}
```

---

### 2. Get Single Expense
**GET** `/api/expenses/{id}`

---

### 3. Create Expense
**POST** `/api/expenses`

**Request Body:**
```json
{
  "amount": "450.00",
  "category": "Travel",
  "date": "2026-01-15",
  "description": "<p>Flight and hotel expenses for client meeting</p>"
}
```

**Required Fields:** `amount`, `category`, `date`, `description`

---

### 4. Delete Expense
**DELETE** `/api/expenses/{id}`

---

## Timesheets API

### 1. List All Timesheets
**GET** `/api/timesheets`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 20,
      "title": "Timesheet: Week ending 2026-01-17 (40 hours)",
      "description": "<p>Weekly hours logged</p>",
      "type": "timesheet",
      "status_name": "Approved",
      "metadata": {
        "week_ending": "2026-01-17",
        "hours": {
          "monday": 8,
          "tuesday": 8,
          "wednesday": 8,
          "thursday": 8,
          "friday": 8,
          "saturday": 0,
          "sunday": 0
        },
        "total_hours": 40
      }
    }
  ]
}
```

---

### 2. Get Single Timesheet
**GET** `/api/timesheets/{id}`

---

### 3. Create Timesheet
**POST** `/api/timesheets`

**Request Body:**
```json
{
  "week_ending": "2026-01-17",
  "hours": {
    "monday": 8,
    "tuesday": 8,
    "wednesday": 8,
    "thursday": 8,
    "friday": 8,
    "saturday": 0,
    "sunday": 0
  },
  "notes": "<p>Regular work week</p>"
}
```

**Required Fields:** `week_ending`, `hours`

---

### 4. Delete Timesheet
**DELETE** `/api/timesheets/{id}`

---

## HTTP Status Codes

- **200 OK**: Request successful
- **400 Bad Request**: Invalid request data
- **401 Unauthorized**: Authentication required
- **403 Forbidden**: Insufficient permissions
- **404 Not Found**: Resource not found
- **500 Internal Server Error**: Server error

---

## Testing with cURL

**Note:** First obtain a Bearer token using the login endpoint, then use it in all subsequent requests.

### Example: Login
```bash
curl -X POST "http://localhost:8080/nizaam/public/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

### Example: List Work Items
```bash
curl -X GET "http://localhost:8080/nizaam/public/api/work-items" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here"
```

### Example: Create Work Item
```bash
curl -X POST "http://localhost:8080/nizaam/public/api/work-items" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here" \
  -d '{
    "type": "task",
    "title": "API Test Task",
    "description": "<p>Testing API</p>",
    "priority": "medium"
  }'
```

### Example: Update Work Item
```bash
curl -X PUT "http://localhost:8080/nizaam/public/api/work-items/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here" \
  -d '{
    "title": "Updated Title",
    "priority": "high"
  }'
```

### Example: Delete Work Item
```bash
curl -X DELETE "http://localhost:8080/nizaam/public/api/work-items/1" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your_token_here"
```

---

## Testing with Postman

1. **Import Collection**: Create a new collection in Postman
2. **Set Base URL**: Use `http://localhost:8080/nizaam/public/api` as base URL
3. **Get Bearer Token**:
   - Send POST request to `/auth/login` with email and password
   - Copy the `token` from the response
4. **Configure Authorization**:
   - Go to Collection settings → Authorization tab
   - Type: Bearer Token
   - Token: Paste your token
5. **Set Headers**:
   - `Content-Type: application/json`
   - Authorization header will be added automatically
6. **Send Requests**: All requests in the collection will use the Bearer token

**Token Management:**
- Tokens expire after 24 hours
- Use `/auth/refresh` endpoint to get a new token
- Store the token securely and don't expose it in version control

---

## Notes

- All API endpoints (except `/api/auth/login`, `/api/auth/refresh`) require Bearer token authentication
- Include the token in the Authorization header: `Authorization: Bearer your_token_here`
- Tokens expire after 24 hours - refresh before expiration to maintain access
- All rich text fields (descriptions, comments) support HTML including base64 encoded images
- Dates should be in `YYYY-MM-DD` format
- Timestamps are in `YYYY-MM-DD HH:MM:SS` format
- The API uses Bearer token authentication stored in the `api_tokens` database table

