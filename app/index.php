<?php
require __DIR__ . '/db.php';
$conn = db_connect();

// Add a new employee (proves writes reach the database and persist on the volume)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['name'])) {
    $stmt = $conn->prepare('INSERT INTO employees (name, position, salary) VALUES (?, ?, ?)');
    $name = trim($_POST['name']);
    $position = trim($_POST['position'] ?? '');
    $salary = (float)($_POST['salary'] ?? 0);
    $stmt->bind_param('ssd', $name, $position, $salary);
    $stmt->execute();
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$result = $conn->query('SELECT id, name, position, salary FROM employees ORDER BY id');
$env = getenv('APP_ENV_LABEL') ?: 'Part I';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Employee Directory</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; color: #222; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #f0f4f8; }
    form { margin-top: 1.5rem; display: flex; gap: .5rem; flex-wrap: wrap; }
    input, button { padding: 8px; }
    .tag { background: #0b5; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: .8rem; }
  </style>
</head>
<body>
  <h1>Employee Directory <span class="tag"><?= htmlspecialchars($env) ?></span></h1>
  <p>Served by container <code><?= htmlspecialchars(gethostname()) ?></code> &middot;
     database host <code><?= htmlspecialchars(getenv('MYSQL_HOST') ?: 'db') ?></code> &middot;
     <a href="health.php">health check</a></p>
  <table>
    <tr><th>ID</th><th>Name</th><th>Position</th><th>Salary (Rs.)</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= (int)$row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['position']) ?></td>
        <td><?= number_format((float)$row['salary'], 2) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <form method="post">
    <input name="name" placeholder="Name" required>
    <input name="position" placeholder="Position">
    <input name="salary" type="number" step="0.01" placeholder="Salary">
    <button type="submit">Add employee</button>
  </form>
</body>
</html>
