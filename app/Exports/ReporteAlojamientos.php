<?php

namespace App\Exports;

use App\Models\Recepcion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReporteAlojamientos implements FromCollection, WithHeadings
{

    protected $fecha_inicial;
    protected $fecha_final;
    protected $responsable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($fecha_inicial,$fecha_final,$responsable)
    {
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->responsable = $responsable;
    }


    public function collection()
    {

        $alojamientos = Recepcion::select("alojamientos.*", "habitaciones.nombre as habitacion", "clientes.nombre as cliente")
            ->join("habitaciones", "habitaciones.id", "=", "alojamientos.habitaciones_id")
            ->join("clientes", "clientes.id", "=", "alojamientos.clientes_id");

        if($this->fecha_inicial!=""){
            $alojamientos->where("fecha_hora_entrada",">=",$this->fecha_inicial." 00:00:00");
        }

        if($this->fecha_final!=""){
            $alojamientos->where("fecha_hora_entrada","<=",$this->fecha_final." 23:59:59");
        }

        if($this->responsable!=""){
            $alojamientos->where("users_id","=",$this->responsable);
        }

        $alojamientos->where("alojamientos.estado","=","terminada");

        //dd( $this->fecha_inicial ."-". $this->fecha_final ."-". $this->responsable);

        return  $alojamientos->get();
    }

    public function headings(): array
    {
        return array_keys($this->collection()->first()->toArray());
    }
}
