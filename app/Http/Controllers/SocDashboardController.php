<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;



class SocDashboardController extends Controller
{
    public function index()
    {
        $search = request('search');
        $filterMethod = request('filter_method');
        $startDate = request('start_date');
        $endDate = request('end_date');

        $path = storage_path('logs/laravel.log');
        if (!File::exists($path)) {
            return view('dashboard.logs.index', ['logs' => []]);
        }

        $lines = File::lines($path);
        $logs = [];

        foreach ($lines as $line) {
    if (str_contains($line, 'Azione')) {
        preg_match('/^\[(.*?)\] .*?Azione (.*)$/', $line, $matches);
        if (!$matches) continue;

        $timestamp = $matches[1];
        $azione = $matches[2];

        // Trova l'inizio della parte JSON
        $jsonStart = strpos($azione, '{');
        if ($jsonStart === false) continue; // se non trova JSON salta

        $json = substr($azione, $jsonStart);
        $data = json_decode($json, true);

        if (!$data || !isset($data['user_id'])) continue;

        // Il resto del codice rimane uguale
        $user = DB::table('users')->where('id', $data['user_id'])->first();
        if ($user) {
            $data['email'] = $user->email;
            $data['name'] = $user->name;
        } else {
            $data['name'] = 'N/A';
            $data['email'] = 'N/A';
        }

        $log = [
            'id' => $data['user_id'] ?? 'N/A',
            'username' => $data['name'] ?? 'N/A',
            'email' => $data['email'] ?? 'N/A',
            'ip' => $data['ip'] ?? '',
            'url' => $data['url'] ?? '',
            'method' => $data['method'] ?? '',
            'time' => $timestamp,
        ];

        // Filtri come prima
        $logTime = strtotime($log['time']);
        $start = $startDate ? strtotime($startDate . ' 00:00:00') : null;
        $end = $endDate ? strtotime($endDate . ' 23:59:59') : null;

        if (
            ($search && !str_contains(strtolower(json_encode($log)), strtolower($search))) ||
            ($filterMethod && $log['method'] !== $filterMethod) ||
            ($start && $logTime < $start) ||
            ($end && $logTime > $end)
        ) {
            continue;
        }

        $logs[] = $log;
    }
}

        $logs = array_reverse($logs);

        // Paginazione manuale
        $page = Paginator::resolveCurrentPage();
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $paginatedLogs = new LengthAwarePaginator(
            array_slice($logs, $offset, $perPage),
            count($logs),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath(), 'query' => request()->query()]
        );

        return view('socdashboard.dashboard', [
            'logs' => $paginatedLogs,
            'search' => $search,
            'filterMethod' => $filterMethod,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function statistiche()
    {
        $path = storage_path('logs/laravel.log');
        if (!File::exists($path)) {
            return view('socdashboard.statistiche', ['stats' => []]);
        }

        $lines = File::lines($path);
        $logs = [];

        foreach ($lines as $line) {
            if (str_contains($line, 'Interazione utente')) {
                preg_match('/^\[(.*?)\] .*?Interazione utente (.*)$/', $line, $matches);
                if (!$matches) continue;

                $timestamp = $matches[1];
                $json = $matches[2];
                $data = json_decode($json, true);
                if (!$data || !isset($data['user_id'])) continue;
                
                $user = DB::table('utente')->where('id', $data['user_id'])->first();

                if($data['url']=="http://localhost:8000/log-action"){
                    $url = $data['payload']['url'] ?? 'N/A';
                }else{
                    $url = $data['url'] ?? 'N/A';
                }
                
                
                if (str_contains($url, '?')) {
                    $url = substr($url, 0, strpos($url, '?'));
                }

                //remove from the url http://localhost:8000/
                if (str_contains($url, 'http://localhost:8000/')) {
                    $url = str_replace('http://localhost:8000/', '', $url);
                }

                if ($user) {
                    $data['email'] = $user->email;
                    $data['username'] = $user->username;
                    $data['ruolo'] = $user->ruolo;
                    $data['url'] = $url ?? 'N/A';
                } else {
                    $data['username'] = 'N/A';
                    $data['email'] = 'N/A';
                    $data['ruolo'] = 'N/A';
                    $data['url'] = 'N/A';
                }
                $log = [
                    'time' => $timestamp,
                    'method' => $data['method'] ?? '',
                    'ruolo' => DB::table('utente_ruolo')->where('id', $data['ruolo'])->value('nome') ?? 'N/A',
                    'user_id' => $data['user_id'],
                    'url' => $data['url']
                ];
                $logs[] = $log;
            }
        }

        $stats = [
            'totali' => count($logs),
            'per_ruolo' => collect($logs)->groupBy('ruolo')->map->count(),
            'per_metodo' => collect($logs)->groupBy('method')->map->count(),
            'per_giorno' => collect($logs)->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['time'])->format('Y-m-d');
            })->map->count(),
            'top_utenti' => collect($logs)->groupBy('user_id')->map->count()->sortDesc()->take(5),
            'pagine_visitate' => collect($logs)
                ->filter(fn($log) => $log['url'] !== 'N/A')
                ->filter(fn($log) => $log['url'] !== 'dashboard/logs')
                ->filter(fn($log) => $log['url'] !== 'dashboard/errori')
                ->filter(fn($log) => $log['url'] !== 'dashboard/statistiche')
                ->filter(fn($log) => $log['url'] !== 'css/bootstrap.min.css')
                ->filter(fn($log) => $log['url'] !== 'css/bootstrap.min.css.map')
                ->filter(fn($log) => $log['url'] !== 'eventimop.promomediaweb.it/test/tabelle/assets/img/favicon.ico')
                ->filter(fn($log) => $log['url'] !== 'eventimop.promomediaweb.it/test/assets/js/base/search.js')
                ->filter(fn($log) => $log['url'] !== 'get-files-json')
                ->filter(fn($log) => $log['url'] !== 'http://localhost:8000')
                ->groupBy('url')
                ->map->count()
                ->sortDesc()
        ];

        // Estrai solo i log di errore
        $errorLogs = collect($lines)->filter(function ($line) {
            return preg_match('/^\[(.*?)\] (\w+)\.(ERROR|CRITICAL|WARNING): (.*)$/', $line);
        })->map(function ($line) {
            preg_match('/^\[(.*?)\] (\w+)\.(ERROR|CRITICAL|WARNING): (.*)$/', $line, $matches);
            $timestamp = $matches[1];
            $level = $matches[3];
            $messageFull = $matches[4];

            $pos = strpos($messageFull, '{');
            $nome = $pos !== false ? trim(substr($messageFull, 0, $pos)) : $messageFull;
            $nome = strtok($nome, "\n");

            return [
                'timestamp' => $timestamp,
                'level' => $level,
                'nome' => $nome
            ];
        });

        $statisticheErrori = [
            'per_nome' => $errorLogs->groupBy('nome')->map->count()->sortDesc()->take(10),
            'per_data' => $errorLogs->groupBy(function ($log) {
                return Carbon::parse($log['timestamp'])->format('Y-m-d');
            })->map->count()->sortDesc(),
        ];

        return view('socdashboard.statistiche', [
            'stats' => $stats,
            'statisticheErrori' => $statisticheErrori,
        ]);
    }


public function errori()
{
    $path = storage_path('logs/laravel.log');
    if (!File::exists($path)) {
        return view('socdashboard.errori', ['logs' => []]);
    }

    $levelFilter = request()->get('level');
    $search = request()->get('search');
    $dataInizio = request()->get('data_inizio');
    $dataFine = request()->get('data_fine');

    $lines = File::lines($path);
    $logs = [];
    $current = [];

    foreach ($lines as $line) {
        if (preg_match('/^\[(.*?)\] (\w+)\.(ERROR|CRITICAL|WARNING): (.*)$/', $line, $matches)) {
            if (!empty($current)) {
                $logs[] = $current;
                $current = [];
            }

            $fullMessage = $matches[4];
            $pos = strpos($fullMessage, '{');
            $message = $fullMessage;
            $nome = $fullMessage;
            if ($pos !== false) {
                $message = trim(substr($fullMessage, $pos));
                $nome = trim(substr($fullMessage, 0, $pos));
            }
            $nome = strtok($nome, "\n");

            $current = [
                'timestamp' => $matches[1],
                'level' => $matches[3],
                'nome' => $nome,
                'message' => $message,
                'stack' => ''
            ];
        } elseif (!empty($current)) {
            $current['stack'] .= $line . "\n";
        }
    }

    if (!empty($current)) {
        $logs[] = $current;
    }

    $logs = array_reverse($logs);

    if ($levelFilter) {
        $logs = array_filter($logs, fn($log) => $log['level'] === $levelFilter);
    }

    if ($search) {
        $logs = array_filter($logs, function ($log) use ($search) {
            return stripos($log['nome'], $search) !== false || stripos($log['message'], $search) !== false;
        });
    }

    if ($dataInizio) {
        $logs = array_filter($logs, function ($log) use ($dataInizio) {
            return Carbon::parse($log['timestamp']) >= Carbon::parse($dataInizio);
        });
    }

    if ($dataFine) {
        $logs = array_filter($logs, function ($log) use ($dataFine) {
            return Carbon::parse($log['timestamp']) <= Carbon::parse($dataFine)->endOfDay();
        });
    }

    $page = Paginator::resolveCurrentPage();
    $perPage = 10;
    $offset = ($page - 1) * $perPage;

    $paginatedLogs = new LengthAwarePaginator(
        array_slice($logs, $offset, $perPage),
        count($logs),
        $perPage,
        $page,
        ['path' => Paginator::resolveCurrentPath(), 'query' => request()->query()]
    );

    return view('socdashboard.errori', [
        'logs' => $paginatedLogs,
        'level' => $levelFilter,
        'search' => $search,
        'data_inizio' => $dataInizio,
        'data_fine' => $dataFine,
    ]);
}




}