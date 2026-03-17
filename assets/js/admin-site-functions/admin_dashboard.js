document.addEventListener('DOMContentLoaded', function () {

    // ================= HOVER LINE PLUGIN =================
    const hoverLine = {
        id: 'hoverLine',
        afterDraw(chart) {
            if (chart.tooltip?._active?.length) {
                const ctx = chart.ctx;
                const x = chart.tooltip._active[0].element.x;
                const topY = chart.scales.y.top;
                const bottomY = chart.scales.y.bottom;

                ctx.save();
                ctx.beginPath();
                ctx.moveTo(x, topY);
                ctx.lineTo(x, bottomY);
                ctx.lineWidth = 1;
                ctx.strokeStyle = '#d1d5db';
                ctx.setLineDash([4,4]);
                ctx.stroke();
                ctx.restore();
            }
        }
    };

    // ================= DAILY CHART =================
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [4200, 3800, 5100, 4600, 6200, 7800, 5400],
                backgroundColor: '#f4a100',
                borderRadius: 3,
                borderSkipped: false,
                categoryPercentage: 0.55,
                barPercentage: 1.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: { display: false }
            },

            scales: {
                x: {
                    grid: {
                        color: '#e5e7eb',
                        borderDash: [4,4]
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb',
                        borderDash: [4,4]
                    },
                    ticks: {
                        color: '#6b7280',
                        callback: v => v >= 1000 ? (v/1000)+'k' : v
                    }
                }
            }
        }
    });

    // ================= MONTHLY CHART =================
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                data: [32000, 28000, 35000, 42000, 39000, 45000],
                borderColor: '#1f4e79',
                backgroundColor: 'rgba(31,78,121,0.08)',
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            animations: {
                tension: {
                    duration: 800,
                    easing: 'easeOutCubic',
                    from: 0.2,
                    to: 0.4
                }
            },

            plugins: {
                legend: { display: false },

                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#111',
                    bodyColor: '#111',
                    borderColor: '#e5e7eb',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    cornerRadius: 6,
                    caretSize: 6,
                    animation: {
                        duration: 200
                    },
                    callbacks: {
                        label: ctx => ' $' + ctx.parsed.y.toLocaleString()
                    }
                }
            },

            scales: {
                x: {
                    grid: {
                        color: '#e5e7eb',
                        borderDash: [4,4]
                    },
                    ticks: {
                        color: '#6b7280'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#e5e7eb',
                        borderDash: [4,4]
                    },
                    ticks: {
                        color: '#6b7280',
                        callback: v => v >= 1000 ? (v/1000)+'k' : v
                    }
                }
            },

            elements: {
                point: {
                    radius: 0,
                    hoverRadius: 6,
                    backgroundColor: '#1f4e79',
                    borderWidth: 3,
                    borderColor: '#fff'
                }
            }
        },

        plugins: [hoverLine] // 🔥 attach plugin HERE
    });

});