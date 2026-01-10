<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pentest;
use App\Models\User;
use App\Models\Views\User as ViewsUser;
use App\Models\Views\Visit;
use App\Models\Views\VisitYesterday;
use App\Models\Vulnerability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $visits = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%.js%')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($visits)
                ->addColumn('time', function ($row) {
                    return date(('H:i:s'), strtotime($row->created_at));
                })
                ->addIndexColumn()
                ->rawColumns(['time'])
                ->make(true);
        }

        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        /** Pentest Statistics */
        $pentestStats = $this->pentestStatistics();

        return view('admin.home.index', compact(
            'onlineUsers',
            'percent',
            'access',
            'chart',
            'pentestStats',
        ));
    }

    public function chart(): JsonResponse
    {
        /** Statistics */
        $statistics = $this->accessStatistics();
        $onlineUsers = $statistics['onlineUsers'];
        $percent = $statistics['percent'];
        $access = $statistics['access'];
        $chart = $statistics['chart'];

        return response()->json([
            'onlineUsers' => $onlineUsers,
            'access' => $access,
            'percent' => $percent,
            'chart' => $chart,
        ]);
    }

    private function accessStatistics(): array
    {
        $onlineUsers = User::online()->count();

        $accessToday = Visit::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%.js%')
            ->where('method', 'GET')
            ->get();
        $accessYesterday = VisitYesterday::where('url', '!=', route('admin.home.chart'))
            ->where('url', 'NOT LIKE', '%columns%')
            ->where('url', 'NOT LIKE', '%storage%')
            ->where('url', 'NOT LIKE', '%offline%')
            ->where('url', 'NOT LIKE', '%manifest.json%')
            ->where('url', 'NOT LIKE', '%.png%')
            ->where('url', 'NOT LIKE', '%.js%')
            ->where('method', 'GET')
            ->count();

        $totalDaily = $accessToday->count();

        $percent = 0;
        if ($accessYesterday > 0 && $totalDaily > 0) {
            $percent = number_format((($totalDaily - $accessYesterday) / $totalDaily * 100), 2, ',', '.');
        }

        /** Visitor Chart */
        $data = $accessToday->groupBy(function ($reg) {
            return date('H', strtotime($reg->created_at));
        });

        $dataList = [];
        foreach ($data as $key => $value) {
            $dataList[$key.'H'] = count($value);
        }

        $chart = new stdClass;
        $chart->labels = (array_keys($dataList));
        $chart->dataset = (array_values($dataList));

        return [
            'onlineUsers' => $onlineUsers,
            'access' => $totalDaily,
            'percent' => $percent,
            'chart' => $chart,
        ];
    }

    private function pentestStatistics(): array
    {
        $currentYear = date('Y');

        // Pentests do ano corrente
        $pentestsThisYear = Pentest::where('year', $currentYear)->get();
        $totalPentestsYear = $pentestsThisYear->count();

        // Status dos pentests do ano
        $finalized = $pentestsThisYear->whereNotNull('completion_date')->count();
        $inProgress = $pentestsThisYear->whereNull('completion_date')->whereNotNull('start_date')->count();
        $pending = $pentestsThisYear->whereNull('start_date')->count();
        $delayed = $pentestsThisYear->filter(function ($p) {
            return !$p->completion_date && $p->deadline && $p->deadline < now();
        })->count();

        // Percentuais de status
        $finalizedPercent = $totalPentestsYear > 0 ? round(($finalized / $totalPentestsYear) * 100, 1) : 0;
        $inProgressPercent = $totalPentestsYear > 0 ? round(($inProgress / $totalPentestsYear) * 100, 1) : 0;
        $pendingPercent = $totalPentestsYear > 0 ? round(($pending / $totalPentestsYear) * 100, 1) : 0;
        $delayedPercent = $totalPentestsYear > 0 ? round(($delayed / $totalPentestsYear) * 100, 1) : 0;

        // Vulnerabilidades do ano
        $pentestIds = $pentestsThisYear->pluck('id');
        $vulnerabilities = Vulnerability::whereIn('pentest_id', $pentestIds)->get();
        $totalVulnerabilities = $vulnerabilities->count();

        // Vulnerabilidades por criticidade
        $critical = $vulnerabilities->where('criticality', 'critical')->count();
        $high = $vulnerabilities->where('criticality', 'high')->count();
        $medium = $vulnerabilities->where('criticality', 'medium')->count();
        $low = $vulnerabilities->where('criticality', 'low')->count();
        $informative = $vulnerabilities->where('criticality', 'informative')->count();

        // Percentuais por criticidade
        $criticalPercent = $totalVulnerabilities > 0 ? round(($critical / $totalVulnerabilities) * 100, 1) : 0;
        $highPercent = $totalVulnerabilities > 0 ? round(($high / $totalVulnerabilities) * 100, 1) : 0;
        $mediumPercent = $totalVulnerabilities > 0 ? round(($medium / $totalVulnerabilities) * 100, 1) : 0;
        $lowPercent = $totalVulnerabilities > 0 ? round(($low / $totalVulnerabilities) * 100, 1) : 0;
        $informativePercent = $totalVulnerabilities > 0 ? round(($informative / $totalVulnerabilities) * 100, 1) : 0;

        // Vulnerabilidades sanadas
        $resolved = $vulnerabilities->whereNotNull('resolved_at')->count();
        $unresolved = $totalVulnerabilities - $resolved;
        $resolvedPercent = $totalVulnerabilities > 0 ? round(($resolved / $totalVulnerabilities) * 100, 1) : 0;

        // Prioridades dos pentests do ano
        $urgent = $pentestsThisYear->where('priority', 'urgent')->count();
        $highPriority = $pentestsThisYear->where('priority', 'high')->count();
        $mediumPriority = $pentestsThisYear->where('priority', 'medium')->count();
        $lowPriority = $pentestsThisYear->where('priority', 'low')->count();

        return [
            'currentYear' => $currentYear,
            'totalPentestsYear' => $totalPentestsYear,
            'finalized' => $finalized,
            'finalizedPercent' => $finalizedPercent,
            'inProgress' => $inProgress,
            'inProgressPercent' => $inProgressPercent,
            'pending' => $pending,
            'pendingPercent' => $pendingPercent,
            'delayed' => $delayed,
            'delayedPercent' => $delayedPercent,
            'totalVulnerabilities' => $totalVulnerabilities,
            'critical' => $critical,
            'criticalPercent' => $criticalPercent,
            'high' => $high,
            'highPercent' => $highPercent,
            'medium' => $medium,
            'mediumPercent' => $mediumPercent,
            'low' => $low,
            'lowPercent' => $lowPercent,
            'informative' => $informative,
            'informativePercent' => $informativePercent,
            'resolved' => $resolved,
            'resolvedPercent' => $resolvedPercent,
            'unresolved' => $unresolved,
            'urgent' => $urgent,
            'highPriority' => $highPriority,
            'mediumPriority' => $mediumPriority,
            'lowPriority' => $lowPriority,
        ];
    }
}
