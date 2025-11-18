<?php
// Include authentication check
require_once 'includes/auth-check.php';

$results = [];
$query = '';

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    
    // Search in all data files
    $files = ['blogs', 'contacts', 'bookings', 'applications', 'team', 'testimonials', 'menu', 'jobs'];
    
    foreach ($files as $file) {
        $path = "data/{$file}.json";
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            foreach ($data as $item) {
                $match = false;
                foreach ($item as $value) {
                    if (is_string($value) && stripos($value, $query) !== false) {
                        $match = true;
                        break;
                    }
                }
                if ($match) {
                    $results[] = [
                        'type' => $file,
                        'data' => $item
                    ];
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="css/admin-unified.css" rel="stylesheet"></head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-4">
                    <h1 class="h2"><i class="fas fa-search me-2"></i> Global Search</h1>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="GET" class="mb-4">
                            <div class="input-group input-group-lg">
                                <input type="text" name="q" class="form-control" placeholder="Search across all data..." value="<?php echo htmlspecialchars($query); ?>" autofocus>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </form>

                        <?php if ($query): ?>
                            <h5>Found <?php echo count($results); ?> results for "<?php echo htmlspecialchars($query); ?>"</h5>
                            <hr>
                            
                            <?php if (empty($results)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No results found</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($results as $result): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">
                                                <span class="badge bg-primary"><?php echo ucfirst($result['type']); ?></span>
                                                <?php 
                                                $title = $result['data']['title'] ?? $result['data']['name'] ?? $result['data']['subject'] ?? 'Item';
                                                echo htmlspecialchars($title);
                                                ?>
                                            </h6>
                                            <a href="<?php echo $result['type']; ?>.php" class="btn btn-sm btn-outline-primary">
                                                View <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <p class="mb-1 text-muted">
                                            <?php
                                            $preview = $result['data']['content'] ?? $result['data']['message'] ?? $result['data']['description'] ?? '';
                                            echo htmlspecialchars(substr($preview, 0, 150)) . '...';
                                            ?>
                                        </p>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
