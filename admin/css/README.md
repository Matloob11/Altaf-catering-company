# Altaf Catering Admin Panel - Unified CSS System

## Overview
The admin panel now uses a single, unified CSS file (`admin-unified.css`) that provides a consistent, professional design across all pages.

## What Changed
- **Before**: Multiple CSS files with inconsistent styling
- **After**: Single unified CSS file with professional design system

## Files Structure
```
admin/css/
├── admin-unified.css          # Main unified CSS file (USE THIS)
├── backup/                    # Old CSS files (backed up)
│   ├── admin-essential.css.bak
│   ├── loader.css.bak
│   └── ... (other old files)
└── README.md                  # This file
```

## How to Use in New Pages

### 1. Basic Setup (Required)
Add these 3 lines in the `<head>` section of any new admin page:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
<link href="css/admin-unified.css" rel="stylesheet">
```

### 2. Page Structure Template
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title - Altaf Catering Admin</title>
    
    <!-- Unified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Your content here -->
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## Design Features

### 1. Color System
- **Primary**: Blue gradient (#4e73df to #764ba2)
- **Success**: Green (#1cc88a)
- **Warning**: Yellow (#f6c23e)
- **Danger**: Red (#e74a3b)
- **Info**: Cyan (#36b9cc)

### 2. Components Available

#### Cards
```html
<div class="card shadow">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Card Title</h6>
    </div>
    <div class="card-body">
        Card content
    </div>
</div>
```

#### Stat Cards
```html
<div class="card border-left-primary shadow h-100 py-2 card-hover stat-card">
    <div class="card-body">
        <div class="row no-gutters align-items-center">
            <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Label</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800" data-count="25">25</div>
            </div>
            <div class="col-auto">
                <i class="fas fa-icon fa-2x text-primary"></i>
            </div>
        </div>
    </div>
</div>
```

#### Buttons
```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-success">Success Button</button>
<button class="btn btn-sm btn-info">Small Button</button>
```

#### Tables
```html
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

#### Forms
```html
<div class="mb-3">
    <label class="form-label">Field Label</label>
    <input type="text" class="form-control" name="field">
</div>
```

#### Alerts
```html
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle me-2"></i>
    Success message
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
```

### 3. Utility Classes
- `.glow-on-hover` - Adds glow effect on hover
- `.card-hover` - Enhanced hover effect for cards
- `.gradient-text` - Gradient text effect
- `.pulse-animation` - Pulsing animation

### 4. Page Header Template
```html
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 gradient-text">
        <i class="fas fa-icon me-2"></i> Page Title
    </h1>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Add New
        </button>
    </div>
</div>
```

## Benefits

### 1. Consistency
- All pages now have the same professional look
- Consistent spacing, colors, and typography
- Unified component styling

### 2. Maintainability
- Single CSS file to maintain
- Easy to make global changes
- No more conflicting styles

### 3. Performance
- Reduced CSS file size
- Faster loading times
- Better caching

### 4. Responsive Design
- Mobile-first approach
- Tablet and desktop optimized
- Touch-friendly interface

## Migration Guide

### For Existing Pages
1. Replace all CSS links with the 3 unified CSS links
2. Remove any inline styles that conflict
3. Test the page to ensure proper styling

### For New Pages
1. Use the template file (`template-new-page.php`) as a starting point
2. Follow the component examples above
3. Maintain the consistent structure

## Customization

If you need to add custom styles:
1. Add them to the end of `admin-unified.css`
2. Use CSS custom properties (variables) when possible
3. Follow the existing naming conventions

## Support

The unified CSS system includes:
- ✅ All Bootstrap 5 components
- ✅ FontAwesome icons
- ✅ Custom admin components
- ✅ Responsive design
- ✅ Dark mode support (optional)
- ✅ Print styles
- ✅ Accessibility features
- ✅ Performance optimizations

## File Backup

All old CSS files have been moved to the `backup/` folder with `.bak` extension for safety. You can delete them after confirming everything works properly.

---

**Note**: Always use the unified CSS system for new pages to maintain consistency across the admin panel.