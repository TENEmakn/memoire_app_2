<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Benefice;
use App\Models\Depense;
use Carbon\Carbon;

class StatsController extends Controller
{
    /**
     * Récupère les données statistiques annuelles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnnuelData()
    {
        // Définir la plage d'années (2025 à 2037)
        $startYear = 2025;
        $endYear = 2037;
        $currentYear = now()->year;
        
        $labels = [];
        $depensesData = [];
        $beneficesData = [];
        $bilanData = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $labels[] = (string)$year;
            
            // Pour les années passées et l'année en cours, utiliser des données réelles
            if ($year <= $currentYear) {
                $depenses = Depense::whereYear('date', $year)->sum('montant');
                $benefices = Benefice::whereYear('date', $year)->sum('montant');
            } else {
                // Pour les années futures, utiliser des projections ou des valeurs nulles
                // À remplacer par votre logique de projection si nécessaire
                $depenses = 0;
                $benefices = 0;
            }
            
            $bilan = $benefices - $depenses;
            
            $depensesData[] = $depenses;
            $beneficesData[] = $benefices;
            $bilanData[] = $bilan;
        }
        
        return response()->json([
            'labels' => $labels,
            'depenses' => $depensesData,
            'benefices' => $beneficesData,
            'bilan' => $bilanData,
            'xAxisLabel' => 'Années'
        ]);
    }
    
    /**
     * Récupère les données statistiques mensuelles pour l'année en cours
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMensuelData()
    {
        $currentYear = now()->year;
        $months = [
            'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin',
            'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'
        ];
        
        $depensesData = [];
        $beneficesData = [];
        $bilanData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::createFromDate($currentYear, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            
            // Récupérer les données réelles jusqu'au mois actuel
            if ($startDate <= now()) {
                $depenses = Depense::whereBetween('date', [$startDate, $endDate])->sum('montant');
                $benefices = Benefice::whereBetween('date', [$startDate, $endDate])->sum('montant');
            } else {
                // Pour les mois futurs, utiliser des projections ou des valeurs nulles
                $depenses = 0;
                $benefices = 0;
            }
            
            $bilan = $benefices - $depenses;
            
            $depensesData[] = $depenses;
            $beneficesData[] = $benefices;
            $bilanData[] = $bilan;
        }
        
        return response()->json([
            'labels' => $months,
            'depenses' => $depensesData,
            'benefices' => $beneficesData,
            'bilan' => $bilanData,
            'xAxisLabel' => 'Mois de l\'année en cours'
        ]);
    }
    
    /**
     * Récupère les données statistiques hebdomadaires pour le mois en cours
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHebdomadaireData()
    {
        try {
            $now = Carbon::now();
            $currentMonth = $now->month;
            $currentYear = $now->year;
            
            // Déterminer le premier et dernier jour du mois actuel
            $firstDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1);
            $lastDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth();
            
            \Log::info("Mois actuel: " . $firstDayOfMonth->format('Y-m'));
            \Log::info("Premier jour: " . $firstDayOfMonth->format('Y-m-d'));
            \Log::info("Dernier jour: " . $lastDayOfMonth->format('Y-m-d'));
            
            // Préparer les données des semaines
            $labels = [];
            $depensesData = [];
            $beneficesData = [];
            $bilanData = [];
            
            // Créer 5 semaines (maximum possible dans un mois)
            $weeks = [];
            
            // Première semaine
            $week1Start = $firstDayOfMonth->copy();
            $week1End = min($week1Start->copy()->endOfWeek(), $lastDayOfMonth);
            $weeks[] = [
                'start' => $week1Start,
                'end' => $week1End,
                'label' => 'Semaine 1'
            ];
            
            // Deuxième semaine
            if ($week1End->copy()->addDay()->lte($lastDayOfMonth)) {
                $week2Start = $week1End->copy()->addDay();
                $week2End = min($week2Start->copy()->endOfWeek(), $lastDayOfMonth);
                $weeks[] = [
                    'start' => $week2Start,
                    'end' => $week2End,
                    'label' => 'Semaine 2'
                ];
                
                // Troisième semaine
                if ($week2End->copy()->addDay()->lte($lastDayOfMonth)) {
                    $week3Start = $week2End->copy()->addDay();
                    $week3End = min($week3Start->copy()->endOfWeek(), $lastDayOfMonth);
                    $weeks[] = [
                        'start' => $week3Start,
                        'end' => $week3End,
                        'label' => 'Semaine 3'
                    ];
                    
                    // Quatrième semaine
                    if ($week3End->copy()->addDay()->lte($lastDayOfMonth)) {
                        $week4Start = $week3End->copy()->addDay();
                        $week4End = min($week4Start->copy()->endOfWeek(), $lastDayOfMonth);
                        $weeks[] = [
                            'start' => $week4Start,
                            'end' => $week4End,
                            'label' => 'Semaine 4'
                        ];
                        
                        // Cinquième semaine (si nécessaire)
                        if ($week4End->copy()->addDay()->lte($lastDayOfMonth)) {
                            $week5Start = $week4End->copy()->addDay();
                            $week5End = $lastDayOfMonth;
                            $weeks[] = [
                                'start' => $week5Start,
                                'end' => $week5End,
                                'label' => 'Semaine 5'
                            ];
                        }
                    }
                }
            }
            
            \Log::info("Nombre de semaines générées: " . count($weeks));
            
            // Récupérer les données pour chaque semaine
            foreach ($weeks as $week) {
                \Log::info("Semaine: " . $week['label'] . " du " . $week['start']->format('Y-m-d') . " au " . $week['end']->format('Y-m-d'));
                
                $labels[] = $week['label'];
                
                // Récupérer les données de dépenses
                $depenses = Depense::whereBetween('date', [
                    $week['start']->format('Y-m-d'), 
                    $week['end']->format('Y-m-d')
                ])->sum('montant');
                
                // Récupérer les données de bénéfices
                $benefices = Benefice::whereBetween('date', [
                    $week['start']->format('Y-m-d'), 
                    $week['end']->format('Y-m-d')
                ])->sum('montant');
                
                \Log::info("Dépenses: $depenses, Bénéfices: $benefices");
                
                $bilan = $benefices - $depenses;
                
                $depensesData[] = floatval($depenses);
                $beneficesData[] = floatval($benefices);
                $bilanData[] = floatval($bilan);
            }
            
            // Si aucune semaine n'a été trouvée, créer des données par défaut
            if (empty($labels)) {
                $labels = ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4', 'Semaine 5'];
                $depensesData = [0, 0, 0, 0, 0];
                $beneficesData = [0, 0, 0, 0, 0];
                $bilanData = [0, 0, 0, 0, 0];
            }
            
            $result = [
                'labels' => $labels,
                'depenses' => $depensesData,
                'benefices' => $beneficesData,
                'bilan' => $bilanData,
                'xAxisLabel' => 'Semaines du mois en cours'
            ];
            
            \Log::info("Réponse hebdomadaire: " . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error("Exception dans getHebdomadaireData: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // En cas d'erreur, renvoyer des données par défaut
            return response()->json([
                'labels' => ['Semaine 1', 'Semaine 2', 'Semaine 3', 'Semaine 4', 'Semaine 5'],
                'depenses' => [0, 0, 0, 0, 0],
                'benefices' => [0, 0, 0, 0, 0],
                'bilan' => [0, 0, 0, 0, 0],
                'xAxisLabel' => 'Semaines du mois en cours'
            ]);
        }
    }
    
    /**
     * Récupère les données statistiques journalières pour la semaine en cours
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJournalierData()
    {
        try {
            $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
            $startOfWeek = now()->startOfWeek();  // Lundi
            
            \Log::info("Début de la semaine: " . $startOfWeek->format('Y-m-d'));
            
            $depensesData = [];
            $beneficesData = [];
            $bilanData = [];
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                \Log::info("Traitement du jour " . $daysOfWeek[$i] . ": " . $date->format('Y-m-d'));
                
                // Récupérer les données de dépenses pour ce jour
                $depenses = Depense::whereDate('date', $date->format('Y-m-d'))->sum('montant');
                
                // Récupérer les données de bénéfices pour ce jour
                $benefices = Benefice::whereDate('date', $date->format('Y-m-d'))->sum('montant');
                
                \Log::info("Dépenses: $depenses, Bénéfices: $benefices");
                
                $bilan = $benefices - $depenses;
                
                $depensesData[] = floatval($depenses);
                $beneficesData[] = floatval($benefices);
                $bilanData[] = floatval($bilan);
            }
            
            $result = [
                'labels' => $daysOfWeek,
                'depenses' => $depensesData,
                'benefices' => $beneficesData,
                'bilan' => $bilanData,
                'xAxisLabel' => 'Jours de la semaine en cours'
            ];
            
            \Log::info("Réponse journalière: " . json_encode($result));
            
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error("Exception dans getJournalierData: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // En cas d'erreur, renvoyer des données par défaut
            return response()->json([
                'labels' => ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
                'depenses' => [0, 0, 0, 0, 0, 0, 0],
                'benefices' => [0, 0, 0, 0, 0, 0, 0],
                'bilan' => [0, 0, 0, 0, 0, 0, 0],
                'xAxisLabel' => 'Jours de la semaine en cours'
            ]);
        }
    }
    
    /**
     * Router qui redirige vers la méthode appropriée en fonction de la période
     * 
     * @param string $period Période (annuel, mensuel, hebdomadaire, journalier)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatData($period)
    {
        \Log::info("Période demandée: $period");
        
        switch ($period) {
            case 'annuel':
                return $this->getAnnuelData();
            case 'mensuel':
                return $this->getMensuelData();
            case 'hebdomadaire':
                \Log::info("Appel de la méthode getHebdomadaireData");
                try {
                    $result = $this->getHebdomadaireData();
                    \Log::info("Résultat hebdomadaire: " . json_encode($result->getData()));
                    return $result;
                } catch (\Exception $e) {
                    \Log::error("Erreur dans getHebdomadaireData: " . $e->getMessage());
                    throw $e;
                }
            case 'journalier':
                return $this->getJournalierData();
            default:
                \Log::warning("Période inconnue: $period, utilisation de la période annuelle par défaut");
                return $this->getAnnuelData();
        }
    }
} 