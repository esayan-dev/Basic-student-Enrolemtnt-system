<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("location:admin/index.php");
    exit;
}

ob_start();  // Start output buffering

require 'db.php';
require 'libs/tcpdf/tcpdf.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '<h2 style="text-align:center;">Student Admission List</h2>';
$html .= '<table border="1" cellpadding="5"><thead><tr>
<th>ID</th><th>Name</th><th>Mobile</th><th>Email</th><th>Gender</th><th>Enrolled At</th>
</tr></thead><tbody>';

$stmt = $db->query("SELECT * FROM students ORDER BY id DESC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $html .= '<tr>
    <td>' . htmlspecialchars($row['id']) . '</td>
    <td>' . htmlspecialchars($row['name']) . '</td>
    <td>' . htmlspecialchars($row['mobile']) . '</td>
    <td>' . htmlspecialchars($row['email']) . '</td>
    <td>' . htmlspecialchars($row['gender']) . '</td>
    <td>' . htmlspecialchars($row['enrolled_at']) . '</td>
  </tr>';
}
$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

ob_end_clean();  // Clean (erase) the output buffer and turn off output buffering

$pdf->Output('students.pdf', 'I');
exit;
?>
