<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Letter;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $year = Carbon::now()->year;
        
        $formattedData = $this->getLettersPerMonth();
        $divisionData = $this->getLettersPerDivision();
        $performanceData = $this->getPerformance();
        $usersPerDivisionData = $this->getUsersPerDivision();

        return view('home', compact('formattedData', 'divisionData', 'year', 'performanceData', 'usersPerDivisionData'));
    }

    private function getLettersPerMonth()
    {
        $lettersPerMonth = Letter::select(DB::raw('COUNT(*) as count'), DB::raw("MONTH(created_at) as month"))
        ->groupBy('month')
        ->whereYear('created_at', Carbon::now()->year)
        ->get();

        $formattedData = array_fill(1, 12, 0);
    
        foreach ($lettersPerMonth as $data) {
            $formattedData[(int)$data->month] = $data->count;
        }
    
        return $formattedData;
    }

    private function getLettersPerDivision()
    {
        $year = Carbon::now()->year; // Default ke tahun sekarang jika tidak ada parameter tahun

        $lettersPerDivision = Letter::select(DB::raw('COUNT(*) as count'), 'divisions.name as division')
            ->join('users', 'letters.user_id', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('divisions', 'user_details.division_id', '=', 'divisions.id')
            ->whereYear('letters.created_at', $year) // Menyertakan kondisi tahun
            ->groupBy('divisions.name')
            ->get();

        // Format data untuk digunakan di Chart.js
        $divisionData = [
            'divisions' => $lettersPerDivision->pluck('division'),
            'counts' => $lettersPerDivision->pluck('count'),
            'year' => $year // Menyertakan tahun dalam data yang dikembalikan
        ];

        return $divisionData;
    }

    private function getPerformance()
    {
        $oneWeekAgo = now()->subWeek();

        $performancePerTime = DB::table('performance_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('AVG(response_time) as avg_response_time'))
            ->where('created_at', '>=', $oneWeekAgo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $performanceData = [
            'dates' => $performancePerTime->pluck('date'),
            'responseTimes' => $performancePerTime->pluck('avg_response_time'),
        ];

        return $performanceData;
    }

    private function getUsersPerDivision()
    {
        $usersPerDivision = User::select(DB::raw('COUNT(users.id) as count'), 'divisions.name as division')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('divisions', 'user_details.division_id', '=', 'divisions.id')
            ->groupBy('divisions.name')
            ->get();

        // Format data untuk digunakan di Chart.js
        $divisionData = [
            'divisions' => $usersPerDivision->pluck('division'),
            'counts' => $usersPerDivision->pluck('count')
        ];

        return $divisionData;
    }
}