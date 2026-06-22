# Payment Methods Management Feature - Installation & Setup Guide

## Overview

This feature allows admins to add custom payment methods to the Orders page. Admins can:

- Add new payment methods via a professional modal
- See custom payment methods in the payment method dropdown
- Delete custom payment methods (system methods like Cash, GCash, Card cannot be deleted)

## Installation Steps

### Step 1: Database Setup

Run the database migration to create the `payment_methods` table:

**Option A: Automated Setup (Recommended)**

1. Open your browser and navigate to:
   ```
   http://localhost/Anything_Inside_Website/api/admin_site/order_processes/setup_payment_methods.php
   ```
2. You should see a JSON response confirming the table was created
3. If the table already exists, you'll see a success message

**Option B: Manual SQL Execution**

1. Open phpMyAdmin
2. Select your database `anything_inside_db`
3. Click on "SQL" tab
4. Copy and paste the contents of `/connect/migrations/001_create_payment_methods_table.sql`
5. Click "Execute"

### Step 2: Verify Installation

1. Go to Admin Dashboard > Orders page
2. Look for a payment method dropdown in any order row
3. You should see: "Cash on Delivery", "GCash", "Card", and "+ Add Payment Method" option

### Step 3: Test the Feature

1. Click on the "+ Add Payment Method" option in the dropdown
2. A professional modal should appear
3. Fill in the form:
   - **Payment Method Name**: e.g., "PayPal"
   - **Method Identifier**: e.g., "paypal" (auto-lowercase)
   - **Icon Class**: e.g., "fa-brands fa-paypal" (Font Awesome)
4. Click "Add Payment Method"
5. The new method should now appear in all payment method dropdowns

## File Structure

### New Files Created:

```
/api/admin_site/order_processes/
├── payment_methods.php              (API for CRUD operations)
├── setup_payment_methods.php        (Database initialization)

/assets/js/admin-site-functions/
├── payment_methods.js               (Frontend logic for modal & dropdowns)

/connect/migrations/
├── 001_create_payment_methods_table.sql  (Database schema)
```

### Modified Files:

```
/admin/pages/
├── Orders.php                       (Added modal HTML)

/assets/css/admin-site/
├── orders_styles.css                (Added modal styling)

/assets/js/admin-site-functions/admin_data_fetch/
├── orders.js                        (Updated dropdown logic)
```

## API Endpoints

### Get All Payment Methods

```
GET /api/admin_site/order_processes/payment_methods.php
Response: { success: true, data: [...payment_methods] }
```

### Add Payment Method

```
POST /api/admin_site/order_processes/payment_methods.php
Body: {
  "method_name": "PayPal",
  "method_value": "paypal",
  "icon_class": "fa-brands fa-paypal"
}
```

### Update Payment Method

```
PUT /api/admin_site/order_processes/payment_methods.php
Body: {
  "id": 4,
  "method_name": "PayPal Updated",
  "is_active": 1,
  "icon_class": "fa-brands fa-paypal"
}
```

### Delete Payment Method

```
DELETE /api/admin_site/order_processes/payment_methods.php?id=4
```

## Database Schema

### payment_methods Table

```sql
CREATE TABLE `payment_methods` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `method_name` varchar(100) NOT NULL,
  `method_value` varchar(50) NOT NULL UNIQUE,
  `icon_class` varchar(100) DEFAULT 'fa-solid fa-credit-card',
  `sort_order` int(11) DEFAULT 999,
  `is_system` tinyint(1) DEFAULT 0,     -- 1 if cannot be deleted
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp,
  `updated_at` timestamp
);
```

## Font Awesome Icons

Common icons for payment methods:

- Cash: `fa-solid fa-money-bill-wave`
- Credit Card: `fa-regular fa-credit-card`
- Mobile: `fa-solid fa-mobile-alt`
- Wallet: `fa-solid fa-wallet`
- PayPal: `fa-brands fa-paypal`
- Bitcoin: `fa-brands fa-bitcoin`
- Bank: `fa-solid fa-building-columns`

For more icons, visit: https://fontawesome.com/icons

## Features

### Modal UI/UX

- Professional, clean design matching admin dashboard
- Real-time icon preview
- Auto-formatting of method identifier (lowercase, underscores only)
- Form validation with helpful error messages
- Keyboard support (Escape to close, Enter to submit)
- Loading states and animations

### Security & Validation

- SQL injection prevention (prepared statements)
- XSS protection via escapeHtml() function
- Validation for empty fields
- Prevents duplicate payment methods
- Cannot delete system payment methods
- Input sanitization

### Performance

- Caching of payment methods in memory
- Efficient database queries
- Minimal API calls
- Smooth animations and transitions

## Troubleshooting

### Table not created?

1. Verify MySQL is running
2. Check database connection in `/connect/config.php`
3. Run setup file again: `/api/admin_site/order_processes/setup_payment_methods.php`

### Modal doesn't appear?

1. Check browser console for errors
2. Verify `payment_methods.js` is loaded (check Network tab in DevTools)
3. Ensure both `orders.js` and `payment_methods.js` are included in Orders.php

### New payment method doesn't show in dropdown?

1. Check API response in Network tab
2. Verify payment method was inserted in database
3. Refresh the page

### Icon not showing?

1. Verify Font Awesome CSS is loaded
2. Check icon class is correct (visit fontawesome.com)
3. Common mistake: missing "fa-solid" or "fa-brands" prefix

## Future Enhancements

Potential improvements:

- Edit existing payment methods
- Reorder payment methods via drag-and-drop
- Payment method categories/groups
- Configure payment method acceptance rules
- Payment method status history
- Bulk import/export payment methods

## Support

For issues or questions:

1. Check the console for error messages
2. Verify all files were created correctly
3. Ensure database migration was successful
4. Review API responses using browser DevTools

---

**Last Updated:** 2026-06-08
**Version:** 1.0
