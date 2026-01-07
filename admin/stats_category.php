<?php
require_once __DIR__ . '/check_auth.php';
require_once __DIR__ . '/../connect.php';

$rs = mysqli_query($conn, "
    SELECT 
        c.name, 
        SUM(oi.quantity * oi.price) AS total
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    JOIN categories c ON c.id = p.category_id
    GROUP BY c.id
");

$data = [];
while ($r = mysqli_fetch_assoc($rs)) {
    $data[] = $r;
}
?>
<canvas id="chart"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const data = <?= json_encode($data) ?>;
    new Chart(document.getElementById('chart'), {
        type: 'bar',
        data: {
            labels: data.map(i => i.name),
            datasets: [{
                label: 'Doanh thu',
                data: data.map(i => i.total),
                backgroundColor: '#6f4e37'
            }]
        }
    });
</script>