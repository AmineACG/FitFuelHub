<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Line Chart Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="myChart"></canvas>
    </div>
    
    <button id="addLabelButton">Add Label</button>
    <button id="addDataButton">Add Data</button>

    <script>
        var days = 0;
        var chartData = {
            labels: ["January", "February", "March", "April", "May"],
            datasets: [
                {
                    label: "Sales",
                    data: [],
                    borderColor: "rgba(75, 192, 192, 1)",
                    fill: false
                }
            ]
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var addLabelButton = document.getElementById('addLabelButton');
        addLabelButton.addEventListener('click', function() {
            chartData.labels.push(days);
            days++;
            myLineChart.update();
        });

        var addDataButton = document.getElementById('addDataButton');
        addDataButton.addEventListener('click', function() {
            var newDataPoint = Math.floor(Math.random() * 50); // Generate random data
            chartData.datasets[0].data.push(newDataPoint);
            myLineChart.update();
        });
    </script>
</body>
</html>
