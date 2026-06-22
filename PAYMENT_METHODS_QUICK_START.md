# Quick Start: Adding Payment Methods

## How to Use

### Step 1: Initialize Database

1. Visit: `http://localhost/Anything_Inside_Website/api/admin_site/order_processes/setup_payment_methods.php`
2. You should see a success message

### Step 2: Go to Orders Page

1. Log in as admin
2. Navigate to **Orders & Quotations** page

### Step 3: Add New Payment Method

1. In any order row, find the **Payment Method** dropdown column
2. Click on the dropdown
3. Select **"+ Add Payment Method"** at the bottom
4. A modal will appear

### Step 4: Fill the Form

**Payment Method Name:**

- Enter a display name, e.g., "PayPal", "Bank Transfer", "Google Pay"

**Method Identifier:**

- Use lowercase letters and underscores only
- Examples: `paypal`, `bank_transfer`, `google_pay`
- Auto-converts to lowercase as you type

**Icon Class (Optional):**

- Font Awesome icon class, e.g., `fa-brands fa-paypal`
- Icon preview shown on the right
- Default: `fa-solid fa-credit-card`

### Step 5: Submit

- Click **"Add Payment Method"** button
- Modal closes on success
- New method appears in all payment dropdowns

## Example: Adding PayPal

| Field               | Value               |
| ------------------- | ------------------- |
| Payment Method Name | PayPal              |
| Method Identifier   | paypal              |
| Icon Class          | fa-brands fa-paypal |

## Common Payment Methods & Icons

| Method         | Identifier    | Icon Class                   |
| -------------- | ------------- | ---------------------------- |
| PayPal         | paypal        | fa-brands fa-paypal          |
| Bank Transfer  | bank_transfer | fa-solid fa-building-columns |
| Google Pay     | google_pay    | fa-brands fa-google          |
| Apple Pay      | apple_pay     | fa-brands fa-apple           |
| Bitcoin        | bitcoin       | fa-brands fa-bitcoin         |
| Cryptocurrency | crypto        | fa-brands fa-ethereum        |
| Check          | check         | fa-solid fa-check            |
| Wire Transfer  | wire          | fa-solid fa-arrow-right      |

## Features

✅ Professional modal with validation
✅ Real-time icon preview
✅ Auto-formatting of identifiers
✅ System payment methods cannot be deleted
✅ Responsive design
✅ Keyboard shortcuts (Escape to close)

## Troubleshooting

**Q: Modal doesn't open?**
A: Refresh the page or check browser console for errors

**Q: Icon not showing?**
A: Check the Font Awesome icon class at https://fontawesome.com/icons

**Q: Payment method doesn't appear?**
A: Refresh the page or check database was initialized

## Need Help?

See full documentation: [PAYMENT_METHODS_SETUP.md](PAYMENT_METHODS_SETUP.md)
