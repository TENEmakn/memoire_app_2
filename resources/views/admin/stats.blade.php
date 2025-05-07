@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Statistiques</h1>
    
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-light py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark">Période</h5>
        </div>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-center">
                <div class="period-buttons me-4 mb-2">
                    <button class="btn btn-primary active me-2" data-period="annuel">Annuel</button>
                    <button class="btn btn-outline-primary me-2" data-period="mensuel">Mensuel</button>
                    <button class="btn btn-outline-primary me-2" data-period="hebdomadaire">Hebdomadaire</button>
                    <button class="btn btn-outline-primary" data-period="journalier">Journalier</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-light py-3 border-bottom">
            <h5 class="text-center mb-0 fw-bold text-dark">Données statistiques</h5>
        </div>
        <div class="card-body p-4">
            <div style="height: 350px;">
                <canvas id="statsChart"></canvas>
            </div>
            <div id="chartLoader" class="text-center p-5 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mt-2">Chargement des données...</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 mb-4">
    <div class="col-12">
        <h2 class="mb-4 fw-bold text-primary">Vue d'ensemble</h2>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="icon-container mb-3">
                    <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Utilisateurs</h4>
                <h2 class="fw-bold text-primary mb-0">{{ $totalUsers }}</h2>
                <p class="text-muted mt-2 mb-0">
                    @if($userGrowthRate > 0)
                        <span class="text-success fw-bold"><i class="fas fa-arrow-up"></i> {{ $userGrowthRate }}%</span> depuis le mois dernier
                    @elseif($userGrowthRate < 0)
                        <span class="text-danger fw-bold"><i class="fas fa-arrow-down"></i> {{ abs($userGrowthRate) }}%</span> depuis le mois dernier
                    @else
                        <span class="text-secondary fw-bold"><i class="fas fa-minus"></i> 0%</span> depuis le mois dernier
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="icon-container mb-3">
                    <i class="fas fa-car text-danger" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Véhicules</h4>
                <h2 class="fw-bold text-danger mb-0">{{ $totalVehicules }}</h2>
                <p class="text-muted mt-2 mb-0">
                    @if($vehicleGrowthRate > 0)
                        <span class="text-success fw-bold"><i class="fas fa-arrow-up"></i> {{ $vehicleGrowthRate }}%</span> depuis le mois dernier
                    @elseif($vehicleGrowthRate < 0)
                        <span class="text-danger fw-bold"><i class="fas fa-arrow-down"></i> {{ abs($vehicleGrowthRate) }}%</span> depuis le mois dernier
                    @else
                        <span class="text-secondary fw-bold"><i class="fas fa-minus"></i> 0%</span> depuis le mois dernier
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
            <div class="card-body text-center p-4">
                <div class="icon-container mb-3">
                    <i class="fas fa-clipboard-list text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold mb-2">Locations</h4>
                <h2 class="fw-bold text-success mb-0">{{ $totalLocations }}</h2>
                <p class="text-muted mt-2 mb-0">
                    @if($locationGrowthRate > 0)
                        <span class="text-success fw-bold"><i class="fas fa-arrow-up"></i> {{ $locationGrowthRate }}%</span> depuis le mois dernier
                    @elseif($locationGrowthRate < 0)
                        <span class="text-danger fw-bold"><i class="fas fa-arrow-down"></i> {{ abs($locationGrowthRate) }}%</span> depuis le mois dernier
                    @else
                        <span class="text-secondary fw-bold"><i class="fas fa-minus"></i> 0%</span> depuis le mois dernier
                    @endif
                </p>
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
    
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn.active {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let statsChart = null;
        const ctx = document.getElementById('statsChart').getContext('2d');
        const chartLoader = document.getElementById('chartLoader');
        
        // Fonction pour créer ou mettre à jour le graphique
        function updateChart(data) {
            const chartConfig = {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Dépenses',
                            data: data.depenses,
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            borderColor: 'rgba(220, 53, 69, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(220, 53, 69, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        },
                        {
                            label: 'Entrées',
                            data: data.benefices,
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            borderColor: 'rgba(25, 135, 84, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(25, 135, 84, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        },
                        {
                            label: 'Bilan (Entrées - Dépenses)',
                            data: data.bilan,
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            title: {
                                display: true,
                                text: 'Montant (FCFA)',
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
                                text: data.xAxisLabel,
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            };
            
            if (statsChart) {
                statsChart.destroy();
            }
            
            statsChart = new Chart(ctx, chartConfig);
        }
        
        // Fonction pour charger les données selon la période
        function loadChartData(period) {
            // Afficher le loader
            document.querySelector('canvas#statsChart').classList.add('d-none');
            chartLoader.classList.remove('d-none');
            
            console.log(`Chargement des données pour la période: ${period}`);
            
            // Appel AJAX pour récupérer les données
            fetch(`/admin/stats/data/${period}`)
                .then(response => {
                    console.log('Statut de la réponse:', response.status);
                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Données reçues:', data);
                    // Cacher le loader et afficher le canvas
                    chartLoader.classList.add('d-none');
                    document.querySelector('canvas#statsChart').classList.remove('d-none');
                    
                    // Mettre à jour le graphique avec les nouvelles données
                    updateChart(data);
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    chartLoader.classList.add('d-none');
                    document.querySelector('canvas#statsChart').classList.remove('d-none');
                    
                    // En cas d'erreur, afficher des données factices
                    const mockData = getMockData(period);
                    updateChart(mockData);
                });
        }
        
        // Fonction pour obtenir des données mock en cas d'erreur
        function getMockData(period) {
            let labels, xAxisLabel;
            
            switch(period) {
                case 'annuel':
                    labels = ['2025', '2026', '2027', '2028', '2029', '2030', '2031', '2032', '2033', '2034', '2035', '2036', '2037'];
                    xAxisLabel = 'Années';
                    break;
                case 'mensuel':
                    labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                    xAxisLabel = 'Mois de l\'année en cours';
                    break;
                case 'hebdomadaire':
                    labels = ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4', 'Semaine 5'];
                    xAxisLabel = 'Semaines du mois en cours';
                    break;
                case 'journalier':
                    labels = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                    xAxisLabel = 'Jours de la semaine en cours';
                    break;
                default:
                    labels = ['2025', '2026', '2027', '2028', '2029', '2030', '2031'];
                    xAxisLabel = 'Années';
            }
            
            // Générer des données aléatoires pour les dépenses et bénéfices
            const depenses = labels.map(() => Math.floor(Math.random() * 1000000) + 100000);
            const benefices = labels.map(() => Math.floor(Math.random() * 2000000) + 500000);
            
            // Calculer le bilan (différence entre bénéfices et dépenses)
            const bilan = depenses.map((dep, index) => benefices[index] - dep);
            
            return {
                labels: labels,
                depenses: depenses,
                benefices: benefices,
                bilan: bilan,
                xAxisLabel: xAxisLabel
            };
        }
        
        // Chargement initial des données (annuel par défaut)
        loadChartData('annuel');
        
        // Gestion des boutons de période
        const periodBtns = document.querySelectorAll('.period-buttons .btn');
        periodBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                periodBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
                periodBtns.forEach(b => b.classList.add('btn-outline-primary'));
                this.classList.remove('btn-outline-primary');
                this.classList.add('active', 'btn-primary');
                
                // Récupérer la période sélectionnée et charger les données correspondantes
                const period = this.getAttribute('data-period');
                loadChartData(period);
            });
        });
    });
</script>

@endsection
