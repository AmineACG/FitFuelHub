<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... (your head content) ... -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 50%; margin: 0 auto;">
        <canvas id="myChart"></canvas>
    </div>
    
    <button id="addDataButton">Add Data</button>

    <script>
        var chartData = {
            labels: ["Red", "Blue", "Yellow", "Green", "Purple"],
            datasets: [
                {
                    data: [],
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.7)",
                        "rgba(54, 162, 235, 0.7)",
                        "rgba(255, 206, 86, 0.7)",
                        "rgba(75, 192, 192, 0.7)",
                        "rgba(153, 102, 255, 0.7)"
                    ],
                    borderColor: [
                        "rgba(255, 99, 132, 1)",
                        "rgba(54, 162, 235, 1)",
                        "rgba(255, 206, 86, 1)",
                        "rgba(75, 192, 192, 1)",
                        "rgba(153, 102, 255, 1)"
                    ],
                    borderWidth: 1
                }
            ]
        };

        var ctx = document.getElementById('myChart').getContext('2d');
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: chartData
        });

        var addDataButton = document.getElementById('addDataButton');
        addDataButton.addEventListener('click', function() {
            var newDataValue = Math.floor(Math.random() * 50); // Generate random data
            chartData.datasets[0].data.push(newDataValue);
            myPieChart.update();
        });
    </script>
</body>
</html>
