@extends('../layouts.appadmin')

@section('content')

<div class="container mt-5">
    <h1 class="mb-4 fw-bold text-primary">Dépenses</h1>

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
                    <h5 class="mb-0 fw-bold">DEPENSES TOTALES DE CETTE SEMAINE <span class="badge bg-white text-primary">{{ $Depenses->total() }}</span></h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-primary">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-danger text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">DEPENSES STRATEGIQUES DE CETTE SEMAINE <span class="badge bg-white text-primary">{{ $Depenses->where('categorie', 'strategique')->count() }}</span></h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-danger">{{ number_format($depensesStrategiquesThisWeek, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-danger text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">DÉPENSES FONCTIONNELLES DE CETTE SEMAINE <span class="badge bg-white text-primary">{{ $Depenses->where('categorie', 'fonctionnelle')->count() }}</span></h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-danger">{{ number_format($depensesFonctionnellesThisWeek, 0, ',', ' ') }} FCFA</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-3 hover-shadow transition">
                <div class="card-header bg-danger text-white text-center py-3 rounded-top">
                    <h5 class="mb-0 fw-bold">DEPENSES OPERATIONNELLES DE CETTE SEMAINE <span class="badge bg-white text-primary">{{ $Depenses->where('categorie', 'operationnelle')->count() }}</span></h5>
                </div>
                <div class="card-body text-center py-4">
                    <h3 class="fw-bold text-danger">{{ number_format($depensesOperationnellesThisWeek, 0, ',', ' ') }} FCFA</h3>
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
        <div class="col-md-6 d-flex align-items-end">
            <button class="btn btn-primary w-100 py-3" style="border-radius: 25px;" data-bs-toggle="modal" data-bs-target="#depenseModal">
                <i class="fas fa-plus-circle me-2"></i> Effectuer une dépense
            </button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom">
                    <h5 class="text-center mb-0 fw-bold text-dark">Évolution des dépenses</h5>
                </div>
                <div class="card-body p-4">
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="depenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Liste des dépenses -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-light py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Liste des dépenses</h5>
                    <div class="d-flex">
                        <input type="text" class="form-control me-2" id="searchDepense" placeholder="Rechercher...">
                        <select class="form-select" id="filterCategorie">
                            <option value="">Toutes les catégories</option>
                            <option value="strategique">Stratégique</option>
                            <option value="fonctionnelle">Fonctionnelle</option>
                            <option value="operationnelle">Opérationnelle</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Catégorie</th>
                                    <th>Motif</th>
                                    <th>Montant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($Depenses as $depense)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($depense->date)) }}</td>
                                    <td>
                                        <span class="badge {{ $depense->categorie == 'strategique' ? 'bg-primary' : ($depense->categorie == 'fonctionnelle' ? 'bg-success' : 'bg-warning') }}">
                                            {{ ucfirst($depense->categorie) }}
                                        </span>
                                    </td>
                                    <td>{{ $depense->motif }}</td>
                                    <td class="fw-bold">{{ number_format($depense->montant, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editDepenseModal{{ $depense->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteDepenseModal{{ $depense->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Aucune dépense trouvée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Affichage de {{ $Depenses->count() }} sur {{ $Depenses->total() }} dépenses</span>
                        </div>
                        <div>
                            {{ $Depenses->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour éditer et supprimer les dépenses -->
@foreach($Depenses as $depense)
<!-- Modal d'édition de dépense -->
<div class="modal fade" id="editDepenseModal{{ $depense->id }}" tabindex="-1" aria-labelledby="editDepenseModalLabel{{ $depense->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editDepenseModalLabel{{ $depense->id }}">Modifier la dépense</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editDepenseForm{{ $depense->id }}" action="{{ route('depenses.update', $depense->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="categorieDepense{{ $depense->id }}" class="form-label fw-bold">Catégorie de dépense</label>
                        <select class="form-select" id="categorieDepense{{ $depense->id }}" name="categorieDepense" required>
                            <option value="strategique" {{ $depense->categorie == 'strategique' ? 'selected' : '' }}>Dépense stratégique</option>
                            <option value="fonctionnelle" {{ $depense->categorie == 'fonctionnelle' ? 'selected' : '' }}>Dépense fonctionnelle</option>
                            <option value="operationnelle" {{ $depense->categorie == 'operationnelle' ? 'selected' : '' }}>Dépense opérationnelle</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="montantDepense{{ $depense->id }}" class="form-label fw-bold">Montant (FCFA)</label>
                        <input type="number" class="form-control" id="montantDepense{{ $depense->id }}" name="montantDepense" placeholder="0.00" step="0.01" min="0" value="{{ $depense->montant }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="motifDepense{{ $depense->id }}" class="form-label fw-bold">Motif</label>
                        <textarea class="form-control" id="motifDepense{{ $depense->id }}" name="motifDepense" rows="3" placeholder="Décrivez le motif de la dépense" required>{{ $depense->motif }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="dateDepense{{ $depense->id }}" class="form-label fw-bold">Date</label>
                        <input type="date" class="form-control" id="dateDepense{{ $depense->id }}" name="dateDepense" value="{{ $depense->date }}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" form="editDepenseForm{{ $depense->id }}">Enregistrer les modifications</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de dépense -->
<div class="modal fade" id="deleteDepenseModal{{ $depense->id }}" tabindex="-1" aria-labelledby="deleteDepenseModalLabel{{ $depense->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteDepenseModalLabel{{ $depense->id }}">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer cette dépense ?</p>
                <div class="alert alert-warning mt-3">
                    <div><strong>Catégorie:</strong> {{ ucfirst($depense->categorie) }}</div>
                    <div><strong>Montant:</strong> {{ number_format($depense->montant, 0, ',', ' ') }} FCFA</div>
                    <div><strong>Motif:</strong> {{ $depense->motif }}</div>
                    <div><strong>Date:</strong> {{ date('d/m/Y', strtotime($depense->date)) }}</div>
                </div>
                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('depenses.destroy', $depense->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal pour effectuer une dépense -->
<div class="modal fade" id="depenseModal" tabindex="-1" aria-labelledby="depenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="depenseModalLabel">Effectuer une dépense</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="depenseForm" action="{{ route('depenses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="categorieDepense" class="form-label fw-bold">Catégorie de dépense</label>
                        <select class="form-select" id="categorieDepense" name="categorieDepense" required>
                            <option value="" selected disabled>Sélectionner une catégorie</option>
                            <option value="strategique">Dépense stratégique</option>
                            <option value="fonctionnelle">Dépense fonctionnelle</option>
                            <option value="operationnelle">Dépense opérationnelle</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="montantDepense" class="form-label fw-bold">Montant (FCFA)</label>
                        <input type="number" class="form-control" id="montantDepense" name="montantDepense" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="motifDepense" class="form-label fw-bold">Motif</label>
                        <textarea class="form-control" id="motifDepense" name="motifDepense" rows="3" placeholder="Décrivez le motif de la dépense" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="dateDepense" class="form-label fw-bold">Date</label>
                        <input type="date" class="form-control" id="dateDepense" name="dateDepense" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary" form="depenseForm">Enregistrer</button>
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
        const ctx = document.getElementById('depenseChart').getContext('2d');
        let depenseChart = null;
        
        // Configuration commune pour tous les graphiques
        const chartOptions = {
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
        };
        
        // Afficher un indicateur de chargement
        function showLoader() {
            const container = document.querySelector('.chart-container');
            container.style.position = 'relative';
            
            // Supprimer un loader existant
            const oldLoader = document.querySelector('.chart-loader');
            if (oldLoader) {
                oldLoader.remove();
            }
            
            const loader = document.createElement('div');
            loader.className = 'chart-loader';
            loader.style.position = 'absolute';
            loader.style.top = '0';
            loader.style.left = '0';
            loader.style.width = '100%';
            loader.style.height = '100%';
            loader.style.display = 'flex';
            loader.style.alignItems = 'center';
            loader.style.justifyContent = 'center';
            loader.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            loader.style.zIndex = '100';
            
            const spinner = document.createElement('div');
            spinner.className = 'spinner-border text-primary';
            spinner.setAttribute('role', 'status');
            
            const span = document.createElement('span');
            span.className = 'visually-hidden';
            span.textContent = 'Chargement...';
            
            spinner.appendChild(span);
            loader.appendChild(spinner);
            container.appendChild(loader);
        }
        
        // Masquer l'indicateur de chargement
        function hideLoader() {
            const loader = document.querySelector('.chart-loader');
            if (loader) {
                loader.remove();
            }
        }
        
        // Fonction pour charger les données du graphique
        async function loadChart(period) {
            showLoader();
            
            // Déterminer l'URL appropriée selon la période
            let url;
            switch(period) {
                case 'annuel':
                    url = '/admin/depense/data/yearly';
                    break;
                case 'mensuel':
                    url = '/admin/depense/data/monthly';
                    break;
                case 'hebdomadaire':
                    console.log('Chargement des données hebdomadaires...');
                    url = '/admin/depense/data/weekly';
                    break;
                case 'journalier':
                    url = '/admin/depense/data/daily';
                    break;
                default:
                    url = '/admin/depense/data/yearly';
            }
            
            console.log('Requête API vers:', url);
            
            try {
                // Charger les données depuis l'API
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                
                const data = await response.json();
                
                // Si un graphique existe déjà, le détruire
                if (depenseChart) {
                    depenseChart.destroy();
                }
                
                // Créer le nouveau graphique avec les données réelles
                depenseChart = new Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: chartOptions
                });
                
            } catch (error) {
                console.error('Erreur lors du chargement des données:', error);
                
                // Afficher l'erreur
                const errorMsg = document.createElement('div');
                errorMsg.className = 'alert alert-danger mt-3';
                errorMsg.textContent = `Erreur lors du chargement des données: ${error.message}`;
                
                const chartContainer = document.querySelector('.chart-container');
                chartContainer.parentNode.insertBefore(errorMsg, chartContainer.nextSibling);
                
                // Supprimer le message d'erreur après 10 secondes
                setTimeout(() => {
                    errorMsg.remove();
                }, 10000);
            } finally {
                hideLoader();
            }
        }
        
        // Charger le graphique annuel par défaut
        loadChart('annuel');
        
        // Configurer les boutons de période
        const periodButtons = document.querySelectorAll('.period-buttons .btn');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Mettre à jour l'apparence des boutons
                periodButtons.forEach(btn => {
                    btn.classList.remove('active', 'btn-primary');
                    btn.classList.add('btn-outline-primary');
                });
                
                this.classList.remove('btn-outline-primary');
                this.classList.add('active', 'btn-primary');
                
                // Charger les données pour la période sélectionnée
                const period = this.getAttribute('data-period');
                console.log('Période sélectionnée:', period);
                loadChart(period);
            });
        });
        
        // Initialiser la date du formulaire à aujourd'hui
        document.getElementById('dateDepense').valueAsDate = new Date();
        
        // Filtrage et recherche de dépenses
        const searchInput = document.getElementById('searchDepense');
        const filterSelect = document.getElementById('filterCategorie');
        
        if (searchInput && filterSelect) {
            const filterTable = () => {
                const searchTerm = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value.toLowerCase();
                const rows = document.querySelectorAll('table tbody tr');
                
                rows.forEach(row => {
                    const category = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const motif = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const date = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const montant = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                    
                    // Vérifier si la ligne correspond aux critères de recherche et de filtrage
                    const matchesSearch = date.includes(searchTerm) || 
                                         category.includes(searchTerm) || 
                                         motif.includes(searchTerm) || 
                                         montant.includes(searchTerm);
                    
                    const matchesFilter = filterValue === '' || category.includes(filterValue);
                    
                    // Afficher ou masquer la ligne en fonction des critères
                    row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
                });
            };
            
            // Ajouter des écouteurs d'événements
            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        }
    });
</script>

@endsection
