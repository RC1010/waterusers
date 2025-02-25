<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A brief description of the page for SEO">
    <title>Water Order Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="./css/analytics.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-dialog/dist/css/bootstrap-dialog.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./css/nav.css">
</head>
<body>
    <div class="header">
        <?php include ("nav.php"); ?> 
    </div>

    <div class="container">
    <div class="chart-section">
        <h2>Water Orders Analysis</h2>
        <canvas id="orderChart"></canvas>
    </div>

    <div class="chart-section">
        <h2>Customers Added Analysis</h2>
        <canvas id="customerChart"></canvas>
    </div>
</div>


<script>
    async function fetchOrderData() {
        try {
            const response = await fetch('http://127.0.0.1:5000/orders');
            const data = await response.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            const ctx = document.getElementById('orderChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Daily', 'Weekly', 'Monthly', 'Yearly'],
                    datasets: [{
                        label: 'Water Orders',
                        data: [data.daily_orders, data.weekly_orders, data.monthly_orders, data.yearly_orders],
                        borderColor: 'blue',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.2
                    },
                    {
                        label: 'Total Amount (₱)',
                        data: [data.daily_amount, data.weekly_amount, data.monthly_amount, data.yearly_amount],
                        backgroundColor: 'green',
                        borderColor: 'green',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error fetching order data:', error);
        }
    }

    async function fetchCustomerData() {
        try {
            const response = await fetch('http://127.0.0.1:5000/customers');
            const data = await response.json();

            if (data.error) {
                alert(data.error);
                return;
            }

            const ctx = document.getElementById('customerChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Daily', 'Weekly', 'Monthly', 'Yearly'],
                    datasets: [{
                        label: 'Customers Added',
                        data: [data.daily_customers, data.weekly_customers, data.monthly_customers, data.yearly_customers],
                        borderColor: 'red',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            onClick: null  // Prevents toggling visibility on legend click
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error fetching customer data:', error);
        }
    }

    fetchOrderData();
    fetchCustomerData();
</script>


</body>
</html>
