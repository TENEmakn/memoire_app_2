<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepenseController extends Controller
{
    /**
     * Store a newly created expense in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'categorieDepense' => 'required|string',
            'montantDepense' => 'required|numeric|min:0',
            'motifDepense' => 'required|string',
            'dateDepense' => 'required|date',
        ]);

        // Create a new expense
        $depense = new Depense();
        $depense->categorie = $request->categorieDepense;
        $depense->montant = $request->montantDepense;
        $depense->motif = $request->motifDepense;
        $depense->date = $request->dateDepense;
        $depense->user_id = Auth::id(); // Save the current user ID
        $depense->save();

        return redirect()->route('admin.depense')->with('success', 'Dépense enregistrée avec succès!');
    }

    /**
     * Update an existing expense in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Depense $depense)
    {
        // Validate the request data
        $request->validate([
            'categorieDepense' => 'required|string',
            'montantDepense' => 'required|numeric|min:0',
            'motifDepense' => 'required|string',
            'dateDepense' => 'required|date',
        ]);

        // Update the expense
        $depense->categorie = $request->categorieDepense;
        $depense->montant = $request->montantDepense;
        $depense->motif = $request->motifDepense;
        $depense->date = $request->dateDepense;
        $depense->save();

        return redirect()->route('admin.depense')->with('success', 'Dépense mise à jour avec succès!');
    }

    /**
     * Remove the specified expense from the database.
     *
     * @param  \App\Models\Depense  $depense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depense $depense)
    {
        $depense->delete();
        return redirect()->route('admin.depense')->with('success', 'Dépense supprimée avec succès!');
    }

    /**
     * Get yearly expense data from 2025 to current year + 12
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getYearlyData()
    {
        $currentYear = (int)date('Y');
        $startYear = max(2025, $currentYear - 2); // Starting from at least 2025 or 2 years back
        $endYear = $currentYear + 12; // 12 years into the future
        $years = range($startYear, $endYear);
        
        // Récupérer les données pour chaque catégorie
        $operationnelData = $this->getYearlyExpensesByCategory('operationnelle', $startYear, $endYear);
        $fonctionnelleData = $this->getYearlyExpensesByCategory('fonctionnelle', $startYear, $endYear);
        $strategiqueData = $this->getYearlyExpensesByCategory('strategique', $startYear, $endYear);
        
        // Calculer le total pour chaque année
        $totalData = [];
        foreach ($years as $index => $year) {
            $totalData[] = $operationnelData[$index] + $fonctionnelleData[$index] + $strategiqueData[$index];
        }
        
        // Formater les données pour Chart.js
        $data = [
            'labels' => $years,
            'datasets' => [
                [
                    'label' => 'Dépenses opérationnelles',
                    'data' => $operationnelData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses fonctionnelles',
                    'data' => $fonctionnelleData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses stratégiques',
                    'data' => $strategiqueData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Total des dépenses',
                    'data' => $totalData,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ]
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Helper function to get yearly expenses by category
     */
    private function getYearlyExpensesByCategory($category, $startYear, $endYear)
    {
        $data = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $amount = Depense::where('categorie', $category)
                ->whereYear('date', $year)
                ->sum('montant');
            $data[] = $amount;
        }
        return $data;
    }

    /**
     * Get monthly expense data for the current year
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlyData()
    {
        $currentYear = date('Y');
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];
        
        // Récupérer les données pour chaque catégorie
        $operationnelData = $this->getMonthlyExpensesByCategory('operationnelle', $currentYear);
        $fonctionnelleData = $this->getMonthlyExpensesByCategory('fonctionnelle', $currentYear);
        $strategiqueData = $this->getMonthlyExpensesByCategory('strategique', $currentYear);
        
        // Calculer le total pour chaque mois
        $totalData = [];
        for ($i = 0; $i < 12; $i++) {
            $totalData[] = $operationnelData[$i] + $fonctionnelleData[$i] + $strategiqueData[$i];
        }
        
        // Formater les données pour Chart.js
        $data = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Dépenses opérationnelles',
                    'data' => $operationnelData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses fonctionnelles',
                    'data' => $fonctionnelleData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses stratégiques',
                    'data' => $strategiqueData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Total des dépenses',
                    'data' => $totalData,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ]
            ]
        ];
        
        return response()->json($data);
    }
    
    /**
     * Helper function to get monthly expenses by category
     */
    private function getMonthlyExpensesByCategory($category, $year)
    {
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $amount = Depense::where('categorie', $category)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('montant');
            $data[] = $amount;
        }
        return $data;
    }

    /**
     * Get weekly expense data for the current month
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeeklyData()
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        
        // Récupérer le premier et dernier jour du mois
        $startOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $endOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth();
        
        // Déterminer toutes les semaines du mois
        $weeks = [];
        $currentDate = $startOfMonth->copy();
        
        while ($currentDate->lte($endOfMonth)) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->addDays(6)->min($endOfMonth);
            
            $weeks[] = [
                'start' => $weekStart->format('Y-m-d'),
                'end' => $weekEnd->format('Y-m-d'),
                'label' => 'Du ' . $weekStart->format('d/m') . ' au ' . $weekEnd->format('d/m')
            ];
            
            $currentDate->addDays(7);
        }
        
        $labels = [];
        $operationnelData = [];
        $fonctionnelleData = [];
        $strategiqueData = [];
        $totalData = [];

        foreach ($weeks as $week) {
            $labels[] = $week['label'];
            
            // Récupérer les données pour chaque catégorie
            $operationnelAmount = Depense::where('categorie', 'operationnelle')
                ->whereBetween('date', [$week['start'], $week['end']])
                ->sum('montant');
            
            $fonctionnelleAmount = Depense::where('categorie', 'fonctionnelle')
                ->whereBetween('date', [$week['start'], $week['end']])
                ->sum('montant');
            
            $strategiqueAmount = Depense::where('categorie', 'strategique')
                ->whereBetween('date', [$week['start'], $week['end']])
                ->sum('montant');
            
            $operationnelData[] = $operationnelAmount;
            $fonctionnelleData[] = $fonctionnelleAmount;
            $strategiqueData[] = $strategiqueAmount;
            $totalData[] = $operationnelAmount + $fonctionnelleAmount + $strategiqueAmount;
        }

        // Formater les données pour Chart.js
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Dépenses opérationnelles',
                    'data' => $operationnelData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses fonctionnelles',
                    'data' => $fonctionnelleData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses stratégiques',
                    'data' => $strategiqueData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Total des dépenses',
                    'data' => $totalData,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ]
            ]
        ];
        
        return response()->json($data);
    }

    /**
     * Get daily expense data for the current week
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDailyData()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $labels = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $operationnelData = [];
        $fonctionnelleData = [];
        $strategiqueData = [];
        $totalData = [];

        for ($day = 0; $day < 7; $day++) {
            $date = (clone $startOfWeek)->addDays($day);
            
            // Récupérer les données pour chaque catégorie
            $operationnelAmount = Depense::where('categorie', 'operationnelle')
                ->whereDate('date', $date->toDateString())
                ->sum('montant');
            
            $fonctionnelleAmount = Depense::where('categorie', 'fonctionnelle')
                ->whereDate('date', $date->toDateString())
                ->sum('montant');
            
            $strategiqueAmount = Depense::where('categorie', 'strategique')
                ->whereDate('date', $date->toDateString())
                ->sum('montant');
            
            $operationnelData[] = $operationnelAmount;
            $fonctionnelleData[] = $fonctionnelleAmount;
            $strategiqueData[] = $strategiqueAmount;
            $totalData[] = $operationnelAmount + $fonctionnelleAmount + $strategiqueAmount;
        }

        // Formater les données pour Chart.js
        $data = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Dépenses opérationnelles',
                    'data' => $operationnelData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses fonctionnelles',
                    'data' => $fonctionnelleData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Dépenses stratégiques',
                    'data' => $strategiqueData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ],
                [
                    'label' => 'Total des dépenses',
                    'data' => $totalData,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => false
                ]
            ]
        ];
        
        return response()->json($data);
    }
} 