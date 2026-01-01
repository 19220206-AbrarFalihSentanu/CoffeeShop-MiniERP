# 🎨 DARK COFFEE THEME - Dokumentasi Lengkap

## Pendahuluan

Website eUREKA Coffee ERP kini menggunakan **Dark Coffee Theme** - tema warna modern yang dirancang khusus untuk bisnis kopi dengan palet warna profesional dan elegan.

---

## 📋 Palet Warna

### Primary Colors - Espresso Dark

```
#1A1310 - Espresso Primary Dark
#2C1810 - Espresso Primary Light (untuk variants)
#0F0A08 - Espresso Primary Darker (untuk accents)
```

**Penggunaan:** Background utama, header, sidebar, input fields

### Secondary Colors - Mocha Brown

```
#6D4C41 - Mocha Brown Dark
#8D6E63 - Mocha Brown Light
```

**Penggunaan:** Hover states, borders, text secondary, disabled states

### Accent Colors - Copper/Gold

```
#C87941 - Copper/Gold Dark (primary accent)
#D4A574 - Copper/Gold Light (hover/secondary accent)
```

**Penggunaan:** Buttons, links, active states, highlights, icons

### Highlights - Sage Green

```
#7CB342 - Sage Green
#9CCC65 - Sage Green Light (hover)
```

**Penggunaan:** Success states, sustainability indicators, positive actions

### Neutral Colors

```
#FFFFFF - White
#F5F5F5 - Light Gray (text)
#D9D9D9 - Medium Gray
#424242 - Dark Gray
```

---

## 🎯 Komponen yang Di-customize

### 1. **Layout & Background**

-   Body background: Espresso dark (#1A1310)
-   Sidebar: Gradient dari dark ke light espresso
-   Cards: Dark gradient dengan border mocha

### 2. **Navigation**

-   Navbar: Gradient dengan accent border
-   Sidebar menu: Dark theme dengan hover mocha
-   Active menu: Copper gold gradient
-   Menu icons: Copper gold color

### 3. **Buttons**

```
- Primary: Copper gold (#C87941)
- Secondary: Mocha brown (#6D4C41)
- Success: Sage green (#7CB342)
- Warning: Copper gold (#C87941)
- Danger: Red (#E53935)
- Info: Mocha light (#8D6E63)
```

### 4. **Forms & Inputs**

-   Background: Dark (#3A2F28)
-   Border: Mocha brown
-   Focus border: Copper gold
-   Text color: Light gray
-   Labels: Copper gold

### 5. **Badges & Status Indicators**

-   Solid badges: Colored backgrounds
-   Label badges: Semi-transparent dengan matching colors
-   Success: Sage green
-   Warning: Copper gold
-   Danger: Red

### 6. **Cards**

-   Background: Dark gradient
-   Border: Mocha brown
-   Hover: Copper gold border dengan shadow
-   Header: Dark gradient dengan copper accent border
-   Title: Copper gold text

### 7. **Tables**

-   Background: Dark
-   Header: Gradient dark to mocha
-   Hover rows: Mocha brown
-   Borders: Mocha brown
-   Text: Light gray

### 8. **Alerts**

-   Primary/Warning: Copper gold theme
-   Success: Sage green theme
-   Danger: Red theme
-   Info: Mocha theme

### 9. **Links & Text**

-   Links: Copper gold (#D4A574)
-   Link hover: Copper dark (#C87941)
-   Primary text: Copper gold
-   Secondary text: Mocha light
-   Muted text: Medium gray

---

## 📁 File Structure

```
public/assets/css/
├── coffee-theme.css          ← Custom Dark Coffee Theme
└── demo.css

resources/views/layouts/
├── app.blade.php             ← Updated dengan include coffee-theme.css
└── partials/
    └── sidebar.blade.php
```

---

## 🔧 Cara Mengganti Warna (Customization)

### Option 1: Edit CSS Variables

Edit `coffee-theme.css` pada bagian `:root`:

```css
:root {
    --coffee-primary-dark: #1a1310; /* Ubah di sini */
    --coffee-accent-dark: #c87941; /* Ubah di sini */
    /* ... */
}
```

Semua komponen akan otomatis mengikuti perubahan.

### Option 2: Add Custom Utilities

Tambahkan di akhir `coffee-theme.css`:

```css
/* Custom color untuk card tertentu */
.card-special {
    background: linear-gradient(135deg, #2c1810 0%, #3a2f28 100%);
    border-color: var(--coffee-sage-green);
}

/* Custom button style */
.btn-coffee {
    background-color: var(--coffee-accent-dark);
    color: white;
}
```

### Option 3: Override dengan Inline Style (untuk testing)

```blade
<div class="card" style="--coffee-primary-dark: #1A1310;">
  ...
</div>
```

---

## 🎨 Penggunaan di Blade Templates

### Menggunakan Predefined Classes

```blade
<!-- Primary button (Copper gold) -->
<button class="btn btn-primary">Click me</button>

<!-- Success badge (Sage green) -->
<span class="badge bg-success">Active</span>

<!-- Warning alert (Copper gold) -->
<div class="alert alert-warning">Warning message</div>

<!-- Primary text (Copper gold) -->
<p class="text-primary">Important text</p>

<!-- Secondary text (Mocha) -->
<p class="text-secondary">Secondary info</p>
```

### Menggunakan CSS Variables

```blade
<!-- Custom styling dengan CSS variables -->
<div style="color: var(--coffee-accent-dark); border: 1px solid var(--coffee-secondary-dark);">
  Custom styled content
</div>
```

---

## 🎯 Color Reference Chart

| Element    | Light Mode | Dark Mode   | Hex Code |
| ---------- | ---------- | ----------- | -------- |
| Primary    | Blue       | Copper Gold | #C87941  |
| Secondary  | Gray       | Mocha Brown | #6D4C41  |
| Success    | Green      | Sage Green  | #7CB342  |
| Warning    | Orange     | Copper Gold | #C87941  |
| Danger     | Red        | Red         | #E53935  |
| Background | White      | Espresso    | #1A1310  |
| Text       | Dark       | Light Gray  | #F5F5F5  |
| Borders    | Light Gray | Mocha       | #6D4C41  |

---

## 🖼️ Component Preview

### Sidebar

-   Background: Dark espresso gradient
-   Active menu: Copper gold gradient
-   Hover: Mocha brown
-   Icons: Copper gold
-   Headers: Copper gold text

### Cards

-   Background: Dark gradient (#2C2218 → #3A2F28)
-   Border: Mocha brown
-   Header: Gradient dengan copper accent border
-   Title: Copper gold
-   Hover: Mocha border dengan copper shadow

### Buttons

-   Primary: Solid copper gold
-   Hover: Lighter copper with lift effect
-   Focus: Copper shadow ring

### Tables

-   Headers: Dark gradient + copper border
-   Rows: Alternating opacity
-   Hover: Mocha background

### Forms

-   Input background: Dark (#3A2F28)
-   Border: Mocha brown
-   Focus: Copper border + shadow
-   Labels: Copper gold

---

## 🔄 Browser Support

✅ Chrome 90+
✅ Firefox 88+
✅ Safari 14+
✅ Edge 90+

---

## 📱 Responsive Design

Semua komponen telah di-optimize untuk:

-   Desktop (1200px+)
-   Tablet (768px - 1199px)
-   Mobile (< 768px)

Custom breakpoint untuk responsive adjustments tersedia di bagian `@media` CSS.

---

## 🚀 Tips Optimasi

1. **Performance**: CSS theme diprioritaskan setelah core CSS untuk loading yang cepat
2. **Maintainability**: Gunakan CSS variables untuk consistency
3. **Accessibility**: Contrast ratio memenuhi WCAG AA standards
4. **Print**: Tema dark dioptimasi untuk print preview

---

## 📞 Support & Customization

Jika ingin melakukan customization lebih lanjut:

1. Edit `coffee-theme.css` secara langsung
2. Tambahkan CSS custom di `@stack('styles')` pada blade template
3. Gunakan utility classes yang sudah tersedia

---

## 📌 Catatan Penting

-   Theme CSS sudah di-include di `layouts/app.blade.php`
-   Tidak perlu include manual di setiap halaman
-   CSS variables dapat di-override per-element
-   Theme fully responsive untuk semua devices

---

**Last Updated:** 27 Desember 2025
**Version:** 1.0
**Theme:** Dark Coffee Modern Theme
