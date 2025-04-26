@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Bénéfices</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">BÉNÉFICE DE CETTE SEMAINE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-primary">{{ number_format($beneficeThisWeek, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-success text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">BÉNÉFICE DE CE MOIS</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-success">{{ number_format($beneficeThisMonth, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-info text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">BÉNÉFICE DE CETTE ANNÉE</h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-info">{{ number_format($beneficeThisYear, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulaire d'ajout de bénéfice -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Nouvel enregistrement</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('benefices.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="categorie" class="form-label">Catégorie</label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="location">Location</option>
                                    <option value="vente">Vente</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="montant" class="form-label">Montant (FCFA)</label>
                                <input type="number" class="form-control" id="montant" name="montant" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="remarques" class="form-label">Remarques</label>
                                <textarea class="form-control" id="remarques" name="remarques" rows="2" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Période</h5>
                </div>
                <div class="card-body p-4">
                    <div class="period-buttons">
                        <button class="btn btn-primary active me-2" data-period="annuel">Annuel</button>
                        <button class="btn btn-outline-primary me-2" data-period="mensuel">Mensuel</button>
                        <button class="btn btn-outline-primary me-2" data-period="hebdomadaire">Hebdomadaire</button>
                        <button class="btn btn-outline-primary" data-period="journalier">Journalier</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Répartition des revenus</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div style="height: 200px;">
                        <canvas id="revenusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="text-center mb-0 fw-bold text-dark">Évolution des bénéfices</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="beneficeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé des opérations de cette semaine -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">Résumé financier de cette semaine</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Revenus des locations</h6>
                                    <h4 class="fw-bold text-success">{{ number_format($revenusLocationsThisWeek, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-muted">Revenus des ventes</h6>
                                    <h4 class="fw-bold text-success">{{ number_format($revenusVentesThisWeek, 0, ',', ' ') }} FCFA</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des bénéfices -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                    <h5 class="mb-0 fw-bold text-dark">Liste des bénéfices</h5>
                    <div class="w-100">
                        <form action="{{ route('admin.benefices') }}" method="GET" class="d-flex flex-column flex-md-row gap-2 w-100">
                            
                            <div class="input-group mb-2 mb-md-0">
                                <select name="categorie" class="form-select" onchange="this.form.submit()">
                                    <option value="">Toutes les catégories</option>
                                    <option value="location" {{ request('categorie') == 'location' ? 'selected' : '' }}>Location</option>
                                    <option value="vente" {{ request('categorie') == 'vente' ? 'selected' : '' }}>Vente</option>
                                </select>
                            </div>
                            <div class="input-group mb-2 mb-md-0">
                                <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}" placeholder="Date début">
                            </div>
                            <div class="input-group mb-2 mb-md-0">
                                <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}" placeholder="Date fin">
                            </div>
                            <div class="input-group mb-2 mb-md-0">
                                <input type="text" class="form-control" name="remarques" value="{{ request('remarques') }}" placeholder="Rechercher dans les remarques">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 w-md-auto">Filtrer</button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Catégorie</th>
                                    <th>Montant</th>
                                    <th>Remarques</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($benefices as $benefice)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($benefice->date)->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $benefice->categorie == 'location' ? 'bg-success' : 'bg-primary' }}">
                                            {{ ucfirst($benefice->categorie) }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($benefice->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>{{ $benefice->remarques }}</td>
                                    <td>
                                        @php
                                            $location = \App\Models\LocationRequest::find($benefice->location_request_id);
                                        @endphp
                                        @if($location && $location->statut !== 'terminee')
                                        <div class="btn-group">
                                            <form action="{{ route('benefices.destroy', $benefice->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bénéfice?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    {{ $benefices->links() }}
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique de répartition des revenus
        const ctxRevenus = document.getElementById('revenusChart').getContext('2d');
        const revenusChart = new Chart(ctxRevenus, {
            type: 'doughnut',
            data: {
                labels: ['Locations', 'Ventes'],
                datasets: [{
                    data: [
                        {{ $revenusLocationsThisMonth }},
                        {{ $revenusVentesThisMonth }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(0, 123, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(0, 123, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                return label + ': ' + new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
        
        // Graphique d'évolution des bénéfices
        const ctxBenefice = document.getElementById('beneficeChart').getContext('2d');
        
        const beneficeChart = new Chart(ctxBenefice, {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Bénéfices Vente',
                        data: [],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Bénéfices Location',
                        data: [],
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Bénéfices Totaux',
                        data: [],
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: false
                    }
                ]
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
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
        
        // Configurer les boutons de période
        const periodButtons = document.querySelectorAll('.period-buttons .btn');
        let currentChart = 'annuel';
        let chartData = {};
        
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Mettre à jour l'apparence des boutons
                periodButtons.forEach(btn => {
                    btn.classList.remove('active', 'btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                
                this.classList.remove('btn-outline-primary');
                this.classList.add('active', 'btn-primary');
                
                // Récupérer la période sélectionnée
                const period = this.getAttribute('data-period');
                currentChart = period;
                
                // Appeler l'API correspondante pour récupérer les données
                if (period === 'annuel') {
                    fetch('{{ route("benefices.data.yearly") }}')
                        .then(response => response.json())
                        .then(data => {
                            chartData.annuel = data;
                            updateChart(data, 'annuel');
                        });
                } else if (period === 'mensuel') {
                    const currentYear = new Date().getFullYear();
                    fetch(`{{ route("benefices.data.monthly") }}?year=${currentYear}`)
                        .then(response => response.json())
                        .then(data => {
                            chartData.mensuel = data;
                            updateChart(data, 'mensuel');
                        });
                } else if (period === 'hebdomadaire') {
                    fetch('{{ route("benefices.data.weekly") }}')
                        .then(response => response.json())
                        .then(data => {
                            chartData.hebdomadaire = data;
                            updateChart(data, 'hebdomadaire');
                        });
                } else if (period === 'journalier') {
                    fetch('{{ route("benefices.data.daily") }}')
                        .then(response => response.json())
                        .then(data => {
                            chartData.journalier = data;
                            updateChart(data, 'journalier');
                        });
                }
            });
        });
        
        // Fonction pour mettre à jour le graphique avec de nouvelles données
        function updateChart(data, period) {
            let labels = [];
            let beneficesVente = [];
            let beneficesLocation = [];
            let beneficesTotaux = [];
            let xAxisTitle = "";
            
            if (period === 'annuel') {
                // Utiliser les années fournies par l'API (2025-2037)
                labels = data.years || [];
                beneficesVente = data.beneficesVente || [];
                beneficesLocation = data.beneficesLocation || [];
                beneficesTotaux = data.beneficesTotaux || [];
                xAxisTitle = "Années";
            } else if (period === 'mensuel') {
                labels = data.months || ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
                beneficesVente = data.beneficesVente || [];
                beneficesLocation = data.beneficesLocation || [];
                beneficesTotaux = data.beneficesTotaux || [];
                xAxisTitle = "Mois de l'année " + new Date().getFullYear();
            } else if (period === 'hebdomadaire') {
                labels = data.weeks || ["Semaine 1", "Semaine 2", "Semaine 3", "Semaine 4", "Semaine 5"];
                beneficesVente = data.beneficesVente || [];
                beneficesLocation = data.beneficesLocation || [];
                beneficesTotaux = data.beneficesTotaux || [];
                xAxisTitle = "Semaines du mois";
            } else if (period === 'journalier') {
                labels = data.days || ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
                beneficesVente = data.beneficesVente || [];
                beneficesLocation = data.beneficesLocation || [];
                beneficesTotaux = data.beneficesTotaux || [];
                xAxisTitle = "Jours de la semaine";
            }
            
            beneficeChart.data.labels = labels;
            beneficeChart.data.datasets[0].data = beneficesVente;
            beneficeChart.data.datasets[1].data = beneficesLocation;
            beneficeChart.data.datasets[2].data = beneficesTotaux;
            
            beneficeChart.options.scales.x = {
                title: {
                    display: true,
                    text: xAxisTitle
                },
                ticks: {
                    autoSkip: false,
                    maxRotation: 90,
                    minRotation: 45
                }
            };
            
            beneficeChart.options.scales.y = {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Bénéfices (FCFA)'
                },
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                    }
                }
            };
            
            beneficeChart.update();
        }
        
        // Charger les données annuelles par défaut
        fetch('{{ route("benefices.data.yearly") }}')
            .then(response => response.json())
            .then(data => {
                chartData.annuel = data;
                updateChart(data, 'annuel');
            })
            .catch(error => {
                console.error('Erreur lors du chargement des données annuelles:', error);
                // Afficher un message d'erreur si les données ne peuvent pas être chargées
                const errorData = {
                    years: ['Aucune donnée disponible'],
                    beneficesVente: [0],
                    beneficesLocation: [0],
                    beneficesTotaux: [0]
                };
                chartData.annuel = errorData;
                updateChart(errorData, 'annuel');
            });
    });
</script>

@endsection 