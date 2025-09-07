@extends('layouts.app')

@section('content')
<div class="container py-5">
  <h3 class="text-center mb-4">📈 Weekly Attendance Overview</h3>
  <canvas id="weeklyChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('weeklyChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($labels),
      datasets: [{
        label: 'Attendance %',
        data: @json($percentages),
        fill: false,
        borderColor: '#4e73df',
        tension: 0.1,
        pointBackgroundColor: '#f6c23e',
        pointRadius: 5
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          ticks: { callback: v => v + '%' }
        }
      },
      plugins: {
        tooltip: {
          callbacks: { label: ctx => ctx.parsed.y + '%' }
        }
      }
    }
  });
</script>
@endsection
