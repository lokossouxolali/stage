import Chart from 'chart.js/auto';

const chartData = window.dashboardCharts;

if (chartData) {
    const chartColors = ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b'];

    new Chart(document.getElementById('rolesChart'), {
        type: 'bar',
        data: {
            labels: chartData.roles.labels,
            datasets: [{
                label: 'Utilisateurs',
                data: chartData.roles.data,
                backgroundColor: chartColors,
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });

    new Chart(document.getElementById('propositionsChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.propositions.labels,
            datasets: [{
                data: chartData.propositions.data,
                backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
        },
    });
}
