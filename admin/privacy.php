<?php
// Include authentication check
require_once 'includes/auth-check.php';

// Load privacy data
$privacy_file = 'data/privacy.json';
$privacy = file_exists($privacy_file) ? json_decode(file_get_contents($privacy_file), true) : [
    'page_title' => 'Privacy Policy',
    'page_subtitle' => 'Please read how we handle and protect your information',
    'last_updated' => 'November 2025',
    'intro_text' => '',
    'sections' => []
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_privacy'])) {
        $sections = [];
        if (isset($_POST['section_id'])) {
            foreach ($_POST['section_id'] as $index => $id) {
                $sections[] = [
                    'id' => $id,
                    'icon' => $_POST['section_icon'][$index],
                    'title' => $_POST['section_title'][$index],
                    'content' => $_POST['section_content'][$index]
                ];
            }
        }
        
        $privacy = [
            'page_title' => $_POST['page_title'],
            'page_subtitle' => $_POST['page_subtitle'],
            'last_updated' => $_POST['last_updated'],
            'intro_text' => $_POST['intro_text'],
            'sections' => $sections
        ];
        
        file_put_contents($privacy_file, json_encode($privacy, JSON_PRETTY_PRINT));
        $success = "Privacy Policy updated successfully!";
    }
    
    if (isset($_POST['delete_section'])) {
        $delete_index = (int)$_POST['delete_section'];
        array_splice($privacy['sections'], $delete_index, 1);
        file_put_contents($privacy_file, json_encode($privacy, JSON_PRETTY_PRINT));
        $success = "Section deleted successfully!";
        $privacy = json_decode(file_get_contents($privacy_file), true);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy Management - Altaf Catering Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"><style>
        .section-card {
            border-left: 4px solid #198754;
            margin-bottom: 1rem;
        }
        .section-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
                    <h1 class="h2 gradient-text">
                        <i class="fas fa-user-shield me-2"></i> Privacy Policy Management
                    </h1>
                    <div class="d-flex gap-2">
                        <a href="../privacy.php" target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i> Preview Page
                        </a>
                        <button class="btn btn-sm btn-success" onclick="addNewSection()">
                            <i class="fas fa-plus me-1"></i> Add Section
                        </button>
                    </div>
                </div>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="privacyForm">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Page Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" name="page_title" class="form-control" value="<?php echo htmlspecialchars($privacy['page_title']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Updated</label>
                                    <input type="text" name="last_updated" class="form-control" value="<?php echo htmlspecialchars($privacy['last_updated']); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Page Subtitle</label>
                                <input type="text" name="page_subtitle" class="form-control" value="<?php echo htmlspecialchars($privacy['page_subtitle']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Introduction Text</label>
                                <textarea name="intro_text" class="form-control" rows="3"><?php echo htmlspecialchars($privacy['intro_text']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Privacy Policy Sections</h5>
                            <small class="text-muted"><?php echo count($privacy['sections']); ?> sections</small>
                        </div>
                        <div class="card-body">
                            <div id="sectionsContainer">
                                <?php foreach ($privacy['sections'] as $index => $section): ?>
                                <div class="card section-card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="mb-0">
                                                <i class="<?php echo $section['icon']; ?> me-2"></i>
                                                Section <?php echo $index + 1; ?>
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteSection(<?php echo $index; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Section ID</label>
                                                <input type="text" name="section_id[]" class="form-control" value="<?php echo htmlspecialchars($section['id']); ?>" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Icon Class</label>
                                                <input type="text" name="section_icon[]" class="form-control" value="<?php echo htmlspecialchars($section['icon']); ?>" required>
                                                <small class="text-muted">e.g., fas fa-shield-alt</small>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Section Title</label>
                                                <input type="text" name="section_title[]" class="form-control" value="<?php echo htmlspecialchars($section['title']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-0">
                                            <label class="form-label">Content</label>
                                            <textarea name="section_content[]" class="form-control" rows="4" required><?php echo htmlspecialchars($section['content']); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <button type="submit" name="save_privacy" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Save All Changes
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin-enhancements.js"></script>
    <script>
        function addNewSection() {
            const container = document.getElementById('sectionsContainer');
            const sectionCount = container.children.length + 1;
            
            const newSection = document.createElement('div');
            newSection.className = 'card section-card mb-3';
            newSection.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            New Section ${sectionCount}
                        </h6>
                        <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.section-card').remove()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section ID</label>
                            <input type="text" name="section_id[]" class="form-control" value="new_section_${sectionCount}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Icon Class</label>
                            <input type="text" name="section_icon[]" class="form-control" value="fas fa-file-alt" required>
                            <small class="text-muted">e.g., fas fa-shield-alt</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" name="section_title[]" class="form-control" value="New Section Title" required>
                        </div>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label">Content</label>
                        <textarea name="section_content[]" class="form-control" rows="4" required>Enter section content here...</textarea>
                    </div>
                </div>
            `;
            
            container.appendChild(newSection);
            newSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        function deleteSection(index) {
            if (confirm('Are you sure you want to delete this section?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `<input type="hidden" name="delete_section" value="${index}">`;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
