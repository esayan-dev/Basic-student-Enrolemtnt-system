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

// Delete logic (if any)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $db->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
    $stmt->execute();
    $_SESSION['deleted'] = "Student ID $delete_id deleted.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all students
$students = $db->query("SELECT * FROM students ORDER BY enrolled_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Reports
$total_students = count($students);
$total_male = 0;
$total_female = 0;
$today_enrolled = 0;
$today = date('Y-m-d');

foreach ($students as $student) {
    if (strtolower($student['gender']) === 'male') $total_male++;
    if (strtolower($student['gender']) === 'female') $total_female++;
    if (strpos($student['enrolled_at'], $today) === 0) $today_enrolled++;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white p-6 shadow-md">
            <h1 class="text-2xl font-bold text-blue-600 mb-8">Admin Panel</h1>
            <nav class="space-y-4">
                <a href="dashboard.php" class="block text-blue-600 font-semibold">📊 Dashboard</a>
                <a href="enroll.php" class="block text-gray-700 hover:text-blue-500">➕ Enroll Student</a>
                <a href="logout.php" class="block text-red-600 hover:text-red-800 mt-8">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">📊 Student Enrollment Dashboard</h2>

            <?php if (isset($_SESSION['deleted'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?= htmlspecialchars($_SESSION['deleted']) ?>
                    <?php unset($_SESSION['deleted']); ?>
                </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-sm font-medium text-gray-500">Total Students</h3>
                    <p class="text-3xl font-bold text-blue-600"><?= $total_students ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-sm font-medium text-gray-500">Male</h3>
                    <p class="text-3xl font-bold text-green-500"><?= $total_male ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <h3 class="text-sm font-medium text-gray-500">Female</h3>
                    <p class="text-3xl font-bold text-pink-500"><?= $total_female ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow text-center col-span-1 md:col-span-3">
                    <h3 class="text-sm font-medium text-gray-500">Enrolled Today (<?= $today ?>)</h3>
                    <p class="text-2xl font-bold text-purple-600"><?= $today_enrolled ?></p>
                </div>
            </div>

            <!-- Students Table -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">📝 Recent Enrollments</h3>
                <a href="../export_pdf.php" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">📄 Export PDF</a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Mobile</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Gender</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Enrolled At</th>
                            <th class="px-4 py-3 text-left text-gray-500 font-medium uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if ($students): ?>
                            <?php foreach ($students as $student): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['id']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['name']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['mobile']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['email']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['gender']) ?></td>
                                    <td class="px-4 py-2"><?= htmlspecialchars($student['enrolled_at']) ?></td>
                                    <td class="px-4 py-2">
                                        <form method="POST" onsubmit="return confirm('Delete student ID <?= $student['id'] ?>?');">
                                            <input type="hidden" name="delete_id" value="<?= $student['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800">🗑 Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No students enrolled yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
