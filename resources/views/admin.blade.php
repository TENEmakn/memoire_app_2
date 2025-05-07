@extends('layouts.appadmin')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Tableau de bord</h1>
    
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL GÉNÉRÉ CETTE SEMAINE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-primary">{{ number_format($beneficesSemaine, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-danger text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">TOTAL DÉPENSES CETTE SEMAINE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-danger">{{ number_format($depenses, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">CHIFFRE D'AFFAIRE CETTE SEMAINE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-success">{{ number_format($beneficesSemaine + $depenses, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="text-center mb-0 fw-bold text-dark">Calendrier Journalier</h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="calendrierChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="text-center mb-0 fw-bold text-dark">Les véhicules majeurs</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        @forelse ($topVehicules as $index => $vehicule)
                        <div class="col-md-6 mb-3">
                            <div class="card bg-white border-0 shadow-sm rounded-3 hover-shadow transition position-relative">
                                <span class="position-absolute top-0 start-0 badge bg-primary m-2 rounded-pill px-3 py-2">
                                    #{{ $index + 1 }}
                                </span>
                                <div class="card-body p-3">
                                    <div class="text-center">
                                        <div class="vehicle-image mb-3">
                                            <img src="{{ asset('storage/' . $vehicule->image_principale) }}" alt="Véhicule" class="img-fluid rounded shadow-sm" style="width: 170px; height: 100px; object-fit: cover;">
                                        </div>
                                        <div class="vehicle-info">
                                            <h6 class="mb-1 fw-bold text-dark">{{ $vehicule->marque }} {{ $vehicule->serie }}</h6>
                                            <span class="badge bg-success">{{ $vehicule->total_locations }} locations</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Aucune donnée disponible pour le moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.transition {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Générer les heures par intervalle de 2h pour l'axe des abscisses
        const heures = [];
        for (let i = 0; i < 24; i += 2) {
            heures.push(i + 'h00');
        }
        
        // Données pour la somme totale générée (à remplacer par des données réelles du backend)
        // Pour l'instant, des données fictives pour démonstration
        const sommeGeneree = [0, 5000, 15000, 25000, 40000, 45000, 60000, 75000, 85000, 95000, 120000, 150000];
        
        var ctx = document.getElementById('calendrierChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: heures,
                datasets: [{
                    label: 'Somme générée (FCFA)',
                    data: sommeGeneree,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('fr-FR') + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fr-FR') + ' FCFA';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Somme totale générée (FCFA)',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: 'Heures de la journée',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
        
        // Fonction pour mettre à jour le graphique toutes les 30 minutes
        function updateChart() {
            // Cette fonction devrait faire une requête AJAX pour récupérer les données les plus récentes
            // et mettre à jour le graphique
            
            fetch('{{ route("benefices.data.hourly") }}')
                .then(response => response.json())
                .then(data => {
                    // Mise à jour des données
                    myChart.data.datasets[0].data = data.sommeGeneree;
                    myChart.update();
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des données:', error);
                });
        }
        
        // Charger les données au chargement de la page
        updateChart();
        
        // Mise à jour toutes les 30 minutes (1800000 ms)
        setInterval(updateChart, 1800000);
    });
</script>

@endsection



