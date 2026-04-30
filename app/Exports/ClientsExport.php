<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Services\ClientService;

class ClientsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $service;

    public function __construct()
    {
        $this->service = new ClientService();
    }
    

    public function query()
    {
        return $this->service->getClientsQuery();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombres y apellidos',
            'DNI',
            'Direcciones',
            'Barrios'
        ];
    }

    public function map($client): array
    {
        return [
            $client->id,
            optional($client->currentTitular)->names_lastnames ?? 'Sin titular',
            optional($client->currentTitular)->dni ?? '--',

            // Direcciones en texto plano (no HTML)
            $client->directions->pluck('name')->implode(', '),

            $client->directions
                ->pluck('zone.name')
                ->filter()
                ->implode(', ')
        ];
    }
}
