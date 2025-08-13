<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case ATRASO = 'A';
    case INJUSTIFICADA = 'I';
    case JUSTIFICADA = 'J';
    case ABANDONO_AULA = 'AA';
    case ABANDONO_INSTITUCIONAL = 'AI';
    case PERMISO = 'P';
    case NOVEDAD = 'N';
    case PRESENTE = ''; // Presente es null o cadena vacía según lo definas
}