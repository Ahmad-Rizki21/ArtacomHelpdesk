<!-- resources/views/filament/pages/dashboard.blade.php -->
<x-filament::page>
    <div class="space-y-5">

        <x-filament::card>
            <h1 class="text-xl font-semibold leading-tight"> LAPORAN TICKET FTTH BULAN INI !</h1>

            <br>

            <!-- Pembungkus utama dengan Flexbox -->
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                <!-- Card 1: Ticket Open -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-danger">
                        <i class="fa fa-skull-crossbones text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Open</h4>
                        </div>
                        <div class="card-body">
                            {{ $openTickets }}
                        </div>
                    </div>
                </div>

                <!-- Card 2: Ticket Pending -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-warning">
                        <i class="fa fa-exclamation-triangle text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Pending</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingTickets }}
                        </div>
                    </div>
                </div>

                <!-- Card 3: Ticket Closed -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-success">
                        <i class="fa fa-smile-wink text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Closed</h4>
                        </div>
                        <div class="card-body">
                            {{ $closedTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::card>

        
        <br>
        <br>
        

        

        <!-- Card untuk LAPORAN TICKET BULAN INI -->
        <x-filament::card>
            <h2 class="text-xl font-semibold leading-tight"><i class="fas fa-chart-bar"></i> LAPORAN TICKET FTTH BULAN INI !</h2>
            <div class="chart-container">
                <canvas id="ticketStatusChart"></canvas>
            </div>
        </x-filament::card>

        <br>
        <br>

        <x-filament::card>
            <h1 class="text-xl font-semibold leading-tight"> LAPORAN TICKET BACKBONE BULAN INI !</h1>

            <br>
            

            <!-- Pembungkus utama dengan Flexbox -->
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                <!-- Card 1: Ticket Open -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-danger">
                        <i class="fa fa-skull-crossbones text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Open</h4>
                        </div>
                        <div class="card-body">
                            {{ $openBackboneTickets }}
                        </div>
                    </div>
                </div>

                <!-- Card 2: Ticket Pending -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-warning">
                        <i class="fa fa-exclamation-triangle text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Pending</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingBackboneTickets }}
                        </div>
                    </div>
                </div>

                <!-- Card 3: Ticket Closed -->
                <div class="card card-statistic-1" style="flex: 1; min-width: 200px; text-align: center;">
                    <div class="card-icon bg-success">
                        <i class="fa fa-smile-wink text-white fa-2x"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Ticket Closed</h4>
                        </div>
                        <div class="card-body">
                            {{ $closedBackboneTickets }}
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::card>


        <br>
        <br>
        

        <!-- Card untuk LAPORAN TICKET BACKBONE BULAN INI -->
        <x-filament::card>
            <h2 class="text-xl font-semibold leading-tight"><i class="fas fa-chart-bar"></i> LAPORAN TICKET BACKBONE BULAN INI !</h2>
            <div class="chart-container">
                <canvas id="ticketBackboneStatusChart"></canvas>
            </div>
        </x-filament::card>

        <br>
            <br>

    </div>

    <!-- CSS untuk menyesuaikan warna dan tampilan -->
    <style>
        /* Styling kartu */
        .card.card-statistic-1 {
            flex: 1;
            max-width: calc(33.33% - 1rem);
            background: linear-gradient(145deg, #2c2c2c, #1e1e1e); /* Gradasi gelap */
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.4); /* Bayangan */
            text-align: center;
            padding: 1rem;
            color: #fff; /* Teks putih untuk tema gelap */
        }

        /* Styling ikon di kartu */
        .card.card-statistic-1 .card-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
        }

        /* Warna untuk setiap status */
        .card.card-statistic-1 .card-icon.bg-danger {
            background-color: #e74a3b; /* Merah */
        }

        .card.card-statistic-1 .card-icon.bg-warning {
            background-color: #f6c23e; /* Kuning */
        }

        .card.card-statistic-1 .card-icon.bg-success {
            background-color: #1cc88a; /* Hijau */
        }

        /* Teks di header kartu */
        .card.card-statistic-1 .card-header h4 {
            font-size: 1rem;
            color: #ddd; /* Warna teks lebih terang */
            margin: 0.5rem 0;
            font-weight: bold;
        }

        /* Nilai di body kartu */
        .card.card-statistic-1 .card-body {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff; /* Teks putih */
        }

        /* Chart container */
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
        }

        #ticketStatusChart, #ticketBackboneStatusChart {
            width: 100% !important;
            height: 100% !important;
        }
    </style>


<br>

    <!-- Tambahkan Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Script untuk Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart untuk Ticket
            const ctx = document.getElementById('ticketStatusChart').getContext('2d');
            const ticketStatusChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['OPEN', 'PENDING', 'CLOSED'],
                    datasets: [{
                        label: '# of Tickets',
                        data: [{{ $openTickets }}, {{ $pendingTickets }}, {{ $closedTickets }}],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)', // Merah
                            'rgba(246, 194, 62, 0.2)', // Kuning
                            'rgba(28, 200, 138, 0.2)'  // Hijau
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(246, 194, 62, 1)',
                            'rgba(28, 200, 138, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    aspectRatio: 2,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            

            // Chart untuk Ticket Backbone
            const ctxBackbone = document.getElementById('ticketBackboneStatusChart').getContext('2d');
            const ticketBackboneStatusChart = new Chart(ctxBackbone, {
                type: 'bar',
                data: {
                    labels: ['OPEN', 'PENDING', 'CLOSED'],
                    datasets: [{
                        label: '# of Ticket Backbone',
                        data: [{{ $openBackboneTickets }}, {{ $pendingBackboneTickets }}, {{ $closedBackboneTickets }}],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)', // Merah
                            'rgba(246, 194, 62, 0.2)', // Kuning
                            'rgba(28, 200, 138, 0.2)'  // Hijau
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(246, 194, 62, 1)',
                            'rgba(28, 200, 138, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    aspectRatio: 2,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-filament::page>