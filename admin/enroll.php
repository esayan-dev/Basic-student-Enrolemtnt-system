<?php
session_start();

// Auth check
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

// SQLite DB connection
$db = new PDO('sqlite:../d1a526666a87b5e50ee2f26368dcdb2d.sqlite');
$db->exec("CREATE TABLE IF NOT EXISTS students (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT,
  mobile TEXT,
  email TEXT,
  gender TEXT,
  enrolled_at TEXT NOT NULL
)");

// Delete student if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $db->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();
    // Optional: flash message via session
    $_SESSION['deleted'] = "Student ID $delete_id has been deleted.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all students
$students = $db->query("SELECT * FROM students ORDER BY enrolled_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Enrollment Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white p-6 shadow-md">
            <h1 class="text-2xl font-bold text-blue-600 mb-8">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="dashboard.php" class="block text-gray-700 hover:text-blue-500">📊 Dashboard</a>
                <a href="enroll.php" class="block text-blue-600 font-semibold">➕ Enroll Student</a>
                <a href="logout.php" class="block text-red-600 hover:text-red-800 mt-8">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-10">
            <?php if (isset($_SESSION['deleted'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['deleted']) ?>
        <?php unset($_SESSION['deleted']); ?>
    </div>
<?php endif; ?>

    <div class="flex justify-between items-center mb-4">
    <h2 class="text-3xl font-bold text-gray-800">Enrolled Students</h2>
    <a href="../export_pdf.php" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">📄 Export PDF</a>
        </div>
            <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled At</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $student): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['id']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['name']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['mobile']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['email']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['gender']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['enrolled_at']) ?></td>
                                    <td class="px-4 py-2">
    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');">
        <input type="hidden" name="delete_id" value="<?= $student['id'] ?>">
        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">🗑 Delete</button>
    </form>
</td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-4 text-center text-gray-500">No students enrolled yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
