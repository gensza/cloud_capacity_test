<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div style="width: 800px; height: 400px; margin: 0 auto; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Cloud Capacity</h2>
            <a href="{{ route('cloud_capacity.download') }}"
                style="padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Download
                Excel</a>
        </div>
        <canvas id="capacityChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($labels);
        const cpuValues = @json($cpu);
        const memValues = @json($mem);

        const ctx = document.getElementById('capacityChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, // dari controller
                datasets: [{
                        label: 'Total CPU',
                        data: cpuValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Memory',
                        data: memValues,
                        backgroundColor: 'rgba(255, 159, 64, 0.5)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }
                ]
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
    </script>

</body>

</html>
