<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Exports\CloudCapacityExport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\CloudCapacityExportMail;

use function Laravel\Prompts\alert;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total CPU dan MEM per cluster
        $data = Cluster::selectRaw('
            cloud_capacity.cluster_id,
            cluster.cluster_name,
            COALESCE(SUM(cloud_capacity.cpu), 0) as total_cpu,
            COALESCE(SUM(cloud_capacity.mem), 0) as total_mem
        ')
            ->leftjoin('cloud_capacity', 'cloud_capacity.cluster_id', '=', 'cluster.id')
            ->groupBy('cloud_capacity.cluster_id', 'cluster.cluster_name')
            ->orderBy('total_cpu', 'DESC')
            ->get();

        return view('dashboard', [
            'labels' => $data->pluck('cluster_name')->toArray(),
            'cpu' => $data->pluck('total_cpu')->toArray(),
            'mem' => $data->pluck('total_mem')->toArray(),
        ]);
    }

    public function downloadExcel()
    {
        if (env('MAIL_USERNAME') == null) {
            return Excel::download(new CloudCapacityExport, 'cloud_capacity.xlsx');
        }

        // simpan di storage/app/exports
        $filePath = 'exports/cloud_capacity_' . now()->format('Ymd_His') . '.xlsx';

        Excel::store(new CloudCapacityExport, $filePath);

        // Kirim email ke alamat dari .env
        $email = env('NOTIFICATION_EMAIL');

        Mail::to($email)->send(new CloudCapacityExportMail($filePath));

        return response()->download(storage_path("app/$filePath"));
    }
}
