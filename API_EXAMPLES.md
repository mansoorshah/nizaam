# Nizaam API - Sample Requests & Responses

This document provides complete sample requests and responses for all API endpoints with realistic data.

---

## 1. Authentication API

### Login
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/auth/login
Content-Type: application/json

{
  "email": "admin@nizaam.com",
  "password": "admin123"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "token": "a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450",
  "token_type": "Bearer",
  "expires_in": 86400,
  "expires_at": "2026-01-19 14:30:50",
  "user": {
    "id": 1,
    "email": "admin@nizaam.com",
    "role": "admin",
    "employee_id": 1,
    "full_name": "System Administrator"
  }
}
```

---

### Get Current User
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/auth/me
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "email": "admin@nizaam.com",
    "role": "admin",
    "employee_id": 1,
    "employee_code": "EMP001",
    "full_name": "System Administrator",
    "designation": "Chief Technology Officer",
    "department": "IT"
  }
}
```

---

### Refresh Token
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/auth/refresh
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "token": "9876fedcba0987654321fedcba098765432109876543210987654321fedcba09_1_1737220150",
  "token_type": "Bearer",
  "expires_in": 86400,
  "expires_at": "2026-01-19 15:30:50"
}
```

---

### Logout
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/auth/logout
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

## 2. Work Items API

### List All Work Items
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/work-items
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Implement User Authentication Module",
      "description": "<p>Design and implement a secure user authentication system with the following features:</p><ul><li>Login/Logout functionality</li><li>Password hashing with bcrypt</li><li>Session management</li><li>Remember me option</li></ul><p>Architecture diagram:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUA...\" style=\"max-width: 100%;\"/>",
      "type": "task",
      "priority": "high",
      "status_name": "In Progress",
      "assigned_to": 2,
      "assignee_name": "John Doe",
      "created_at": "2026-01-15 10:30:00",
      "due_date": "2026-01-25"
    },
    {
      "id": 2,
      "title": "Annual Leave Request - Summer Vacation",
      "description": "<p>Requesting 10 days of annual leave for summer vacation.</p><p>Dates: June 1-10, 2026</p>",
      "type": "leave_request",
      "priority": "medium",
      "status_name": "Pending Approval",
      "assigned_to": null,
      "assignee_name": null,
      "created_at": "2026-01-16 09:15:00",
      "due_date": null
    },
    {
      "id": 3,
      "title": "Business Travel Expense - Client Meeting",
      "description": "<p>Expense report for client meeting in New York:</p><ul><li>Flight tickets: $650</li><li>Hotel (2 nights): $400</li><li>Meals: $150</li><li>Transportation: $80</li></ul><p>Receipt attached:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABg...\" style=\"max-width: 100%;\"/>",
      "type": "expense",
      "priority": "medium",
      "status_name": "Submitted",
      "assigned_to": null,
      "assignee_name": null,
      "created_at": "2026-01-17 14:20:00",
      "due_date": null
    }
  ]
}
```

---

### List Work Items with Filters
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/work-items?type=task&priority=high&project_id=5
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Implement User Authentication Module",
      "description": "<p>Design and implement a secure user authentication system...</p>",
      "type": "task",
      "priority": "high",
      "status_name": "In Progress",
      "assigned_to": 2,
      "assignee_name": "John Doe",
      "project_id": 5,
      "project_name": "Website Redesign",
      "created_at": "2026-01-15 10:30:00",
      "due_date": "2026-01-25"
    }
  ]
}
```

---

### Get Single Work Item
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/work-items/1
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Implement User Authentication Module",
    "description": "<p>Design and implement a secure user authentication system with the following features:</p><ul><li>Login/Logout functionality</li><li>Password hashing with bcrypt</li><li>Session management</li><li>Remember me option</li></ul><p>Architecture diagram:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUA...\" style=\"max-width: 100%;\"/>",
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

### Create Work Item (Task)
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/work-items
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "type": "task",
  "title": "Fix Payment Gateway Integration",
  "description": "<p>The payment gateway is returning timeout errors during checkout.</p><h3>Steps to reproduce:</h3><ol><li>Add items to cart</li><li>Proceed to checkout</li><li>Enter payment details</li><li>Submit payment</li></ol><p>Error screenshot:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAQACAYAAAB/...\" style=\"max-width: 100%; height: auto;\"/><h3>Expected behavior:</h3><p>Payment should process within 5 seconds and redirect to confirmation page.</p><h3>Actual behavior:</h3><p>Gateway times out after 30 seconds with error code 504.</p>",
  "priority": "urgent",
  "assigned_to": 3,
  "due_date": "2026-01-20",
  "project_id": 7
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Work item created successfully",
  "data": {
    "id": 15,
    "title": "Fix Payment Gateway Integration",
    "description": "<p>The payment gateway is returning timeout errors during checkout.</p><h3>Steps to reproduce:</h3><ol><li>Add items to cart</li><li>Proceed to checkout</li><li>Enter payment details</li><li>Submit payment</li></ol><p>Error screenshot:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAQACAYAAAB/...\" style=\"max-width: 100%; height: auto;\"/><h3>Expected behavior:</h3><p>Payment should process within 5 seconds and redirect to confirmation page.</p><h3>Actual behavior:</h3><p>Gateway times out after 30 seconds with error code 504.</p>",
    "type": "task",
    "priority": "urgent",
    "current_status_id": 1,
    "status_name": "New",
    "created_by": 1,
    "assigned_to": 3,
    "project_id": 7,
    "due_date": "2026-01-20",
    "created_at": "2026-01-18 15:45:00"
  }
}
```

---

### Create Work Item (Leave Request)
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/work-items
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "type": "leave_request",
  "title": "Medical Leave - Surgery Recovery",
  "description": "<p>Requesting medical leave for surgery and recovery period.</p><p>Medical certificate attached:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/...\" style=\"max-width: 100%;\"/><p><strong>Surgery Date:</strong> January 22, 2026</p><p><strong>Expected Recovery:</strong> 2 weeks</p>",
  "priority": "high",
  "metadata": {
    "leave_type_id": 3,
    "start_date": "2026-01-22",
    "end_date": "2026-02-05"
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Work item created successfully",
  "data": {
    "id": 16,
    "title": "Medical Leave - Surgery Recovery",
    "type": "leave_request",
    "priority": "high",
    "current_status_id": 1,
    "status_name": "Pending Approval",
    "metadata": {
      "leave_type_id": 3,
      "start_date": "2026-01-22",
      "end_date": "2026-02-05"
    },
    "created_at": "2026-01-18 15:50:00"
  }
}
```

---

### Create Work Item (Expense)
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/work-items
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "type": "expense",
  "title": "Office Supplies Purchase",
  "description": "<p>Monthly office supplies reimbursement request.</p><h3>Items purchased:</h3><ul><li>Printer paper (5 reams): $45</li><li>Ink cartridges (3): $120</li><li>Notebooks and pens: $35</li><li>Sticky notes and markers: $25</li></ul><p>Total: $225.00</p><p>Receipts:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QBqRXhpZg...\" style=\"max-width: 100%; margin: 10px 0;\"/><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QC8RXhpZg...\" style=\"max-width: 100%; margin: 10px 0;\"/>",
  "priority": "medium",
  "metadata": {
    "amount": 225.00,
    "category": "Office Supplies",
    "date": "2026-01-18"
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Work item created successfully",
  "data": {
    "id": 17,
    "title": "Office Supplies Purchase",
    "type": "expense",
    "priority": "medium",
    "current_status_id": 1,
    "status_name": "Submitted",
    "metadata": {
      "amount": 225.00,
      "category": "Office Supplies",
      "date": "2026-01-18"
    },
    "created_at": "2026-01-18 16:00:00"
  }
}
```

---

### Update Work Item
**Request:**
```bash
PUT http://localhost:8080/nizaam/public/api/work-items/15
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "title": "Fix Payment Gateway Integration - URGENT",
  "priority": "urgent",
  "assigned_to": 4,
  "due_date": "2026-01-19",
  "description": "<p>The payment gateway is returning timeout errors during checkout. <strong>UPDATE:</strong> Also affecting mobile app.</p><h3>Steps to reproduce:</h3><ol><li>Add items to cart</li><li>Proceed to checkout</li><li>Enter payment details</li><li>Submit payment</li></ol><p>Error screenshot (Web):</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAQACAYAAAB/...\" style=\"max-width: 100%;\"/><p>Error screenshot (Mobile):</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD/...\" style=\"max-width: 100%;\"/>"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Work item updated successfully",
  "data": {
    "id": 15,
    "title": "Fix Payment Gateway Integration - URGENT",
    "priority": "urgent",
    "assigned_to": 4,
    "assignee_name": "Sarah Johnson",
    "due_date": "2026-01-19",
    "updated_at": "2026-01-18 16:15:00"
  }
}
```

---

### Update Work Item Status
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/work-items/15/status
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "to_status_id": 3,
  "comment": "<p>Started working on the payment gateway issue.</p><h3>Initial findings:</h3><ul><li>Gateway timeout set to 30s</li><li>API endpoint responding slowly (~25s average)</li><li>Network latency issues detected</li></ul><p>Network trace diagram:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAoAAAAHgCAYAAAA1...\" style=\"max-width: 100%;\"/><p>Will implement retry logic and increase timeout to 45s as temporary fix.</p>"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Status updated successfully",
  "data": {
    "work_item_id": 15,
    "from_status": "New",
    "to_status": "In Progress",
    "comment": "<p>Started working on the payment gateway issue.</p><h3>Initial findings:</h3><ul><li>Gateway timeout set to 30s</li><li>API endpoint responding slowly (~25s average)</li><li>Network latency issues detected</li></ul><p>Network trace diagram:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAoAAAAHgCAYAAAA1...\" style=\"max-width: 100%;\"/><p>Will implement retry logic and increase timeout to 45s as temporary fix.</p>",
    "updated_at": "2026-01-18 16:30:00"
  }
}
```

---

### Add Comment to Work Item
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/work-items/15/comments
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "comment": "<p>Update: Payment gateway issue has been resolved!</p><h3>Solution implemented:</h3><ol><li>Increased timeout from 30s to 45s</li><li>Added retry logic (max 3 attempts)</li><li>Implemented exponential backoff</li><li>Added better error logging</li></ol><p>Performance comparison:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA4AAAAFoCAYAAAAP...\" style=\"max-width: 100%; border: 1px solid #ddd; border-radius: 8px;\"/><h3>Test Results:</h3><ul><li>✅ 100 successful test transactions</li><li>✅ Average response time: 8.5s</li><li>✅ Zero timeouts</li><li>✅ Mobile app working correctly</li></ul><p>Ready for deployment to production.</p>"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Comment added successfully",
  "data": {
    "comment_id": 42,
    "work_item_id": 15,
    "comment": "<p>Update: Payment gateway issue has been resolved!</p><h3>Solution implemented:</h3><ol><li>Increased timeout from 30s to 45s</li><li>Added retry logic (max 3 attempts)</li><li>Implemented exponential backoff</li><li>Added better error logging</li></ol><p>Performance comparison:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA4AAAAFoCAYAAAAP...\" style=\"max-width: 100%; border: 1px solid #ddd; border-radius: 8px;\"/><h3>Test Results:</h3><ul><li>✅ 100 successful test transactions</li><li>✅ Average response time: 8.5s</li><li>✅ Zero timeouts</li><li>✅ Mobile app working correctly</li></ul><p>Ready for deployment to production.</p>",
    "created_by": 4,
    "created_by_name": "Sarah Johnson",
    "created_at": "2026-01-18 17:45:00"
  }
}
```

---

### Delete Work Item
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/work-items/17
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Work item deleted successfully",
  "data": null
}
```

---

## 3. Projects API

### List All Projects
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/projects
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 5,
      "name": "Website Redesign",
      "description": "<p>Complete redesign of company website with modern UI/UX.</p><h3>Goals:</h3><ul><li>Improve user experience</li><li>Mobile-first responsive design</li><li>Increase page load speed by 50%</li><li>Implement new brand guidelines</li></ul><p>Design mockup:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABLAAAASwCAYAAAD/...\" style=\"max-width: 100%;\"/>",
      "status": "active",
      "start_date": "2026-01-01",
      "end_date": "2026-03-31",
      "created_at": "2025-12-15 10:00:00"
    },
    {
      "id": 7,
      "name": "E-Commerce Platform Integration",
      "description": "<p>Integrate third-party e-commerce platform for online sales.</p><p>Platform: Shopify</p><p>Integration architecture:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAOACAYAAAA/...\" style=\"max-width: 100%;\"/>",
      "status": "active",
      "start_date": "2026-01-10",
      "end_date": "2026-04-30",
      "created_at": "2026-01-05 14:30:00"
    }
  ]
}
```

---

### Get Single Project
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/projects/5
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 5,
    "name": "Website Redesign",
    "description": "<p>Complete redesign of company website with modern UI/UX.</p><h3>Goals:</h3><ul><li>Improve user experience</li><li>Mobile-first responsive design</li><li>Increase page load speed by 50%</li><li>Implement new brand guidelines</li></ul><p>Design mockup:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABLAAAASwCAYAAAD/...\" style=\"max-width: 100%;\"/><h3>Technology Stack:</h3><ul><li>Frontend: React + TypeScript</li><li>Backend: Node.js + Express</li><li>Database: PostgreSQL</li><li>Hosting: AWS</li></ul>",
    "status": "active",
    "start_date": "2026-01-01",
    "end_date": "2026-03-31",
    "budget": 50000.00,
    "manager_id": 2,
    "manager_name": "John Doe",
    "created_at": "2025-12-15 10:00:00",
    "updated_at": "2026-01-10 16:20:00"
  }
}
```

---

### Create Project
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/projects
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "name": "Mobile App Development",
  "description": "<p>Develop native mobile applications for iOS and Android.</p><h3>Features:</h3><ul><li>User authentication</li><li>Push notifications</li><li>Offline mode</li><li>Real-time sync</li><li>In-app purchases</li></ul><p>App wireframes:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABDAAAAMgCAYAAAAw/...\" style=\"max-width: 100%; margin: 10px 0;\"/><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABDAAAAMgCAYAAAAx/...\" style=\"max-width: 100%; margin: 10px 0;\"/><h3>Timeline:</h3><p>Phase 1: Design & Prototyping (4 weeks)<br/>Phase 2: Development (12 weeks)<br/>Phase 3: Testing & QA (4 weeks)<br/>Phase 4: Deployment (2 weeks)</p>",
  "status": "active",
  "start_date": "2026-02-01",
  "end_date": "2026-07-31",
  "budget": 120000.00
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "id": 10,
    "name": "Mobile App Development",
    "status": "active",
    "start_date": "2026-02-01",
    "end_date": "2026-07-31",
    "budget": 120000.00,
    "created_at": "2026-01-18 17:00:00"
  }
}
```

---

### Update Project
**Request:**
```bash
PUT http://localhost:8080/nizaam/public/api/projects/10
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "name": "Mobile App Development - iOS & Android",
  "budget": 135000.00,
  "end_date": "2026-08-15"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Project updated successfully",
  "data": {
    "id": 10,
    "name": "Mobile App Development - iOS & Android",
    "budget": 135000.00,
    "end_date": "2026-08-15",
    "updated_at": "2026-01-18 17:15:00"
  }
}
```

---

### Add Project Member
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/projects/10/members
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "employee_id": 5
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Member added to project successfully",
  "data": {
    "project_id": 10,
    "employee_id": 5,
    "employee_name": "Michael Chen",
    "added_at": "2026-01-18 17:20:00"
  }
}
```

---

### Remove Project Member
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/projects/10/members/5
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Member removed from project successfully",
  "data": null
}
```

---

### Delete Project
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/projects/10
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Project deleted successfully",
  "data": null
}
```

---

## 4. Employees API

### List All Employees
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/employees
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "employee_id": "EMP001",
      "full_name": "System Administrator",
      "email": "admin@nizaam.com",
      "designation": "Chief Technology Officer",
      "department": "IT",
      "join_date": "2020-01-15",
      "employment_status": "active"
    },
    {
      "id": 2,
      "employee_id": "EMP002",
      "full_name": "John Doe",
      "email": "john.doe@nizaam.com",
      "designation": "Senior Software Developer",
      "department": "Engineering",
      "join_date": "2021-03-20",
      "employment_status": "active"
    },
    {
      "id": 3,
      "employee_id": "EMP003",
      "full_name": "Jane Smith",
      "email": "jane.smith@nizaam.com",
      "designation": "UI/UX Designer",
      "department": "Design",
      "join_date": "2022-06-10",
      "employment_status": "active"
    }
  ]
}
```

---

### Get Single Employee
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/employees/2
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "employee_id": "EMP002",
    "full_name": "John Doe",
    "email": "john.doe@nizaam.com",
    "phone": "+1-555-0123",
    "designation": "Senior Software Developer",
    "department": "Engineering",
    "join_date": "2021-03-20",
    "employment_status": "active",
    "manager_id": 1,
    "manager_name": "System Administrator",
    "address": "123 Main Street, San Francisco, CA 94102",
    "date_of_birth": "1990-05-15",
    "emergency_contact": "Mary Doe - +1-555-0199",
    "role": "employee",
    "created_at": "2021-03-15 10:30:00",
    "updated_at": "2025-12-10 14:20:00"
  }
}
```

---

### Create Employee
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/employees
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "employee_id": "EMP015",
  "full_name": "Emily Rodriguez",
  "email": "emily.rodriguez@nizaam.com",
  "password": "SecurePass123!",
  "phone": "+1-555-0178",
  "designation": "Backend Developer",
  "department": "Engineering",
  "join_date": "2026-02-01",
  "manager_id": 2,
  "address": "456 Oak Avenue, San Francisco, CA 94103",
  "date_of_birth": "1995-08-22",
  "emergency_contact": "Carlos Rodriguez - +1-555-0180"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Employee created successfully",
  "data": {
    "id": 15,
    "employee_id": "EMP015",
    "full_name": "Emily Rodriguez",
    "email": "emily.rodriguez@nizaam.com",
    "designation": "Backend Developer",
    "department": "Engineering",
    "join_date": "2026-02-01",
    "employment_status": "active",
    "created_at": "2026-01-18 17:30:00"
  }
}
```

---

### Update Employee
**Request:**
```bash
PUT http://localhost:8080/nizaam/public/api/employees/15
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "designation": "Senior Backend Developer",
  "phone": "+1-555-0179",
  "address": "789 Pine Street, San Francisco, CA 94104"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Employee updated successfully",
  "data": {
    "id": 15,
    "designation": "Senior Backend Developer",
    "phone": "+1-555-0179",
    "address": "789 Pine Street, San Francisco, CA 94104",
    "updated_at": "2026-01-18 17:45:00"
  }
}
```

---

### Delete Employee
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/employees/15
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Employee deleted successfully",
  "data": null
}
```

---

## 5. Leaves API

### List All Leaves
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/leaves
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "title": "Annual Leave Request - Summer Vacation",
      "description": "<p>Requesting 10 days of annual leave for summer vacation.</p><p>Dates: June 1-10, 2026</p><p>Flight booking confirmation:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAQACAYAAACx...\" style=\"max-width: 100%;\"/>",
      "leave_type": "Annual Leave",
      "start_date": "2026-06-01",
      "end_date": "2026-06-10",
      "days": 10,
      "status": "Pending Approval",
      "employee_id": 2,
      "employee_name": "John Doe",
      "created_at": "2026-01-16 09:15:00"
    },
    {
      "id": 16,
      "title": "Medical Leave - Surgery Recovery",
      "description": "<p>Requesting medical leave for surgery and recovery period.</p><p>Medical certificate:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/...\" style=\"max-width: 100%;\"/>",
      "leave_type": "Medical Leave",
      "start_date": "2026-01-22",
      "end_date": "2026-02-05",
      "days": 14,
      "status": "Approved",
      "employee_id": 3,
      "employee_name": "Jane Smith",
      "created_at": "2026-01-18 15:50:00"
    }
  ]
}
```

---

### Get Single Leave
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/leaves/16
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 16,
    "title": "Medical Leave - Surgery Recovery",
    "description": "<p>Requesting medical leave for surgery and recovery period.</p><p>Medical certificate attached:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/...\" style=\"max-width: 100%;\"/><p><strong>Surgery Date:</strong> January 22, 2026</p><p><strong>Expected Recovery:</strong> 2 weeks</p><p><strong>Doctor:</strong> Dr. Sarah Williams</p><p><strong>Hospital:</strong> San Francisco General Hospital</p>",
    "leave_type_id": 3,
    "leave_type": "Medical Leave",
    "start_date": "2026-01-22",
    "end_date": "2026-02-05",
    "days": 14,
    "status": "Approved",
    "employee_id": 3,
    "employee_name": "Jane Smith",
    "approved_by": 1,
    "approver_name": "System Administrator",
    "approved_at": "2026-01-18 16:30:00",
    "created_at": "2026-01-18 15:50:00"
  }
}
```

---

### Create Leave Request
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/leaves
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "title": "Paternity Leave",
  "description": "<p>Requesting paternity leave for the birth of my child.</p><p><strong>Due Date:</strong> February 15, 2026</p><p><strong>Requested Leave:</strong> 2 weeks</p><p>Hospital pre-registration confirmation:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAOACAYAAAAy...\" style=\"max-width: 100%; border-radius: 8px;\"/><p>I will be available for emergency calls if needed.</p>",
  "metadata": {
    "leave_type_id": 5,
    "start_date": "2026-02-15",
    "end_date": "2026-02-28"
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Leave request created successfully",
  "data": {
    "id": 20,
    "title": "Paternity Leave",
    "leave_type_id": 5,
    "leave_type": "Paternity Leave",
    "start_date": "2026-02-15",
    "end_date": "2026-02-28",
    "days": 14,
    "status": "Pending Approval",
    "created_at": "2026-01-18 18:00:00"
  }
}
```

---

### Get Leave Types
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/leave-types
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Annual Leave",
      "description": "<p>Paid annual vacation leave</p>",
      "days_per_year": 20,
      "is_paid": 1
    },
    {
      "id": 2,
      "name": "Sick Leave",
      "description": "<p>Leave for illness or medical appointments</p>",
      "days_per_year": 10,
      "is_paid": 1
    },
    {
      "id": 3,
      "name": "Medical Leave",
      "description": "<p>Extended leave for serious medical conditions</p>",
      "days_per_year": 30,
      "is_paid": 1
    },
    {
      "id": 4,
      "name": "Maternity Leave",
      "description": "<p>Leave for pregnancy and childbirth</p>",
      "days_per_year": 90,
      "is_paid": 1
    },
    {
      "id": 5,
      "name": "Paternity Leave",
      "description": "<p>Leave for new fathers</p>",
      "days_per_year": 14,
      "is_paid": 1
    }
  ]
}
```

---

### Get Leave Balance
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/leave-balance
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "employee_id": 2,
    "employee_name": "John Doe",
    "year": 2026,
    "balances": [
      {
        "leave_type": "Annual Leave",
        "quota": 20,
        "used": 3,
        "pending": 10,
        "remaining": 7
      },
      {
        "leave_type": "Sick Leave",
        "quota": 10,
        "used": 2,
        "pending": 0,
        "remaining": 8
      },
      {
        "leave_type": "Medical Leave",
        "quota": 30,
        "used": 0,
        "pending": 0,
        "remaining": 30
      }
    ]
  }
}
```

---

## 6. Expenses API

### List All Expenses
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/expenses
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 3,
      "title": "Business Travel Expense - Client Meeting",
      "description": "<p>Expense report for client meeting in New York:</p><ul><li>Flight tickets: $650</li><li>Hotel (2 nights): $400</li><li>Meals: $150</li><li>Transportation: $80</li></ul><p>Receipt:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/...\" style=\"max-width: 100%;\"/>",
      "amount": 1280.00,
      "category": "Travel",
      "date": "2026-01-17",
      "status": "Submitted",
      "employee_id": 2,
      "employee_name": "John Doe",
      "created_at": "2026-01-17 14:20:00"
    },
    {
      "id": 17,
      "title": "Office Supplies Purchase",
      "description": "<p>Monthly office supplies reimbursement request.</p><p>Receipts:</p><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/...\" style=\"max-width: 100%;\"/>",
      "amount": 225.00,
      "category": "Office Supplies",
      "date": "2026-01-18",
      "status": "Submitted",
      "employee_id": 4,
      "employee_name": "Sarah Johnson",
      "created_at": "2026-01-18 16:00:00"
    }
  ]
}
```

---

### Get Single Expense
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/expenses/3
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 3,
    "title": "Business Travel Expense - Client Meeting",
    "description": "<p>Expense report for client meeting in New York:</p><h3>Breakdown:</h3><ul><li>Flight tickets (round trip): $650</li><li>Hotel (2 nights @ $200/night): $400</li><li>Meals and entertainment: $150</li><li>Ground transportation (taxi/uber): $80</li></ul><p><strong>Total:</strong> $1,280.00</p><h3>Receipts:</h3><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QC8RXhpZg...\" style=\"max-width: 100%; margin: 10px 0;\"/><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QDMRXhpZg...\" style=\"max-width: 100%; margin: 10px 0;\"/><img src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QEARXhpZg...\" style=\"max-width: 100%; margin: 10px 0;\"/><h3>Meeting Details:</h3><p>Client: Acme Corporation<br/>Date: January 15-16, 2026<br/>Purpose: Q1 2026 Planning and Strategy Review</p>",
    "amount": 1280.00,
    "category": "Travel",
    "date": "2026-01-17",
    "status": "Approved",
    "employee_id": 2,
    "employee_name": "John Doe",
    "approved_by": 1,
    "approver_name": "System Administrator",
    "approved_at": "2026-01-18 10:30:00",
    "created_at": "2026-01-17 14:20:00"
  }
}
```

---

### Create Expense
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/expenses
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "title": "Conference Registration - TechSummit 2026",
  "description": "<p>Registration fee for TechSummit 2026 conference.</p><h3>Conference Details:</h3><ul><li>Event: TechSummit 2026</li><li>Date: March 15-17, 2026</li><li>Location: Las Vegas Convention Center</li><li>Registration Type: Full Conference Pass</li></ul><p>Confirmation email screenshot:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAOACAYAAAAz...\" style=\"max-width: 100%; border: 1px solid #ddd; padding: 10px;\"/><h3>Benefits:</h3><ul><li>Access to all keynote sessions</li><li>Workshop participation</li><li>Networking events</li><li>Conference materials</li></ul>",
  "metadata": {
    "amount": 1500.00,
    "category": "Training & Development",
    "date": "2026-01-18"
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Expense created successfully",
  "data": {
    "id": 25,
    "title": "Conference Registration - TechSummit 2026",
    "amount": 1500.00,
    "category": "Training & Development",
    "date": "2026-01-18",
    "status": "Submitted",
    "employee_id": 2,
    "created_at": "2026-01-18 18:15:00"
  }
}
```

---

### Delete Expense
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/expenses/25
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Expense deleted successfully",
  "data": null
}
```

---

## 7. Timesheets API

### List All Timesheets
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/timesheets
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 8,
      "title": "Timesheet - Week of Jan 8-14, 2026",
      "description": "<p>Weekly timesheet for Website Redesign project.</p><h3>Daily Breakdown:</h3><ul><li>Monday: Frontend development (8h)</li><li>Tuesday: Backend API integration (8h)</li><li>Wednesday: Code review and testing (7h)</li><li>Thursday: Team meeting and planning (8h)</li><li>Friday: Bug fixes and documentation (8h)</li></ul><p>Progress screenshot:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAOACAYAAAA0...\" style=\"max-width: 100%;\"/>",
      "week_start": "2026-01-08",
      "week_end": "2026-01-14",
      "total_hours": 39,
      "status": "Submitted",
      "employee_id": 2,
      "employee_name": "John Doe",
      "project_id": 5,
      "project_name": "Website Redesign",
      "created_at": "2026-01-15 10:00:00"
    }
  ]
}
```

---

### Get Single Timesheet
**Request:**
```bash
GET http://localhost:8080/nizaam/public/api/timesheets/8
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 8,
    "title": "Timesheet - Week of Jan 8-14, 2026",
    "description": "<p>Weekly timesheet for Website Redesign project.</p><h3>Daily Breakdown:</h3><ul><li>Monday: Frontend development - React components (8h)</li><li>Tuesday: Backend API integration - REST endpoints (8h)</li><li>Wednesday: Code review and unit testing (7h)</li><li>Thursday: Team meeting, sprint planning, documentation (8h)</li><li>Friday: Bug fixes from QA and updated docs (8h)</li></ul><p>Commit history screenshot:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAABAAAAAAOACAYAAAA0...\" style=\"max-width: 100%; border-radius: 8px;\"/><h3>Accomplishments:</h3><ul><li>✅ Completed user profile component</li><li>✅ Integrated authentication API</li><li>✅ Fixed 12 bugs from previous sprint</li><li>✅ Code coverage increased to 85%</li></ul>",
    "week_start": "2026-01-08",
    "week_end": "2026-01-14",
    "hours": {
      "monday": 8,
      "tuesday": 8,
      "wednesday": 7,
      "thursday": 8,
      "friday": 8,
      "saturday": 0,
      "sunday": 0
    },
    "total_hours": 39,
    "status": "Approved",
    "employee_id": 2,
    "employee_name": "John Doe",
    "project_id": 5,
    "project_name": "Website Redesign",
    "approved_by": 1,
    "approver_name": "System Administrator",
    "approved_at": "2026-01-16 09:30:00",
    "created_at": "2026-01-15 10:00:00"
  }
}
```

---

### Create Timesheet
**Request:**
```bash
POST http://localhost:8080/nizaam/public/api/timesheets
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
Content-Type: application/json

{
  "title": "Timesheet - Week of Jan 15-21, 2026",
  "description": "<p>Weekly timesheet for Mobile App Development project.</p><h3>Activities:</h3><ul><li>iOS app development - User authentication screens</li><li>Android app development - Dashboard implementation</li><li>API integration and testing</li><li>Team code reviews</li><li>Sprint retrospective</li></ul><p>App screenshots:</p><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA4AAAAFoCAYAAAA1...\" style=\"max-width: 48%; display: inline-block; margin: 5px;\"/><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAA4AAAAFoCAYAAAA2...\" style=\"max-width: 48%; display: inline-block; margin: 5px;\"/><h3>Key Deliverables:</h3><ul><li>Login/Register screens (iOS & Android)</li><li>Dashboard with real-time data</li><li>Push notification setup</li></ul>",
  "metadata": {
    "hours": {
      "monday": 8,
      "tuesday": 8,
      "wednesday": 8,
      "thursday": 7.5,
      "friday": 8,
      "saturday": 0,
      "sunday": 0
    },
    "project_id": 10
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "message": "Timesheet created successfully",
  "data": {
    "id": 12,
    "title": "Timesheet - Week of Jan 15-21, 2026",
    "total_hours": 39.5,
    "status": "Submitted",
    "project_id": 10,
    "project_name": "Mobile App Development",
    "created_at": "2026-01-18 18:30:00"
  }
}
```

---

### Delete Timesheet
**Request:**
```bash
DELETE http://localhost:8080/nizaam/public/api/timesheets/12
Authorization: Bearer a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890_1_1737216450
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Timesheet deleted successfully",
  "data": null
}
```

---

## Error Responses

### Unauthorized (401)
```json
{
  "error": "Authorization token required"
}
```

### Invalid Token (401)
```json
{
  "error": "Invalid or expired token"
}
```

### Bad Request (400)
```json
{
  "error": "Email and password are required"
}
```

### Not Found (404)
```json
{
  "error": "Work item not found"
}
```

### Forbidden (403)
```json
{
  "error": "Insufficient permissions"
}
```

### Server Error (500)
```json
{
  "error": "Internal server error"
}
```

---

## Notes

- All base64 image strings in examples are truncated for readability
- Real base64 strings will be much longer (typically 10KB-500KB)
- Images support formats: PNG, JPEG, GIF, WebP
- Maximum image size: 5MB (recommended)
- All timestamps are in format: `YYYY-MM-DD HH:MM:SS`
- Dates are in format: `YYYY-MM-DD`
- Bearer tokens expire after 24 hours
- Use `/api/auth/refresh` to get a new token
