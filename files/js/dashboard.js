/*
* Place your custom JavaScript here.
*/

$(function() {
    'use strict';

    // Removed Feather Icons initialization (now handled by feather-icons-init.js)

    var colors = {
        primary: "#6571ff",
        secondary: "#7987a1",
        success: "#05a34a",
        info: "#66d1d1",
        warning: "#fbbc06",
        danger: "#ff3366",
        light: "#e9ecef",
        dark: "#060c17",
        muted: "#7987a1",
        gridBorder: "rgba(77, 138, 240, .15)",
        bodyColor: "#000",
        cardBg: "#fff"
    };

    var fontFamily = "'Roboto', Helvetica, sans-serif";

    // Feather Icons initialization - Removed as it will be called from app.blade.php
    // console.log('Type of feather:', typeof feather);
    // console.log('Feather object:', feather);
    // feather.replace();

    // Fetch chart data from the backend
    console.log('Dashboard.js: Attempting to fetch chart data from:', '/dashboard/chart-data');
    $.ajax({
        url: '/dashboard/chart-data',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Dashboard.js: Chart data received successfully:', data);
            // Disease Detection Trends Chart
            if ($('#diseaseTrendsChart').length) {
                var ctx = document.getElementById('diseaseTrendsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.diseaseTrends.labels,
                        datasets: [{
                            label: 'Cases Detected',
                            data: data.diseaseTrends.data,
                            borderColor: colors.primary,
                            backgroundColor: 'rgba(101, 113, 255, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: colors.bodyColor,
                                    font: {
                                        family: fontFamily
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            },
                            y: {
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            }
                        }
                    }
                });
            }

            // Top Detected Diseases Chart
            if ($('#topDiseasesChart').length) {
                var ctx = document.getElementById('topDiseasesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.topDiseases.labels,
                        datasets: [{
                            label: 'Number of Detections',
                            data: data.topDiseases.data,
                            backgroundColor: [colors.primary, colors.danger, colors.warning, colors.success, colors.info]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            }
                        }
                    }
                });
            }

            // User Growth Over Time Chart
            if ($('#userGrowthChart').length) {
                var ctx = document.getElementById('userGrowthChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.userGrowth.labels,
                        datasets: [{
                            label: 'New Users',
                            data: data.userGrowth.data,
                            borderColor: colors.success,
                            backgroundColor: 'rgba(5, 163, 74, 0.2)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: colors.bodyColor,
                                    font: {
                                        family: fontFamily
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: colors.gridBorder,
                                    borderColor: colors.gridBorder
                                },
                                ticks: {
                                    color: colors.bodyColor
                                }
                            }
                        }
                    }
                });
            }
            
            // Mark charts as initialized to prevent dashboard-charts.js from running
            window.chartsInitialized = true;
            console.log('Dashboard.js: Charts initialized successfully');
        },
        error: function(xhr, status, error) {
            console.error('Dashboard.js: Error fetching chart data:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Status Code:', xhr.status);
            console.error('URL attempted:', '/dashboard/chart-data');
            
            // Try to parse response if it's JSON
            try {
                const response = JSON.parse(xhr.responseText);
                console.error('Parsed response:', response);
            } catch (e) {
                console.error('Response is not JSON');
            }
            
            // If dashboard.js fails, let dashboard-charts.js handle it
            console.log('Dashboard.js failed, allowing dashboard-charts.js to handle chart initialization');
        }
    });
});