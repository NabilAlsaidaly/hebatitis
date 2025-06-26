@extends('patient.layouts.app')

@section('styles')
<style>
    #liverChart {
        width: 100%;
        height: 420px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h4 class="mb-4 text-end">📈 تطور تحاليل الكبد</h4>
    <canvas id="liverChart"></canvas>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("{{ route('patient.chart.data') }}")
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('liverChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'ALT',
                            data: data.ALT,
                            borderColor: '#007bff',
                            backgroundColor: '#007bff20',
                            borderWidth: 2,
                            pointBackgroundColor: '#007bff',
                            pointRadius: 4,
                            tension: 0.2
                        },
                        {
                            label: 'AST',
                            data: data.AST,
                            borderColor: '#28a745',
                            backgroundColor: '#28a74520',
                            borderWidth: 2,
                            pointBackgroundColor: '#28a745',
                            pointRadius: 4,
                            tension: 0.2
                        },
                        {
                            label: 'BIL',
                            data: data.BIL,
                            borderColor: '#dc3545',
                            backgroundColor: '#dc354520',
                            borderWidth: 2,
                            pointBackgroundColor: '#dc3545',
                            pointRadius: 4,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: {
                                    size: 14
                                },
                                rtl: true
                            }
                        },
                        tooltip: {
                            rtl: true,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y}`;
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'تحليل زمني لنتائج الكبد',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            padding: {
                                bottom: 10
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'التاريخ',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'القيمة التحليلية',
                                font: {
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        });
});
</script>
@endsection
