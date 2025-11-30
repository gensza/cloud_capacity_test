<?php

namespace App\Exports;

use App\Models\Cluster;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class CloudCapacityExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $rows = Cluster::selectRaw('
                cloud_capacity.id,
                cluster.cluster_name,
                cloud_capacity.cpu,
                cloud_capacity.mem,
                cloud_capacity.is_active,
                cloud_capacity.created_at,
                cloud_capacity.updated_at
            ')
            ->join('cloud_capacity', 'cloud_capacity.cluster_id', '=', 'cluster.id')
            ->get();

        $result = [];

        foreach ($rows as $row) {
            $result[] = [
                $row->id,
                $row->cluster_name,
                $row->cpu,
                $row->mem,
                $row->is_active,
                $row->created_at->format('d-m-Y H:i'),
                $row->updated_at->format('d-m-Y H:i'),
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cluster Name',
            'CPU',
            'MEM',
            'Active',
            'Created At',
            'Updated At'
        ];
    }
}
