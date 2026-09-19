import Chart from 'chart.js/auto';

window.Chart = Chart;
window.dispatchEvent(new CustomEvent('clinic-chart-ready'));
