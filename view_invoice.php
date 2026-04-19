<?php
include "db.php";
$id=$_GET['id'];

$inv=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM invoices WHERE invoice_id='$id'"));
$items=mysqli_query($conn,"SELECT * FROM invoice_items WHERE invoice_id='$id'");
?>

<h2><?= $inv['invoice_number'] ?></h2>

<table border="1">
<tr><th>Item</th><th>Qty</th><th>Total</th></tr>

<?php while($i=mysqli_fetch_assoc($items)){ ?>
<tr>
<td><?= $i['description'] ?></td>
<td><?= $i['qty'] ?></td>
<td><?= $i['total'] ?></td>
</tr>
<?php } ?>

</table>

<h3>Final: <?= $inv['final_total'] ?></h3>