# Payment Methods API Reference

## Base URL

```
/api/admin_site/order_processes/payment_methods.php
```

## Endpoints

### 1. Get All Payment Methods

**Method**: `GET`

**URL**:

```
/api/admin_site/order_processes/payment_methods.php
```

**Response** (Success - 200):

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "method_name": "Cash on Delivery",
      "method_value": "cash",
      "icon_class": "fa-solid fa-money-bill-wave",
      "sort_order": 1,
      "is_system": 1,
      "is_active": 1,
      "created_at": "2026-06-08 10:00:00",
      "updated_at": "2026-06-08 10:00:00"
    },
    {
      "id": 2,
      "method_name": "GCash",
      "method_value": "gcash",
      "icon_class": "fa-solid fa-mobile-alt",
      "sort_order": 2,
      "is_system": 1,
      "is_active": 1,
      "created_at": "2026-06-08 10:00:00",
      "updated_at": "2026-06-08 10:00:00"
    },
    {
      "id": 4,
      "method_name": "PayPal",
      "method_value": "paypal",
      "icon_class": "fa-brands fa-paypal",
      "sort_order": 999,
      "is_system": 0,
      "is_active": 1,
      "created_at": "2026-06-08 11:30:45",
      "updated_at": "2026-06-08 11:30:45"
    }
  ]
}
```

---

### 2. Add New Payment Method

**Method**: `POST`

**URL**:

```
/api/admin_site/order_processes/payment_methods.php
```

**Content-Type**: `application/json`

**Request Body**:

```json
{
  "method_name": "PayPal",
  "method_value": "paypal",
  "icon_class": "fa-brands fa-paypal"
}
```

**Required Fields**:

- `method_name` (string, 1-100 chars)
- `method_value` (string, 1-50 chars, lowercase + underscores only)

**Optional Fields**:

- `icon_class` (string, default: "fa-solid fa-credit-card")

**Response** (Success - 200):

```json
{
  "success": true,
  "message": "Payment method added successfully",
  "data": {
    "id": 4,
    "method_name": "PayPal",
    "method_value": "paypal",
    "icon_class": "fa-brands fa-paypal",
    "sort_order": 999,
    "is_active": 1
  }
}
```

**Response** (Error - 400):

```json
{
  "success": false,
  "message": "Missing required fields: method_name and method_value"
}
```

**Response** (Error - 409):

```json
{
  "success": false,
  "message": "Payment method already exists"
}
```

---

### 3. Update Payment Method

**Method**: `PUT`

**URL**:

```
/api/admin_site/order_processes/payment_methods.php
```

**Content-Type**: `application/json`

**Request Body**:

```json
{
  "id": 4,
  "method_name": "PayPal Express",
  "is_active": 1,
  "icon_class": "fa-brands fa-paypal"
}
```

**Required Fields**:

- `id` (integer)

**Optional Fields** (at least one required):

- `method_name` (string)
- `is_active` (integer: 0 or 1)
- `icon_class` (string)

**Response** (Success - 200):

```json
{
  "success": true,
  "message": "Payment method updated successfully"
}
```

**Response** (Error - 404):

```json
{
  "success": false,
  "message": "Payment method not found"
}
```

---

### 4. Delete Payment Method

**Method**: `DELETE`

**URL**:

```
/api/admin_site/order_processes/payment_methods.php?id=4
```

**Response** (Success - 200):

```json
{
  "success": true,
  "message": "Payment method deleted successfully"
}
```

**Response** (Error - 403):

```json
{
  "success": false,
  "message": "Cannot delete system payment methods"
}
```

**Response** (Error - 404):

```json
{
  "success": false,
  "message": "Payment method not found"
}
```

---

## HTTP Status Codes

| Code | Meaning            | Scenario               |
| ---- | ------------------ | ---------------------- |
| 200  | OK                 | Successful request     |
| 400  | Bad Request        | Missing/invalid fields |
| 404  | Not Found          | Resource doesn't exist |
| 405  | Method Not Allowed | Wrong HTTP method      |
| 409  | Conflict           | Duplicate entry        |
| 500  | Server Error       | Database/server error  |

---

## JavaScript Usage Examples

### Get All Payment Methods

```javascript
fetch("/api/admin_site/order_processes/payment_methods.php")
  .then((res) => res.json())
  .then((data) => {
    if (data.success) {
      console.log("Payment methods:", data.data);
    }
  });
```

### Add New Payment Method

```javascript
fetch("/api/admin_site/order_processes/payment_methods.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify({
    method_name: "PayPal",
    method_value: "paypal",
    icon_class: "fa-brands fa-paypal",
  }),
})
  .then((res) => res.json())
  .then((data) => {
    if (data.success) {
      console.log("Added:", data.data);
    } else {
      console.error("Error:", data.message);
    }
  });
```

### Update Payment Method

```javascript
fetch("/api/admin_site/order_processes/payment_methods.php", {
  method: "PUT",
  headers: {
    "Content-Type": "application/json",
  },
  body: JSON.stringify({
    id: 4,
    is_active: 0,
  }),
})
  .then((res) => res.json())
  .then((data) => {
    if (data.success) {
      console.log("Updated successfully");
    }
  });
```

### Delete Payment Method

```javascript
const methodId = 4;
fetch(`/api/admin_site/order_processes/payment_methods.php?id=${methodId}`, {
  method: "DELETE",
})
  .then((res) => res.json())
  .then((data) => {
    if (data.success) {
      console.log("Deleted successfully");
    } else {
      console.error("Error:", data.message);
    }
  });
```

---

## Validation Rules

### method_name

- **Type**: String
- **Required**: Yes
- **Length**: 1-100 characters
- **Rules**: Non-empty, trimmed

### method_value

- **Type**: String
- **Required**: Yes
- **Length**: 1-50 characters
- **Rules**:
  - Lowercase letters and underscores only
  - No spaces
  - Must be unique
  - Examples: `paypal`, `bank_transfer`, `google_pay`

### icon_class

- **Type**: String
- **Required**: No
- **Default**: "fa-solid fa-credit-card"
- **Rules**: Font Awesome class format
- **Examples**:
  - `fa-brands fa-paypal`
  - `fa-solid fa-building-columns`
  - `fa-solid fa-wallet`

### is_active

- **Type**: Integer (0 or 1)
- **Default**: 1
- **Rules**: Boolean as integer

### sort_order

- **Type**: Integer
- **Default**: 999
- **Rules**: Auto-generated on create

### is_system

- **Type**: Integer (0 or 1)
- **Default**: 0
- **Rules**:
  - Read-only, set only on create
  - System methods (is_system=1) cannot be deleted

---

## Error Handling

### Common Error Scenarios

**1. Empty method_name**

```json
{
  "success": false,
  "message": "Missing required fields: method_name and method_value"
}
```

**2. Empty method_value**

```json
{
  "success": false,
  "message": "Missing required fields: method_name and method_value"
}
```

**3. Invalid method_value format**

```json
{
  "success": false,
  "message": "Method name and value cannot be empty"
}
```

**4. Duplicate method_value**

```json
{
  "success": false,
  "message": "Payment method already exists"
}
```

**5. Try to delete system method**

```json
{
  "success": false,
  "message": "Cannot delete system payment methods"
}
```

**6. Database error**

```json
{
  "success": false,
  "message": "Error adding payment method: [error details]"
}
```

---

## Rate Limiting

Currently no rate limiting implemented. Consider adding:

- Per-IP limits
- Per-user limits
- Global limits

---

## Authentication

**Note**: These endpoints assume user is already authenticated as admin (checked in Orders page).

For production, consider adding:

- JWT token validation
- Session verification
- Role-based access control (RBAC)

---

## CORS

API is CORS-enabled for same-origin requests (same domain).

---

## Data Types

### Database Field Types

```sql
id                INT(11)
method_name       VARCHAR(100)
method_value      VARCHAR(50)
icon_class        VARCHAR(100)
sort_order        INT(11)
is_system         TINYINT(1)
is_active         TINYINT(1)
created_at        TIMESTAMP
updated_at        TIMESTAMP
```

---

## Best Practices

1. **Always validate** method_value format before sending
2. **Use try-catch** for API calls
3. **Provide feedback** to users with toast notifications
4. **Cache results** on client-side when possible
5. **Handle errors** gracefully
6. **Log API calls** for debugging
7. **Use meaningful** method names and icons

---

## Integration Example

```javascript
class PaymentMethodsAPI {
  static baseURL = "/api/admin_site/order_processes/payment_methods.php";

  static async getAll() {
    const res = await fetch(this.baseURL);
    return await res.json();
  }

  static async add(methodName, methodValue, iconClass) {
    const res = await fetch(this.baseURL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        method_name: methodName,
        method_value: methodValue,
        icon_class: iconClass,
      }),
    });
    return await res.json();
  }

  static async update(id, updates) {
    const res = await fetch(this.baseURL, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, ...updates }),
    });
    return await res.json();
  }

  static async delete(id) {
    const res = await fetch(`${this.baseURL}?id=${id}`, {
      method: "DELETE",
    });
    return await res.json();
  }
}

// Usage:
// const methods = await PaymentMethodsAPI.getAll();
// const result = await PaymentMethodsAPI.add('PayPal', 'paypal', 'fa-brands fa-paypal');
```

---

**Last Updated**: June 8, 2026
**API Version**: 1.0
**Status**: Production Ready
