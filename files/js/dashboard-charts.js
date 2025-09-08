$(function() {
  'use strict';

  // Chart colors
  const chartColors = {
    primary: '#4e73df',
    success: '#1cc88a',
    info: '#36b9cc',
    warning: '#f6c23e',
    danger: '#e74a3b',
    secondary: '#858796',
    light: '#f8f9fc',
    dark: '#5a5c69'
  };

  // Chart options
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom'
      }
    }
  };

  console.log('Initializing charts...');

  // Initialize all charts
  function initializeCharts() {
    // Get the current base URL dynamically
    let baseUrl = '';
    
    // Check if we're in a subdirectory
    const pathParts = window.location.pathname.split('/');
    if (pathParts.length > 2 && pathParts[1] !== 'dashboard') {
      // We're in a subdirectory, extract it
      baseUrl = '/' + pathParts[1];
    }
    
    console.log('Chart initialization - detected base URL:', baseUrl);
    console.log('Current pathname:', window.location.pathname);
    console.log('Path parts:', pathParts);
    console.log('Current origin:', window.location.origin);
    
    // Try multiple URL patterns to find the working one
    const urlPatterns = [
      baseUrl + '/dashboard/chart-data',
      '/dashboard/chart-data',
      baseUrl + '/test-chart-data',
      '/test-chart-data'
    ];
    
    console.log('Will try these URL patterns:', urlPatterns);
    
    // Try each URL pattern until one works
    tryUrlPattern(urlPatterns, 0);
  }
  
  // Recursive function to try different URL patterns
  function tryUrlPattern(urlPatterns, index) {
    if (index >= urlPatterns.length) {
      console.error('All URL patterns failed');
      showChartError();
      return;
    }
    
    const chartDataUrl = urlPatterns[index];
    console.log(`Trying URL pattern ${index + 1}/${urlPatterns.length}:`, chartDataUrl);
    
    fetch(chartDataUrl)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('Chart data received successfully from:', chartDataUrl);
        console.log('Chart data:', data);
        
        if (data.diseaseTrends) {
          initializeDiseaseTrendsChart(data.diseaseTrends);
        }
        if (data.topDiseases) {
          initializeTopDiseasesChart(data.topDiseases);
        }
        if (data.userGrowth) {
          initializeUserGrowthChart(data.userGrowth);
        }
      })
      .catch(error => {
        console.error(`URL pattern ${index + 1} failed:`, error);
        console.error('URL attempted:', chartDataUrl);
        
        // Try the next pattern
        tryUrlPattern(urlPatterns, index + 1);
      });
  }

  // Disease Detection Trends Chart (Line Chart)
  function initializeDiseaseTrendsChart(data) {
    const ctx = document.getElementById('diseaseTrendsChart');
    if (!ctx) {
      console.warn('Disease trends chart element not found');
      return;
    }
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Cases Detected',
          data: data.data,
          borderColor: chartColors.primary,
          backgroundColor: 'rgba(78, 115, 223, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Cases'
            }
          }
        }
      }
    });
  }

  // Top Detected Diseases Chart (Bar Chart)
  function initializeTopDiseasesChart(data) {
    const ctx = document.getElementById('topDiseasesChart');
    if (!ctx) {
      console.warn('Top diseases chart element not found');
      return;
    }
    
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Number of Detections',
          data: data.data,
          backgroundColor: [
            chartColors.primary,
            chartColors.success,
            chartColors.info,
            chartColors.warning,
            chartColors.danger
          ]
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Detections'
            }
          }
        }
      }
    });
  }

  // User Growth Over Time Chart (Line Chart)
  function initializeUserGrowthChart(data) {
    const ctx = document.getElementById('userGrowthChart');
    if (!ctx) {
      console.warn('User growth chart element not found');
      return;
    }
    
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'New Users',
          data: data.data,
          borderColor: chartColors.success,
          backgroundColor: 'rgba(28, 200, 138, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        ...chartOptions,
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Number of Users'
            }
          }
        }
      }
    });
  }

  // Show error message when charts fail to load
  function showChartError() {
    const chartContainers = document.querySelectorAll('.chart-container');
    chartContainers.forEach(container => {
      if (!container.querySelector('canvas')) {
        container.innerHTML = `
          <div class="text-center text-muted py-4">
            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
            <p>Unable to load chart data. Please refresh the page or try again later.</p>
          </div>
        `;
      }
    });
  }

  // Initialize charts when the document is ready
  // Only initialize if charts haven't been initialized by dashboard.js
  document.addEventListener('DOMContentLoaded', function() {
    // Check if charts are already initialized by dashboard.js
    if (window.chartsInitialized) {
      console.log('Charts already initialized by dashboard.js, skipping dashboard-charts.js');
      return;
    }
    
    console.log('Initializing charts via dashboard-charts.js');
    initializeCharts();
  });
}); 