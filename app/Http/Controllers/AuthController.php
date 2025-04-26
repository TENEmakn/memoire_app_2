<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicule;
use App\Models\LocationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Depense;
use App\Models\Benefice;


class AuthController extends Controller
{
    /**
     * Affiche la page d'accueil
     */

    public function index(Request $request)
    {
        try {
            // Validation des entrées
            $validated = $request->validate([
                'marque' => 'nullable|string|max:50',
                'serie' => 'nullable|string|max:50',
                'type' => 'nullable|string|max:50',
                'annee' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'type_annonce' => 'nullable|in:location,vente',
                'page' => 'nullable|integer|min:1'
            ]);

            $perPage = 6;

            $baseQuery = Vehicule::query()->orderBy('disponibilite', 'desc')->where('visibilite', true);

            if (!empty($validated['marque'])) {
                $baseQuery->where('marque', '=', $validated['marque']);
            }
            
            if (!empty($validated['serie'])) {
                $baseQuery->where('serie', '=', $validated['serie']);
            }
            
            if (!empty($validated['type'])) {
                $baseQuery->where('type_vehicule', '=', $validated['type']);
            }
            
            if (!empty($validated['annee'])) {
                $baseQuery->where('annee', '=', $validated['annee']);
            }

            $locationQuery = clone $baseQuery;
            $locationQuery->where('prix_location_abidjan', '>', 0);

            if (!empty($validated['type_annonce']) && $validated['type_annonce'] == 'vente') {
                $vehiculesLocation = collect([]);
            } else {
                $vehiculesLocation = $locationQuery->latest()
                    ->paginate($perPage, ['*'], 'location_page')
                    ->appends($request->except('location_page'));
            }

            $venteQuery = clone $baseQuery;
            $venteQuery->where('prix_vente', '>', 0);

            if (!empty($validated['type_annonce']) && $validated['type_annonce'] == 'location') {
                $vehiculesVente = collect([]);
            } else {
                $vehiculesVente = $venteQuery->latest()
                    ->paginate($perPage, ['*'], 'vente_page')
                    ->appends($request->except('vente_page'));
            }

            $marques = Vehicule::select('marque')->where('visibilite', true)->where('disponibilite', true)
                ->distinct()
                ->orderBy('marque')
                ->pluck('marque');
                
            $series = Vehicule::select('serie')->where('visibilite', true)->where('disponibilite', true)
                ->distinct()
                ->orderBy('serie')
                ->pluck('serie');
                
            $types = Vehicule::select('type_vehicule')->where('visibilite', true)->where('disponibilite', true)
                ->distinct()
                ->orderBy('type_vehicule')
                ->pluck('type_vehicule');
                
            $annees = Vehicule::select('annee')->where('visibilite', true)->where('disponibilite', true)
                ->distinct()
                ->orderBy('annee', 'desc')
                ->pluck('annee');
                
            $disponibilite = Vehicule::select('disponibilite')
                ->distinct()
                ->orderBy('disponibilite')
                ->pluck('disponibilite');

            return view('index', compact(
                'vehiculesLocation',
                'vehiculesVente',
                'marques',
                'series',
                'types',
                'annees',
                'disponibilite',
            ));

        } catch (\Exception $e) {
            \Log::error('Erreur dans la méthode index: ' . $e->getMessage());
            return redirect()->route('index')
                ->with('error', 'Une erreur est survenue lors du chargement des données.');
        }
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * **********************************Affiche la page admin*************************
     */
    public function showadmin()
    {
        // Calculer les bénéfices de la semaine en cours
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $beneficesSemaine = Benefice::whereBetween('date', [$debutSemaine, $finSemaine])->sum('montant');
        
        $depenses = Depense::whereBetween('date', [$debutSemaine, $finSemaine])->sum('montant');
        $chiffre_affaire = $beneficesSemaine - $depenses;
        
        // Récupérer les véhicules les plus souvent loués en statut "terminee"
        $topVehicules = DB::table('location_requests')
            ->join('vehicules', 'location_requests.vehicule_id', '=', 'vehicules.id')
            ->select(
                'vehicules.id', 
                'vehicules.marque', 
                'vehicules.serie', 
                'vehicules.image_principale',
                DB::raw('COUNT(location_requests.id) as total_locations')
            )
            ->where('location_requests.statut', 'terminee')
            ->groupBy('vehicules.id', 'vehicules.marque', 'vehicules.serie', 'vehicules.image_principale')
            ->orderBy('total_locations', 'desc')
            ->limit(4)
            ->get();
            
        return view('admin', compact('beneficesSemaine', 'depenses', 'chiffre_affaire', 'topVehicules'));
    }

    public function showstats()
    {
        $benefices = Benefice::sum('montant');
        
        // Calculer les bénéfices de la semaine en cours
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $beneficesSemaine = Benefice::whereBetween('date', [$debutSemaine, $finSemaine])->sum('montant');
        
        $depenses = Depense::whereBetween('date', [$debutSemaine, $finSemaine])->sum('montant');
        $chiffre_affaire = $beneficesSemaine - $depenses;
        
        // Décompte total des utilisateurs, véhicules et locations
        $totalUsers = User::where('status', 'user')->count();
        $totalVehicules = Vehicule::count();
        $totalLocations = LocationRequest::count();
        
        // Calculer le taux de croissance par rapport au mois précédent
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();
        $currentMonthStart = now()->startOfMonth();
        
        // Croissance des utilisateurs
        $previousMonthUsers = User::where('status', 'user')
            ->where('created_at', '<=', $previousMonthEnd)
            ->count();
        $newUsersThisMonth = User::where('status', 'user')
            ->whereBetween('created_at', [$currentMonthStart, now()])
            ->count();
        $userGrowthRate = $previousMonthUsers > 0 
            ? round(($newUsersThisMonth / $previousMonthUsers) * 100) 
            : 0;
        
        // Croissance des véhicules
        $previousMonthVehicles = Vehicule::where('created_at', '<=', $previousMonthEnd)
            ->count();
        $newVehiclesThisMonth = Vehicule::whereBetween('created_at', [$currentMonthStart, now()])
            ->count();
        $vehicleGrowthRate = $previousMonthVehicles > 0 
            ? round(($newVehiclesThisMonth / $previousMonthVehicles) * 100) 
            : 0;
        
        // Croissance des locations
        $previousMonthLocations = LocationRequest::where('created_at', '<=', $previousMonthEnd)
            ->count();
        $newLocationsThisMonth = LocationRequest::whereBetween('created_at', [$currentMonthStart, now()])
            ->count();
        $locationGrowthRate = $previousMonthLocations > 0 
            ? round(($newLocationsThisMonth / $previousMonthLocations) * 100) 
            : 0;
        
        return view('admin.stats', compact(
            'benefices', 
            'beneficesSemaine', 
            'depenses', 
            'chiffre_affaire',
            'totalUsers',
            'totalVehicules',
            'totalLocations',
            'userGrowthRate',
            'vehicleGrowthRate',
            'locationGrowthRate'
        ));
    }

    public function showdepense()
    {
        $Depenses = Depense::orderBy('date', 'desc')->paginate(10);
        // Calculer les dépenses stratégiques de cette semaine
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $depensesStrategiquesThisWeek = Depense::where('categorie', 'strategique')
            ->whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant');
        

        // Calculer les dépenses fonctionnelles de cette semaine
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $depensesFonctionnellesThisWeek = Depense::where('categorie', 'fonctionnelle')
            ->whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant');
        
        // Calculer les dépenses opérationnelles de cette semaine
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $depensesOperationnellesThisWeek = Depense::where('categorie', 'operationnelle')
            ->whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant');
        

        $totalDepenses = $depensesStrategiquesThisWeek + $depensesFonctionnellesThisWeek + $depensesOperationnellesThisWeek;
        return view('admin.depense', compact('Depenses', 'totalDepenses', 'depensesStrategiquesThisWeek', 'depensesFonctionnellesThisWeek', 'depensesOperationnellesThisWeek'));
    }

    /**
     * Afficher la page des bénéfices
     */
    public function showbenefices()
    {
        // Calculer les bénéfices par différentes périodes
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $debutMois = now()->startOfMonth();
        $finMois = now()->endOfMonth();
        $debutAnnee = now()->startOfYear();
        $finAnnee = now()->endOfYear();
        
        // Récupérer les totaux de revenus des locations de cette semaine
        $revenusLocationsThisWeek = DB::table('location_requests')
            ->where('statut', 'terminee')
            ->whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant_total');
            
        // Récupérer les revenus des ventes de cette semaine
        $revenusVentesThisWeek = DB::table('rendez_vous_ventes')
            ->where('fin_rdv', 'achete')
            ->whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant_vente');
            
        // Récupérer les totaux de dépenses de cette semaine
        $depensesThisWeek = Depense::whereBetween('created_at', [$debutSemaine, $finSemaine])
            ->sum('montant');
            
        // Calculer le bénéfice total de cette semaine
        $beneficeThisWeek = ($revenusLocationsThisWeek + $revenusVentesThisWeek) - $depensesThisWeek;
        
        // Calculer les bénéfices mensuels
        $revenusLocationsThisMonth = DB::table('location_requests')
            ->where('statut', 'terminee')
            ->whereBetween('created_at', [$debutMois, $finMois])
            ->sum('montant_total');
            
        $revenusVentesThisMonth = DB::table('rendez_vous_ventes')
            ->where('fin_rdv', 'achete')
            ->whereBetween('created_at', [$debutMois, $finMois])
            ->sum('montant_vente');
            
        $depensesThisMonth = Depense::whereBetween('created_at', [$debutMois, $finMois])
            ->sum('montant');
            
        $beneficeThisMonth = ($revenusLocationsThisMonth + $revenusVentesThisMonth) - $depensesThisMonth;
        
        // Calculer les bénéfices annuels
        $revenusLocationsThisYear = DB::table('location_requests')
            ->where('statut', 'terminee')
            ->whereBetween('created_at', [$debutAnnee, $finAnnee])
            ->sum('montant_total');
            
        $revenusVentesThisYear = DB::table('rendez_vous_ventes')
            ->where('fin_rdv', 'achete')
            ->whereBetween('created_at', [$debutAnnee, $finAnnee])
            ->sum('montant_vente');
            
        $depensesThisYear = Depense::whereBetween('created_at', [$debutAnnee, $finAnnee])
            ->sum('montant');
            
        $beneficeThisYear = ($revenusLocationsThisYear + $revenusVentesThisYear) - $depensesThisYear;
        
        // Historique des bénéfices mensuels pour le graphique
        $historiqueData = [];
        for ($i = 0; $i < 12; $i++) {
            $moisDate = now()->subMonths($i);
            $debutMoisHisto = $moisDate->copy()->startOfMonth();
            $finMoisHisto = $moisDate->copy()->endOfMonth();
            
            $revenusLocations = DB::table('location_requests')
                ->where('statut', 'terminee')
                ->whereBetween('created_at', [$debutMoisHisto, $finMoisHisto])
                ->sum('montant_total');
                
            $revenusVentes = DB::table('rendez_vous_ventes')
                ->where('fin_rdv', 'achete')
                ->whereBetween('created_at', [$debutMoisHisto, $finMoisHisto])
                ->sum('montant_vente');
                
            $depenses = Depense::whereBetween('created_at', [$debutMoisHisto, $finMoisHisto])
                ->sum('montant');
                
            $benefice = ($revenusLocations + $revenusVentes) - $depenses;
            
            $historiqueData[] = [
                'mois' => $moisDate->format('M Y'),
                'revenusLocations' => $revenusLocations,
                'revenusVentes' => $revenusVentes,
                'depenses' => $depenses,
                'benefice' => $benefice
            ];
        }
        
        // Trier l'historique du plus ancien au plus récent
        $historiqueData = array_reverse($historiqueData);
        
        return view('admin.benefices', compact(
            'beneficeThisWeek', 
            'beneficeThisMonth', 
            'beneficeThisYear',
            'revenusLocationsThisWeek',
            'revenusVentesThisWeek',
            'depensesThisWeek',
            'revenusLocationsThisMonth',
            'revenusVentesThisMonth',
            'depensesThisMonth',
            'revenusLocationsThisYear',
            'revenusVentesThisYear',
            'depensesThisYear',
            'historiqueData'
        ));
    }

    public function showusers(Request $request)
    {
        $query = User::where('status', 'user');
        
        // Appliquer le filtre des pièces d'identité si demandé
        if ($request->has('filter')) {
            $filter = $request->filter;
            
            if ($filter === 'verified') {
                // Pièces vérifiées
                $query->whereNotNull('image_piece_recto')
                      ->whereNotNull('image_piece_verso')
                      ->where('piece_verifie', true);
            } elseif ($filter === 'unverified') {
                // Pièces non vérifiées
                $query->whereNotNull('image_piece_recto')
                      ->whereNotNull('image_piece_verso')
                      ->where('piece_verifie', false);
            } elseif ($filter === 'missing') {
                // Pièces non fournies
                $query->where(function($q) {
                    $q->whereNull('image_piece_recto')
                      ->orWhereNull('image_piece_verso');
                });
            }
        }
        
        $users = $query->paginate(10)->withQueryString();
        
        // Trouver l'utilisateur avec le plus de demandes de location cette année
        $topUserOfYear = DB::table('location_requests')->where('statut', 'terminee')
            ->select('user_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('user_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topUserOfYearData = null;
        if ($topUserOfYear) {
            $topUserOfYearData = User::find($topUserOfYear->user_id);
        }
        
        // Trouver l'utilisateur avec le plus de demandes de location ce mois-ci
        $topUserOfMonth = DB::table('location_requests')->where('statut', 'terminee')
            ->select('user_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->groupBy('user_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topUserOfMonthData = null;
        if ($topUserOfMonth) {
            $topUserOfMonthData = User::find($topUserOfMonth->user_id);
        }
        
        return view('admin.users', compact('users', 'topUserOfYearData', 'topUserOfMonthData'));
    }

    /**
     * Recherche des utilisateurs par email avec le statut 'user'
     */
    public function searchUsers(Request $request)
    {
        $query = User::query();
        
        // Filtre par status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'user');
        }
        
        // Filtre par email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        
        $users = $query->paginate(10)->withQueryString();
        
        // Trouver l'utilisateur avec le plus de demandes de location cette année
        $topUserOfYear = DB::table('location_requests')
            ->select('user_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('user_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topUserOfYearData = null;
        if ($topUserOfYear) {
            $topUserOfYearData = User::find($topUserOfYear->user_id);
        }
        
        // Trouver l'utilisateur avec le plus de demandes de location ce mois-ci
        $topUserOfMonth = DB::table('location_requests')
            ->select('user_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->groupBy('user_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topUserOfMonthData = null;
        if ($topUserOfMonth) {
            $topUserOfMonthData = User::find($topUserOfMonth->user_id);
        }
        
        return view('admin.users', compact('users', 'topUserOfYearData', 'topUserOfMonthData'));
    }

    public function showgestionnaire()
    {
        $gestionnaires = User::where('status', 'gestionnaire')->get();
        return view('admin.gestionnaire', compact('gestionnaires'));
    }

    public function showchauffeur()
    {
        $chauffeurs = User::where('status', 'chauffeur')->get();
        
        // Trouver le chauffeur avec le plus de demandes de location cette année
        $topChauffeurOfYear = DB::table('location_requests')
            ->select('chauffeur_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereNotNull('chauffeur_id')
            ->groupBy('chauffeur_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topChauffeurOfYearData = null;
        if ($topChauffeurOfYear) {
            $topChauffeurOfYearData = User::find($topChauffeurOfYear->chauffeur_id);
        }
        
        // Trouver le chauffeur avec le plus de demandes de location ce mois-ci
        $topChauffeurOfMonth = DB::table('location_requests')
            ->select('chauffeur_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->whereNotNull('chauffeur_id')
            ->groupBy('chauffeur_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topChauffeurOfMonthData = null;
        if ($topChauffeurOfMonth) {
            $topChauffeurOfMonthData = User::find($topChauffeurOfMonth->chauffeur_id);
        }
        
        return view('admin.chauffeur', compact('chauffeurs', 'topChauffeurOfYearData', 'topChauffeurOfMonthData'));
    }

    public function searchChauffeur(Request $request)
    {
        $search = $request->input('search');
        
        // Rechercher des chauffeurs par email ou d'autres champs
        $chauffeurs = User::where('status', 'chauffeur')
            ->where(function($query) use ($search) {
                $query->where('email', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('telephone', 'like', "%{$search}%");
            })
            ->get();
        
        // Récupérer les données pour les meilleurs chauffeurs (comme dans showchauffeur)
        $topChauffeurOfYear = DB::table('location_requests')
            ->select('chauffeur_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereNotNull('chauffeur_id')
            ->groupBy('chauffeur_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topChauffeurOfYearData = null;
        if ($topChauffeurOfYear) {
            $topChauffeurOfYearData = User::find($topChauffeurOfYear->chauffeur_id);
        }
        
        $topChauffeurOfMonth = DB::table('location_requests')
            ->select('chauffeur_id', DB::raw('count(*) as request_count'))
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->whereNotNull('chauffeur_id')
            ->groupBy('chauffeur_id')
            ->orderBy('request_count', 'desc')
            ->first();
        
        $topChauffeurOfMonthData = null;
        if ($topChauffeurOfMonth) {
            $topChauffeurOfMonthData = User::find($topChauffeurOfMonth->chauffeur_id);
        }
        
        return view('admin.chauffeur', compact('chauffeurs', 'topChauffeurOfYearData', 'topChauffeurOfMonthData', 'search'));
    }

    public function showvehicules(Request $request)
    {
        // Récupérer tous les véhicules avec filtres si nécessaire
        $query = Vehicule::query();
        
        // Filtres de recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('marque', 'like', "%{$search}%")
                  ->orWhere('serie', 'like', "%{$search}%")
                  ->orWhere('immatriculation', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('type_vehicule', $request->type);
        }
        
        if ($request->has('marque') && $request->marque) {
            $query->where('marque', $request->marque);
        }
        
        // Filtrage par statut
        if ($request->has('statut')) {
            switch ($request->statut) {
                case 'location':
                    $query->where('etat_vehicule', 'location')
                          ->where('disponibilite', true)
                          ->where('visibilite', true);
                    break;
                case 'vente':
                    $query->where('etat_vehicule', 'vente')
                          ->where(function($q) {
                              $q->where('fin_vehicule', '!=', 'vendu')
                                ->where('visibilite', true)
                                ->orWhereNull('fin_vehicule');
                          });
                    break;
                case 'mission':
                    $query->where('etat_vehicule', 'location')
                          ->where('disponibilite', false);
                    break;
                case 'vendu':
                    $query->where('etat_vehicule', 'vente')
                          ->where('fin_vehicule', 'vendu');
                    break;
                case 'nonactif':
                    $query->where(function($q) {
                        $q->where('fin_vehicule', '!=', 'vendu')
                          ->orWhereNull('fin_vehicule');
                    })
                    ->where('visibilite', false);
                    break;
            }
        }
        
        // Pagination des véhicules
        $vehicules = $query->get(); // Exécuter la requête pour obtenir les résultats
        
        // Statistiques générales
        $totalVehicules = Vehicule::count();
        $vehiculesActifs = Vehicule::where('visibilite', true)->where('etat_vehicule', 'location')->count();
        $vehiculesActifsBadge = Vehicule::where('visibilite', true)->where('etat_vehicule', 'location')->where('disponibilite', true)->count();
        $vehiculesNonActifs = Vehicule::where('visibilite', false)->where('etat_vehicule', 'location')->count();
        $vehiculesNonActifsBadge = Vehicule::where('visibilite', false)->where('disponibilite', true)
                                  ->where(function($query) {
                                      $query->where('etat_vehicule', 'location')
                                            ->orWhere('etat_vehicule', 'vente');
                                  })
                                  ->count();
        $vehiculesActifsvente = Vehicule::where('etat_vehicule', 'vente')->where('visibilite', true)->count();
        $vehiculesNonActifsvente = Vehicule::where('etat_vehicule', 'vente')->Where('visibilite', false)->count();
        $vehiculesVendu = Vehicule::where('etat_vehicule', 'vente')->where('fin_vehicule', 'vendu')->count();
        // Considérons pour l'exemple qu'un véhicule est en vente si son prix de vente > 0
        // et en location si son prix de location > 0
        $vehiculesVente = Vehicule::where('prix_vente', '>', 0)->count();
        $vehiculesLocation = Vehicule::where('prix_location_abidjan', '>', 0)->count();
        
        // Les véhicules en mission sont ceux qui ne sont pas disponibles
        $vehiculesMission = Vehicule::where('etat_vehicule', 'location')->where('disponibilite', false)->count();
        
        // Statistiques par type et marque
        $vehiculesByType = Vehicule::select('type_vehicule', DB::raw('count(*) as count'))
                            ->groupBy('type_vehicule')
                            ->pluck('count', 'type_vehicule');
                            
        $vehiculesByMarque = Vehicule::select('marque', DB::raw('count(*) as count'))
                            ->groupBy('marque')
                            ->pluck('count', 'marque');
        
        // Listes pour les filtres
        $vehiculeTypes = Vehicule::distinct()->pluck('type_vehicule');
        $vehiculeMarques = Vehicule::distinct()->pluck('marque');
        
        return view('admin.vehicules', compact(
            'vehicules', 
            'totalVehicules', 
            'vehiculesActifs', 
            'vehiculesActifsBadge',
            'vehiculesNonActifs',
            'vehiculesNonActifsBadge',
            'vehiculesVente', 
            'vehiculesLocation', 
            'vehiculesMission',
            'vehiculesByType', 
            'vehiculesByMarque',
            'vehiculeTypes',
            'vehiculeMarques',
            'vehiculesActifsvente',
            'vehiculesNonActifsvente',
            'vehiculesVendu'
        ));
    }

    /**
     * Traite la demande de connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $redirectRoute = match($user->status) {
                'admin' => route('admin.index'),
                'gestionnaire' => route('admin.index'), 
                'chauffeur' => route('index'),
                'user' => route('index'),
                default => route('index')
            };

            return redirect()->intended($redirectRoute)
                           ->with('success', 'Ravi de vous revoir ' . $user->name . ' !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne sont pas corrects.',
        ])->onlyInput('email');
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traite la demande d'inscription
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'birthdate' => 'required|date|before:today',
            'password' => 'required|string|min:8|confirmed',
            'commune' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'prenom' => $validated['firstname'],
            'email' => $validated['email'],
            'telephone' => $validated['phone'],
            'date_naissance' => $validated['birthdate'],
            'password' => Hash::make($validated['password']),
            'status' => 'user',
            'commune' => $validated['commune'],
            'ville' => $validated['ville'],
        ]);

        Auth::login($user);

        return redirect()->route('index')->with('success', 'Bienvenue sur notre site ' . $user->name . ' ' . $user->prenom . ' !');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }

    /**
     * Traite les requêtes AJAX pour le formulaire de recherche dynamique
     */
    public function getFilterOptions(Request $request)
    {
        // Log de débogage pour voir les données reçues
        \Log::info('Requête AJAX reçue pour le filtrage dynamique', $request->all());
        
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Préparer un tableau pour les différents types de données
        $responseData = [
            'marque' => [],
            'type' => [],
            'serie' => [],
            'annee' => []
        ];
        
        // Pour chaque type de champ, construire une requête filtrée
        foreach ($responseData as $dataField => &$data) {
            $query = Vehicule::query();
            
            // Appliquer les filtres actuels, sauf pour le champ en cours
            if ($request->has('marque') && $request->marque && $dataField != 'marque') {
                $query->where('marque', $request->marque);
            }
            
            if ($request->has('type') && $request->type && $dataField != 'type') {
                $query->where('type_vehicule', $request->type);
            }
            
            if ($request->has('serie') && $request->serie && $dataField != 'serie') {
                $query->where('serie', $request->serie);
            }
            
            if ($request->has('annee') && $request->annee && $dataField != 'annee') {
                $query->where('annee', $request->annee);
            }
            
            if ($request->has('type_annonce') && $request->type_annonce) {
                if ($request->type_annonce == 'location') {
                    $query->where('prix_location_abidjan', '>', 0);
                } elseif ($request->type_annonce == 'vente') {
                    $query->where('prix_vente', '>', 0);
                }
            }
            
            // Récupérer les données en fonction du type de champ
            switch ($dataField) {
                case 'marque':
                    $data = $query->select('marque')->distinct()->orderBy('marque')->pluck('marque')->toArray();
                    break;
                case 'type':
                    $data = $query->select('type_vehicule')->distinct()->orderBy('type_vehicule')->pluck('type_vehicule')->toArray();
                    break;
                case 'serie':
                    $data = $query->select('serie')->distinct()->orderBy('serie')->pluck('serie')->toArray();
                    break;
                case 'annee':
                    $data = $query->select('annee')->distinct()->orderBy('annee', 'desc')->pluck('annee')->toArray();
                    break;
            }
            
            // Log de débogage pour chaque type de données
            \Log::info("Données pour le champ {$dataField}", ['count' => count($data), 'data' => $data]);
        }
        
        // Log de débogage des données de réponse
        \Log::info('Réponse envoyée pour le filtrage dynamique', $responseData);
        
        return response()->json(['data' => $responseData]);
    }

    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Affiche le profil de l'utilisateur connecté
     */
    public function profil()
    {
        $user = Auth::user();
        return view('autres.profil', compact('user'));
    }

    /**
     * Affiche la liste des réservations pour l'admin
     */
    public function showreservations(Request $request)
    {
        $query = LocationRequest::with(['vehicule', 'user'])
            ->latest();
            
        // Filtrer par statut si demandé
        if ($request->has('statut') || $request->route('statut')) {
            $statut = $request->input('statut') ?: $request->route('statut');
            $query->where('statut', $statut);
        }
        
        // Filtrer par date de création
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        $reservations = $query->get();
            
        return view('admin.reservations', compact('reservations'));
    }

    public function showrdv(Request $request, $statut = null, $fin_rdv = null)
    {
        $query = \App\Models\RendezVousVente::query()->orderBy('id', 'desc');
        
        // Vérifier si on filtre par statut (either from route parameter or request query)
        if ($statut) {
            $query->where('statut', $statut);
        } else if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Vérifier si on filtre par fin_rdv (either from route parameter or request query)
        if ($fin_rdv) {
            $query->where('fin_rdv', $fin_rdv);
        } else if ($request->has('fin_rdv')) {
            $query->where('fin_rdv', $request->fin_rdv);
        }
        
        // Filtre par date de création
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        
        // Récupérer les rendez-vous paginés
        $rendezVous = $query->orderBy('date_rdv', 'desc')
                          ->get(); // Get the results first, then use appends if needed
        
        // Calculer les compteurs de rendez-vous par statut
        $counts = [
            'en_attente' => \App\Models\RendezVousVente::where('statut', 'en_attente')->count(),
            'confirme' => \App\Models\RendezVousVente::where('statut', 'confirme')->count(),
            'en_negociation' => \App\Models\RendezVousVente::where('statut', 'en_negociation')->count(),
            'termine' => \App\Models\RendezVousVente::where('statut', 'termine')->count(),
            'annule' => \App\Models\RendezVousVente::where('statut', 'annule')->count(),
            'achete' => \App\Models\RendezVousVente::where('fin_rdv', 'achete')->count(),
            'refuse' => \App\Models\RendezVousVente::where('fin_rdv', 'refuse')->count(),
        ];
        
        return view('admin.rdv', compact('rendezVous', 'counts'));
    }
    
    /**
     * Récupère les statistiques d'utilisateurs pour le graphique
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersStats(Request $request)
    {
        $period = $request->input('period', 'mensuel');
        $status = $request->input('status', 'user');
        $currentYear = date('Y');
        
        if ($period === 'hebdomadaire') {
            // Données pour la période hebdomadaire (semaines du mois en cours)
            $newUsers = [];
            $activeUsers = [];
            
            $currentMonth = date('m');
            $weeksInMonth = 5; // Maximum 5 semaines dans un mois
            
            for ($week = 1; $week <= $weeksInMonth; $week++) {
                // Calculer le début et la fin de la semaine
                $startDate = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->addWeeks($week - 1);
                $endDate = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->addWeeks($week)->subDay();
                
                // Limiter à la fin du mois
                $endOfMonth = \Carbon\Carbon::createFromDate($currentYear, $currentMonth)->endOfMonth();
                if ($endDate->gt($endOfMonth)) {
                    $endDate = $endOfMonth;
                }
                
                // Si la semaine est au-delà du mois en cours, arrêter
                if ($startDate->month != $currentMonth) {
                    break;
                }
                
                // Compter les nouveaux utilisateurs pour cette semaine
                $newUsersCount = User::where('status', $status)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
                
                // Compter les utilisateurs actifs (ayant au moins une demande de location) pour cette semaine
                $activeUsersCount = DB::table('users')
                    ->join('location_requests', 'users.id', '=', 'location_requests.user_id')
                    ->where('users.status', $status)
                    ->whereBetween('location_requests.created_at', [$startDate, $endDate])
                    ->distinct('users.id')
                    ->count('users.id');
                
                $newUsers[] = $newUsersCount;
                $activeUsers[] = $activeUsersCount;
            }
        } elseif ($period === 'annuel') {
            // Données pour la période annuelle (années 2025 à 2037)
            $newUsers = [];
            $activeUsers = [];
            
            // Années de 2025 à 2037
            $startYear = 2025;
            $endYear = 2037;
            
            for ($year = $startYear; $year <= $endYear; $year++) {
                // Pour les années futures, nous utilisons des données réelles si l'année est passée ou en cours
                // Sinon, nous mettons 0 pour les statistiques futures
                if ($year <= $currentYear) {
                    // Compter les nouveaux utilisateurs pour cette année
                    $newUsersCount = User::where('status', $status)
                        ->whereYear('created_at', $year)
                        ->count();
                    
                    // Compter les utilisateurs actifs (ayant au moins une demande de location) pour cette année
                    $activeUsersCount = DB::table('users')
                        ->join('location_requests', 'users.id', '=', 'location_requests.user_id')
                        ->where('users.status', $status)
                        ->whereYear('location_requests.created_at', $year)
                        ->distinct('users.id')
                        ->count('users.id');
                } else {
                    // Pour les années futures, nous mettons 0 (ou des projections si souhaité)
                    $newUsersCount = 0;
                    $activeUsersCount = 0;
                }
                
                $newUsers[] = $newUsersCount;
                $activeUsers[] = $activeUsersCount;
            }
        } else {
            // Données pour la période mensuelle (par défaut)
            $newUsers = [];
            $activeUsers = [];
            
            // Pour chaque mois de l'année en cours
            for ($month = 1; $month <= 12; $month++) {
                // Compter les nouveaux utilisateurs pour ce mois
                $newUsersCount = User::where('status', $status)
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $month)
                    ->count();
                
                // Compter les utilisateurs actifs (ayant au moins une demande de location) pour ce mois
                $activeUsersCount = DB::table('users')
                    ->join('location_requests', 'users.id', '=', 'location_requests.user_id')
                    ->where('users.status', $status)
                    ->whereYear('location_requests.created_at', $currentYear)
                    ->whereMonth('location_requests.created_at', $month)
                    ->distinct('users.id')
                    ->count('users.id');
                
                $newUsers[] = $newUsersCount;
                $activeUsers[] = $activeUsersCount;
            }
        }
        
        return response()->json([
            'newUsers' => $newUsers,
            'activeUsers' => $activeUsers
        ]);
    }

    /**
     * Supprime un utilisateur
     */
    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Erreur lors de la suppression de l\'utilisateur: ' . $e->getMessage());
        }
    }

    /**
     * Crée un nouvel utilisateur depuis le panneau d'administration
     */
    public function storeUser(Request $request)
    {
        try {
            // Validation des données du formulaire
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'telephone' => 'nullable|string|max:20',
                'date_naissance' => 'nullable|date',
                'adresse' => 'nullable|string|max:255',
                'ville' => 'nullable|string|max:255',
                'status' => 'required|string|in:user,admin,gestionnaire,chauffeur',
                'image_piece_recto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'image_piece_verso' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'piece_verifie' => 'nullable|boolean',
            ]);

            // Création de l'utilisateur avec les champs de base
            $userData = [
                'name' => $validated['name'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'telephone' => $validated['telephone'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'ville' => $validated['ville'] ?? null,
                'status' => $validated['status'],
                'email_verified_at' => now(), // L'utilisateur est vérifié par défaut lorsqu'il est créé par un admin
                'piece_verifie' => isset($validated['piece_verifie']) ? true : false,
            ];

            // Traitement des images pour les pièces d'identité
            if ($request->hasFile('image_piece_recto')) {
                $recto = $request->file('image_piece_recto');
                $rectoName = time() . '_recto.' . $recto->extension();
                $rectoPath = $recto->storeAs('documents/pieces', $rectoName, 'public');
                $userData['image_piece_recto'] = $rectoPath;
            }

            if ($request->hasFile('image_piece_verso')) {
                $verso = $request->file('image_piece_verso');
                $versoName = time() . '_verso.' . $verso->extension();
                $versoPath = $verso->storeAs('documents/pieces', $versoName, 'public');
                $userData['image_piece_verso'] = $versoPath;
            }

            // Création de l'utilisateur avec toutes les données
            $user = User::create($userData);

            return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created chauffeur in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeChauffeur(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'categorie_permis' => 'required|array',
            'date_naissance' => 'nullable|date',
            'commune' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'image_permis_recto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_permis_verso' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_piece_recto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_piece_verso' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'piece_verifie' => 'nullable|boolean',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            $user->date_naissance = $request->date_naissance;
            $user->commune = $request->commune;
            $user->ville = $request->ville;
            $user->status = 'chauffeur';
            $user->categorie_permis = implode(',', $request->categorie_permis);
            $user->password = Hash::make($request->password);
            $user->piece_verifie = $request->has('piece_verifie') ? true : false;

            // Traitement des images du permis
            if ($request->hasFile('image_permis_recto')) {
                $recto = $request->file('image_permis_recto');
                $rectoName = time() . '_permis_recto.' . $recto->extension();
                $rectoPath = $recto->storeAs('permis', $rectoName, 'public');
                $user->image_permis_recto = $rectoPath;
            }

            if ($request->hasFile('image_permis_verso')) {
                $verso = $request->file('image_permis_verso');
                $versoName = time() . '_permis_verso.' . $verso->extension();
                $versoPath = $verso->storeAs('permis', $versoName, 'public');
                $user->image_permis_verso = $versoPath;
            }

            // Traitement des images de pièce d'identité
            if ($request->hasFile('image_piece_recto')) {
                $recto = $request->file('image_piece_recto');
                $rectoName = time() . '_piece_recto.' . $recto->extension();
                $rectoPath = $recto->storeAs('documents/pieces', $rectoName, 'public');
                $user->image_piece_recto = $rectoPath;
            }

            if ($request->hasFile('image_piece_verso')) {
                $verso = $request->file('image_piece_verso');
                $versoName = time() . '_piece_verso.' . $verso->extension();
                $versoPath = $verso->storeAs('documents/pieces', $versoName, 'public');
                $user->image_piece_verso = $versoPath;
            }

            $user->save();

            return redirect()->route('admin.chauffeur')
                ->with('success', 'Chauffeur ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.chauffeur')
                ->with('error', 'Erreur lors de l\'ajout du chauffeur: ' . $e->getMessage());
        }
    }

    /**
     * Supprime un chauffeur
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyChauffeur($id)
    {
        try {
            $chauffeur = User::findOrFail($id);
            
            // Vérifier si c'est bien un chauffeur
            if ($chauffeur->status !== 'chauffeur') {
                return redirect()->route('admin.chauffeur')
                    ->with('error', 'Cet utilisateur n\'est pas un chauffeur.');
            }
            
            // Supprimer les images de permis si elles existent
            if ($chauffeur->image_permis_recto) {
                \Storage::disk('public')->delete($chauffeur->image_permis_recto);
            }
            
            if ($chauffeur->image_permis_verso) {
                \Storage::disk('public')->delete($chauffeur->image_permis_verso);
            }
            
            $chauffeur->delete();

            $vehicules = Vehicule::where('chauffeur_id', $id)->get();
            foreach ($vehicules as $vehicule) {
                $vehicule->visibilite = false;
                $vehicule->user_id = null;
                $vehicule->save();
            }
            
            return redirect()->route('admin.chauffeur')
                ->with('success', 'Chauffeur supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.chauffeur')
                ->with('error', 'Erreur lors de la suppression du chauffeur: ' . $e->getMessage());
        }
    }

    public function simpleUserSearch(Request $request)
    {
        $query = User::query()->where('status', 'user');
        
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        
        $users = $query->paginate(10)->withQueryString();
        
        return view('admin.users', [
            'users' => $users,
            'topUserOfYearData' => null,
            'topUserOfMonthData' => null
        ]);
    }
    
    /**
     * Vérifier les pièces d'identité d'un utilisateur
     */
    public function verifyUserPieces($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Vérifier que les pièces sont bien présentes
            if (!$user->image_piece_recto || !$user->image_piece_verso) {
                return redirect()->back()->with('error', 'Les pièces d\'identité sont incomplètes.');
            }
            
            // Marquer les pièces comme vérifiées
            $user->piece_verifie = true;
            $user->save();
            
            return redirect()->back()->with('success', 'Les pièces d\'identité ont été vérifiées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la vérification des pièces : ' . $e->getMessage());
        }
    }

    /**
     * Refuser les pièces d'identité d'un utilisateur
     */
    public function refuseUserPieces($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->piece_verifie = false;
            $user->image_piece_recto = null;
            $user->image_piece_verso = null;
            $user->save();
            
            return redirect()->back()->with('success', 'Pièces d\'identité refusées avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la vérification des pièces : ' . $e->getMessage());
        }
    }
} 