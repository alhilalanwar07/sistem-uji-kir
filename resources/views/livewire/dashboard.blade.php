<?php

use Livewire\Volt\Component;

new class extends Component {

    public function with(): array
    {
        return [
            'totalKendaraan' => \App\Models\Kendaraan::count(),
            'totalParameter' => \App\Models\Parameter::count(),
            'totalUji' => \App\Models\Uji::count(),
            'totalKirAktif' => \App\Models\Kendaraan::where('status_kir', 'aktif')->count(),
            'totalKirNonAktif' => \App\Models\Kendaraan::where('status_kir', '!=', 'aktif')->count(),
        ];
    }
}; ?>

<div>
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .display-6 {
            font-weight: bold;
            color: #021025FF;
        }

        .card-icon {
            font-size: 3rem;
            color: #021025FF;
        }
    </style>
        <div class="row g-4">
            <!-- Card: Selamat Datang -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-user card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Selamat Datang</h5>
                        <p class="card-text">
                            Selamat datang di dashboard,
                            <strong>{{ auth()->check() ? auth()->user()->name : 'Pengunjung' }}</strong>!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Kendaraan -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-car-side card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Total Kendaraan</h5>
                        <p class="card-text display-6">{{ $totalKendaraan }}</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Parameter -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-sliders card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Total Parameter</h5>
                        <p class="card-text display-6">{{ $totalParameter }}</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total Uji -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-check-to-slot card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Total Uji</h5>
                        <p class="card-text display-6">{{ $totalUji }}</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total KIR Aktif -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-circle-check card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Total KIR Aktif</h5>
                        <p class="card-text display-6">{{ $totalKirAktif }}</p>
                    </div>
                </div>
            </div>

            <!-- Card: Total KIR Non-Aktif -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fa-solid fa-circle-xmark card-icon mb-3"></i>
                        <h5 class="card-title mb-3">Total KIR Non-Aktif</h5>
                        <p class="card-text display-6">{{ $totalKirNonAktif }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Interaktif -->
        <div class="row mt-5">
            <div class="col-md-12" data-aos="fade-up" data-aos-delay="700">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Statistik KIR</h5>
                        <canvas id="kirChart" height="70"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom Script -->
    <script>
        // Initialize AOS
        AOS.init();

        // Chart.js Configuration
        const ctx = document.getElementById('kirChart').getContext('2d');
        const kirChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['KIR Aktif', 'KIR Non-Aktif'],
                datasets: [{
                    label: 'Jumlah KIR',
                    data: [{{ $totalKirAktif }}, {{ $totalKirNonAktif }}],
                    backgroundColor: ['#021025FF', '#dc3545'],
                    borderColor: ['#021025FF', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Statistik KIR Aktif vs Non-Aktif'
                    }
                }
            }
        });
    </script>
</div>