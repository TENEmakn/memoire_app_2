<?php

namespace App\Http\Controllers;

use App\Models\Benefice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\LocationRequest;
use App\Models\Vehicule;

class BeneficeController extends Controller
{
    /**
     * Display the benefices dashboard
     */
    public function index(Request $request)
    {
        // Définir les périodes
        $debutSemaine = now()->startOfWeek();
        $finSemaine = now()->endOfWeek();
        $debutMois = now()->startOfMonth();
        $finMois = now()->endOfMonth();
        $debutAnnee = now()->startOfYear();
        $finAnnee = now()->endOfYear();
        
        // Bénéfices par période en utilisant le modèle Benefice
        $beneficeThisWeek = Benefice::whereBetween('date', [$debutSemaine, $finSemaine])
            ->sum('montant');
            
        $beneficeThisMonth = Benefice::whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
            
        $beneficeThisYear = Benefice::whereBetween('date', [$debutAnnee, $finAnnee])
            ->sum('montant');
        
        // Revenus des locations par période
        $revenusLocationsThisWeek = Benefice::where('categorie', 'location')
            ->whereBetween('date', [$debutSemaine, $finSemaine])
            ->sum('montant');
            
        $revenusLocationsThisMonth = Benefice::where('categorie', 'location')
            ->whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
            
        $revenusLocationsThisYear = Benefice::where('categorie', 'location')
            ->whereBetween('date', [$debutAnnee, $finAnnee])
            ->sum('montant');
        
        // Revenus des ventes par période
        $revenusVentesThisWeek = Benefice::where('categorie', 'vente')
            ->whereBetween('date', [$debutSemaine, $finSemaine])
            ->sum('montant');
            
        $revenusVentesThisMonth = Benefice::where('categorie', 'vente')
            ->whereBetween('date', [$debutMois, $finMois])
            ->sum('montant');
            
        $revenusVentesThisYear = Benefice::where('categorie', 'vente')
            ->whereBetween('date', [$debutAnnee, $finAnnee])
            ->sum('montant');
        
        // Historique des bénéfices mensuels pour le graphique
        $historiqueData = [];
        for ($i = 0; $i < 12; $i++) {
            $moisDate = now()->subMonths($i);
            $debutMoisHisto = $moisDate->copy()->startOfMonth();
            $finMoisHisto = $moisDate->copy()->endOfMonth();
            
            $revenusLocations = Benefice::where('categorie', 'location')
                ->whereBetween('date', [$debutMoisHisto, $finMoisHisto])
                ->sum('montant');
                
            $revenusVentes = Benefice::where('categorie', 'vente')
                ->whereBetween('date', [$debutMoisHisto, $finMoisHisto])
                ->sum('montant');
                
            $benefice = $revenusLocations + $revenusVentes;
            
            $historiqueData[] = [
                'mois' => $moisDate->format('M Y'),
                'revenusLocations' => $revenusLocations,
                'revenusVentes' => $revenusVentes,
                'benefice' => $benefice
            ];
        }
        
        // Trier l'historique du plus ancien au plus récent
        $historiqueData = array_reverse($historiqueData);
        
        // Récupérer tous les bénéfices pour le tableau avec filtres et pagination
        $beneficesQuery = Benefice::query();
        
        // Filtre par catégorie
        if ($request->filled('categorie')) {
            $beneficesQuery->where('categorie', $request->categorie);
        }
        
        // Filtre par date
        if ($request->filled('date_debut')) {
            $beneficesQuery->where('date', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $beneficesQuery->where('date', '<=', $request->date_fin);
        }
        
        // Filtre par remarques
        if ($request->filled('remarques')) {
            $beneficesQuery->where('remarques', 'LIKE', '%' . $request->remarques . '%');
        }
        
        // Récupérer les résultats paginés et triés
        $benefices = $beneficesQuery->orderBy('date', 'desc')->paginate(15)->withQueryString();
        
        return view('admin.benefices', compact(
            'beneficeThisWeek', 
            'beneficeThisMonth', 
            'beneficeThisYear',
            'revenusLocationsThisWeek',
            'revenusVentesThisWeek',
            'revenusLocationsThisMonth',
            'revenusVentesThisMonth',
            'revenusLocationsThisYear',
            'revenusVentesThisYear',
            'historiqueData',
            'benefices'
        ));
    }

    /**
     * Store a new benefice record
     */
    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'categorie' => 'required|string',
            'remarques' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $benefice = new Benefice();
        $benefice->montant = $request->montant;
        $benefice->categorie = $request->categorie;
        $benefice->remarques = $request->remarques;
        $benefice->date = $request->date;
        $benefice->user_id = Auth::id();
        $benefice->save();

        return redirect()->route('admin.benefices')->with('success', 'Enregistrement effectué avec succès!');
    }

    /**
     * Get yearly benefice data for charts
     */
    public function getYearlyData()
    {
        // Définir la plage d'années à afficher (2025-2037)
        $startYear = 2025;
        $endYear = 2037;
        
        // Récupérer les données pour chaque année de la plage
        $years = [];
        $beneficesVente = [];
        $beneficesLocation = [];
        $beneficesTotaux = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            // Ajouter l'année à la liste
            $years[] = $year;
            
            // Récupérer les données réelles de la base de données pour cette année
            $revenusLocations = Benefice::where('categorie', 'location')
                ->whereYear('date', $year)
                ->sum('montant');
                
            $revenusVentes = Benefice::where('categorie', 'vente')
                ->whereYear('date', $year)
                ->sum('montant');
            
            // Ajouter les données aux tableaux
            $beneficesLocation[] = $revenusLocations;
            $beneficesVente[] = $revenusVentes;
            $beneficesTotaux[] = $revenusLocations + $revenusVentes;
        }
        
        return response()->json([
            'years' => $years,
            'beneficesVente' => $beneficesVente,
            'beneficesLocation' => $beneficesLocation,
            'beneficesTotaux' => $beneficesTotaux
        ]);
    }
    
    /**
     * Get monthly benefice data for charts
     */
    public function getMonthlyData(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];
        
        $beneficesVente = [];
        $beneficesLocation = [];
        $beneficesTotaux = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $revenusLocations = Benefice::where('categorie', 'location')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('montant');
                
            $revenusVentes = Benefice::where('categorie', 'vente')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('montant');
            
            $beneficesLocation[] = $revenusLocations;
            $beneficesVente[] = $revenusVentes;
            $beneficesTotaux[] = $revenusLocations + $revenusVentes;
        }
        
        return response()->json([
            'months' => $months,
            'beneficesVente' => $beneficesVente,
            'beneficesLocation' => $beneficesLocation,
            'beneficesTotaux' => $beneficesTotaux
        ]);
    }

    /**
     * Get weekly benefice data for charts
     */
    public function getWeeklyData(Request $request)
    {
        // Récupérer le premier jour du mois en cours
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        $currentMonth = $selectedDate->copy()->startOfMonth();
        
        // Identifier les semaines du mois
        $weeks = [];
        $beneficesVente = [];
        $beneficesLocation = [];
        $beneficesTotaux = [];
        
        // Générer les semaines du mois
        $firstWeekStart = $currentMonth->copy()->startOfMonth();
        $lastDayOfMonth = $currentMonth->copy()->endOfMonth();
        
        $weekNumber = 1;
        $weekStart = $firstWeekStart->copy();
        
        while ($weekStart->lte($lastDayOfMonth)) {
            $weekEnd = $weekStart->copy()->addDays(6)->min($lastDayOfMonth);
            
            $revenusLocations = Benefice::where('categorie', 'location')
                ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                ->sum('montant');
                
            $revenusVentes = Benefice::where('categorie', 'vente')
                ->whereBetween('date', [$weekStart->format('Y-m-d'), $weekEnd->format('Y-m-d')])
                ->sum('montant');
            
            $weeks[] = "Semaine $weekNumber";
            $beneficesLocation[] = $revenusLocations;
            $beneficesVente[] = $revenusVentes;
            $beneficesTotaux[] = $revenusLocations + $revenusVentes;
            
            $weekStart = $weekEnd->copy()->addDay();
            $weekNumber++;
        }
        
        return response()->json([
            'weeks' => $weeks,
            'beneficesVente' => $beneficesVente,
            'beneficesLocation' => $beneficesLocation,
            'beneficesTotaux' => $beneficesTotaux
        ]);
    }
    
    /**
     * Get daily benefice data for charts
     */
    public function getDailyData(Request $request)
    {
        // Récupérer le premier jour de la semaine en cours
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $beneficesVente = [];
        $beneficesLocation = [];
        $beneficesTotaux = [];
        
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            
            $revenusLocations = Benefice::where('categorie', 'location')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('montant');
                
            $revenusVentes = Benefice::where('categorie', 'vente')
                ->whereDate('date', $day->format('Y-m-d'))
                ->sum('montant');
            
            $beneficesLocation[] = $revenusLocations;
            $beneficesVente[] = $revenusVentes;
            $beneficesTotaux[] = $revenusLocations + $revenusVentes;
        }
        
        return response()->json([
            'days' => $days,
            'beneficesVente' => $beneficesVente,
            'beneficesLocation' => $beneficesLocation,
            'beneficesTotaux' => $beneficesTotaux
        ]);
    }

    /**
     * Get hourly benefice data for charts
     */
    public function getHourlyData()
    {
        // Générer les heures par intervalle de 2h
        $heures = [];
        $sommeGeneree = [];
        
        // Récupérer la date du jour
        $today = Carbon::today();
        
        // Récupérer les données pour chaque tranche de 2 heures
        for ($hour = 0; $hour < 24; $hour += 2) {
            $start = $today->copy()->addHours($hour);
            $end = $today->copy()->addHours($hour + 2);
            
            // Récupérer les bénéfices pour cette tranche horaire
            $beneficesHeure = Benefice::whereBetween('created_at', [$start, $end])
                ->sum('montant');
                
            // Si on est dans le futur (par rapport à l'heure actuelle), mettre à zéro
            if ($start->isAfter(now())) {
                $beneficesHeure = 0;
            }
            
            // Ajouter l'heure et le montant aux tableaux
            $heures[] = $hour . 'h00';
            $sommeGeneree[] = $beneficesHeure;
        }
        
        // Convertir en valeurs cumulatives pour montrer l'évolution de la somme totale
        $sommesCumulatives = [];
        $total = 0;
        foreach ($sommeGeneree as $montant) {
            $total += $montant;
            $sommesCumulatives[] = $total;
        }
        
        return response()->json([
            'heures' => $heures,
            'sommeGeneree' => $sommesCumulatives
        ]);
    }

    /**
     * Delete a benefice record
     */
    public function destroy($id)
    {
        $benefice = Benefice::findOrFail($id);
        $benefice->delete();

        $locationRequest = LocationRequest::where('reference_paiement', $benefice->remarques)->first();
        $locationRequest->statut = 'refusee';
        $locationRequest->save();

        $vehicule = Vehicule::where('id', $locationRequest->vehicule_id)->first();
        $vehicule->disponibilite = true;
        $vehicule->save();

        
        return redirect()->route('admin.benefices')->with('success', 'Bénéfice supprimé avec succès!');
    }
} 