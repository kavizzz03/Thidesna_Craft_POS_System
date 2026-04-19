<?php include "db.php"; ?>
<h2>Invoices</h2>

<table border="1">
<tr><th>Invoice</th><th>Date</th><th>Final</th><th>View</th></tr>

<?php
$q=mysqli_query($conn,"SELECT * FROM invoices ORDER BY invoice_id DESC");
while($r=mysqli_fetch_assoc($q)){
?>
<tr>
<td><?= $r['invoice_number'] ?></td>
<td><?= $r['date_time'] ?></td>
<td><?= $r['final_total'] ?></td>
<td><a href="view_invoice.php?id=<?= $r['invoice_id'] ?>">View</a></td>
</tr>
<?php } ?>