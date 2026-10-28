<?php

namespace Tests\Unit;

use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Grupo;
use PHPUnit\Framework\TestCase;

class GrupoTest extends TestCase
{
    public function test_grupo_contiene_toda_su_informacion(): void
    {
        $curso = new Curso([
            'sigla' => 'IF6100',
            'nombre_curso' => 'Análisis y Diseño de Sistemas',
            'creditos' => 4,
        ]);

        $docente1 = new Docente([
            'cod_empleado' => 1001,
            'nombre_empleado' => 'María',
            'apellidos_empleado' => 'Rojas Vargas',
        ]);
        $docente2 = new Docente([
            'cod_empleado' => 1002,
            'nombre_empleado' => 'Carlos',
            'apellidos_empleado' => 'Mora Solano',
        ]);

        $estudiante = new Estudiante([
            'carne' => 'C20051',
            'nombre' => 'Daniela',
            'apellidos' => 'Acosta Mercado',
        ]);
        $estudiante->setRelation('consejero', $docente1);

        $grupo = new Grupo([
            'numero_ciclo' => 2,
            'ano' => 2026,
            'numero_grupo' => 1,
            'abierto' => true,
        ]);
        $grupo->setRelation('curso', $curso);
        $grupo->setRelation('docentes', collect([$docente1, $docente2]));
        $grupo->setRelation('estudiantes', collect([$estudiante]));

        $this->assertSame(2, $grupo->numero_ciclo);
        $this->assertSame(2026, $grupo->ano);
        $this->assertSame(1, $grupo->numero_grupo);
        $this->assertTrue($grupo->abierto);

        $this->assertSame('IF6100', $grupo->curso->sigla);
        $this->assertSame(4, $grupo->curso->creditos);

        $this->assertGreaterThanOrEqual(1, $grupo->docentes->count());
        $this->assertLessThanOrEqual(2, $grupo->docentes->count());
        $this->assertSame('María', $grupo->docentes->first()->nombre_empleado);

        $this->assertCount(1, $grupo->estudiantes);
        $this->assertSame('C20051', $grupo->estudiantes->first()->carne);
        $this->assertSame(1001, $grupo->estudiantes->first()->consejero->cod_empleado);
    }
}