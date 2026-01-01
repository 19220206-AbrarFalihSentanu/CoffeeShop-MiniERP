# 🎨 Dark Coffee Theme - Quick Start Guide

## Setup Sudah Selesai! ✅

Custom Dark Coffee Theme sudah terintegrasi penuh di aplikasi Anda.

---

## 📌 File yang Ditambahkan/Diupdate

### New Files:

1. **`public/assets/css/coffee-theme.css`**
    - File utama untuk custom theme
    - Berisi 400+ lines CSS untuk styling komprehensif

### Updated Files:

1. **`resources/views/layouts/app.blade.php`**
    - Menambahkan link ke `coffee-theme.css`
    - Placement: Setelah demo.css untuk priority yang tepat

---

## 🎨 Palet Warna Utama

```
Espresso Dark (Primary)     → #1A1310
Mocha Brown (Secondary)    → #6D4C41
Copper/Gold (Accent)       → #C87941
Sage Green (Highlights)    → #7CB342
```

---

## 🔧 Cara Menggunakan

### 1. Menggunakan Class Utilities (Recommended)

```blade
<!-- Buttons -->
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-secondary">Secondary Button</button>
<button class="btn btn-success">Success Button</button>

<!-- Badges -->
<span class="badge bg-primary">Primary</span>
<span class="badge bg-success">Success</span>
<span class="badge bg-warning">Warning</span>
<span class="badge bg-danger">Danger</span>

<!-- Text Colors -->
<p class="text-primary">Primary text (Copper gold)</p>
<p class="text-secondary">Secondary text (Mocha)</p>
<p class="text-success">Success text (Sage green)</p>
<p class="text-muted">Muted text (Gray)</p>

<!-- Alerts -->
<div class="alert alert-primary">Primary alert</div>
<div class="alert alert-success">Success alert</div>
<div class="alert alert-warning">Warning alert</div>
<div class="alert alert-danger">Danger alert</div>
```

### 2. Menggunakan CSS Variables

```blade
<div style="color: var(--coffee-accent-dark);">
  Menggunakan Copper Gold
</div>

<button style="background-color: var(--coffee-sage-green);">
  Menggunakan Sage Green
</button>

<div style="border: 2px solid var(--coffee-secondary-dark);">
  Border Mocha Brown
</div>
```

### 3. Kombinasi Class + Custom Style

```blade
<div class="card" style="border-color: var(--coffee-sage-green);">
  <div class="card-header">
    <h5 class="card-title">Custom Styled Card</h5>
  </div>
  <div class="card-body">
    Custom content dengan warna sage green border
  </div>
</div>
```

---

## 📊 Color Reference Table

| Usage           | Variable                 | Color    | Hex Code |
| --------------- | ------------------------ | -------- | -------- |
| Primary Dark    | --coffee-primary-dark    | Espresso | #1A1310  |
| Primary Light   | --coffee-primary-light   | -        | #2C1810  |
| Secondary Dark  | --coffee-secondary-dark  | Mocha    | #6D4C41  |
| Secondary Light | --coffee-secondary-light | -        | #8D6E63  |
| Accent Dark     | --coffee-accent-dark     | Copper   | #C87941  |
| Accent Light    | --coffee-accent-light    | Gold     | #D4A574  |
| Sage Green      | --coffee-sage-green      | Green    | #7CB342  |
| Light Gray      | --coffee-light-gray      | White    | #F5F5F5  |
| Medium Gray     | --coffee-medium-gray     | Gray     | #D9D9D9  |
| Dark Gray       | --coffee-dark-gray       | -        | #424242  |

---

## 🎯 Components Styling Examples

### Dashboard Cards

```blade
<div class="card">
  <div class="card-body">
    <div class="avatar flex-shrink-0 mb-3">
      <i class="bx bx-error bx-md text-warning"></i>
    </div>
    <span class="fw-semibold d-block mb-1">Low Stock Items</span>
    <h3 class="card-title mb-2">{{ $count }}</h3>
    <a href="{{ route('admin.inventory.alerts') }}" class="text-warning small">
      <i class="bx bx-right-arrow-alt"></i> View Details
    </a>
  </div>
</div>
```

### Form Input

```blade
<div class="mb-3">
  <label class="form-label" for="product_name">
    Product Name
  </label>
  <input
    type="text"
    class="form-control"
    id="product_name"
    placeholder="Enter product name"
  />
</div>
```

### Data Table

```blade
<table class="table table-hover">
  <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Arabica Gayo</td>
      <td>Rp 85.000</td>
      <td>50 units</td>
      <td><span class="badge bg-success">Active</span></td>
    </tr>
  </tbody>
</table>
```

### Alert Messages

```blade
<!-- Success Alert -->
<div class="alert alert-success">
  <i class="bx bx-check-circle"></i>
  Operation completed successfully!
</div>

<!-- Warning Alert -->
<div class="alert alert-warning">
  <i class="bx bx-error"></i>
  Low stock warning for some products
</div>

<!-- Danger Alert -->
<div class="alert alert-danger">
  <i class="bx bx-x-circle"></i>
  Error: Action could not be completed
</div>
```

---

## 🎨 Customizing Theme

### Option 1: Edit CSS Variables (Recommended for Major Changes)

File: `public/assets/css/coffee-theme.css`

```css
:root {
    /* Change primary color */
    --coffee-primary-dark: #1a1310; /* Change this */

    /* Change accent color */
    --coffee-accent-dark: #c87941; /* Change this */

    /* Change secondary color */
    --coffee-secondary-dark: #6d4c41; /* Change this */
}
```

### Option 2: Add Custom Component Class

Append ke `coffee-theme.css`:

```css
/* Custom component style */
.card-custom {
    background: linear-gradient(
        135deg,
        var(--coffee-primary-light) 0%,
        var(--coffee-secondary-dark) 100%
    );
    border: 2px solid var(--coffee-accent-dark);
    border-radius: 12px;
}

.btn-custom {
    background-color: var(--coffee-sage-green);
    border: none;
}

.badge-custom {
    background-color: var(--coffee-accent-dark) !important;
    color: white;
}
```

### Option 3: Use with @stack('styles') in Blade

```blade
@push('styles')
<style>
  .my-custom-element {
    color: var(--coffee-accent-dark);
    border: 1px solid var(--coffee-secondary-dark);
  }
</style>
@endpush
```

---

## 📱 Responsive Breakpoints

Theme fully responsive untuk:

-   **Desktop**: 1200px+
-   **Tablet**: 768px - 1199px
-   **Mobile**: < 768px

---

## 🔍 Quality Checklist

✅ All buttons styled and hover effects
✅ All form inputs dark mode ready
✅ Tables with proper contrast
✅ Alerts with semantic colors
✅ Badge variations
✅ Icon colors matched
✅ Link colors consistent
✅ Scrollbar themed
✅ Modal dialogs styled
✅ Dropdown menus themed
✅ Pagination styled
✅ Responsive on mobile

---

## 🐛 Troubleshooting

### Colors not changing?

1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard reload page (Ctrl+Shift+R)
3. Check if coffee-theme.css is loaded in DevTools

### Styling conflicts?

1. Ensure coffee-theme.css loads AFTER theme-default.css
2. Check browser console for CSS errors
3. Use !important if needed for specific overrides

### Mobile view looks weird?

1. Check responsive breakpoints in coffee-theme.css
2. Test on actual device, not just browser dev tools
3. Clear mobile browser cache

---

## 💡 Tips & Tricks

1. **Reuse Variables**: Always use `var(--coffee-*)` for consistency
2. **Hover Effects**: Add subtle transitions for better UX
3. **Contrast**: Maintain readable text on dark backgrounds
4. **Print Styles**: Test print preview for proper colors
5. **Accessibility**: Use WCAG AA color contrasts

---

## 📚 Resources

-   **CSS Variables Reference**: Line 1-14 in coffee-theme.css
-   **Component Examples**: Sections starting with "NAVBAR/HEADER", "CARDS", etc.
-   **Bootstrap Classes**: All standard Bootstrap classes work with theme
-   **Icons**: Using Boxicons (https://boxicons.com/)

---

## 🎓 Next Steps

1. **Test**: Open all pages and verify theme
2. **Customize**: Edit colors in CSS variables if needed
3. **Extend**: Add more custom classes as needed
4. **Deploy**: Push to production when satisfied

---

**Questions?** Check THEME_DOCUMENTATION.md for detailed information.

Happy coding! ☕

---

**Last Updated:** 27 Desember 2025
**Version:** 1.0 - Initial Release
