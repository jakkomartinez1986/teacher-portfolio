<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\SchoolSeeder;
use App\Models\Security\Spatie\Role;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\DataPrincipalSeeder;
use App\Models\Security\Spatie\Permission;
use Database\Seeders\DocumentCategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        if ($this->command->confirm('¿Desea actualizar la migración antes de sembrar? Borrará todos los datos antiguos? [y|N]', true)) {
            $this->command->call('migrate:fresh');
            $this->command->warn("Datos borrados, Iniciando la base de datos en blanco.");
            }
           

            $this->call([
               PermissionsSeeder::class,
            ]);
          
            $this->command->info('Permisos predeterminados agregados.');
            // Confirm roles needed
            if ($this->command->confirm('Crear roles para el usuario,los valores predeterminados son Super-Admin,Vicerrector,Dir-Area,Docente? [y|N]', true)) {
                // roles y descripcion de rol
                $input_roles = ['Super-Admin'=>'Super Administrador pueden realizar cualquier acción',
                                    'Admin'=>'Administrador están habilitados para leer,crear,actualizar,compartir,firmar documentos',
                                    'Rector'=>'Rector están habilitados para leer ,firmar documentos)', 
                                    'Vicerrector'=>'Vicerrector están habilitados para leer (todos los documentos)- crear(año lectivo-periodo academico-areas- tipo documento-asignar directores) y actualizar(estado usuario,año lectivo-periodo academico-areas- tipo documento-asignar directores)',
                                    'Inspector'=>'Rector están habilitados para leer ,firmar documentos,revisar asistencias)', 
                                    'Dir-Area'=>'Director de Area  están habilitados para leer(documentos area),crear(equipos), actualizar (estado usuario,equipos)', 
                                    'Dece'=>'Ps Dece están habilitados para leer documentos NEE ,firmar documentos de NEE',
                                    'Tutor'=>'Generar Documentacion de curso asignado, firmar documentos de curso asignado, llevar asistencia de curso asignado,subir listado estudiantes del curso asignado',
                                    'Docente'=>'Docente están habilitados para leer- crear- actualizar-compartir (documentos,notas,horario de clases) firmar documentos de curso asignado, llevar asistencia de curso asignado, subir listado estudiantes del curso asignado',
                                    'Estudiante'=>'Estudiante esta habilitado para ver horario de clases, ver documentos de curso asignado, ver asistencia de curso asignado, ver notas',];
                // 
                $input_array = ('Super-Admin,Admin,Rector,Vicerrector,Inspector,Dir-Area,Dece,Tutor,Docente,Estudiante');
                // add roles strtoupper($query)
                foreach($input_roles as $role=>$description) {
                    $rol = Role::firstOrCreate(['name' => trim(strtoupper($role)),'description'=>$description,'guard_name'=>config('auth.defaults.guard')]);
                    if( $rol->id == '1' ) {
                        // assign all permissions
                        $rol->syncPermissions(Permission::all());
                        $this->command->info('Al Super Administrador se otorgó todos los permisos');
                    } else {
                        // for others by default only read access
                        $rol->syncPermissions(Permission::where('name', 'LIKE', 'ver-%')->get());
                    }
                    // create one user for each role
                    //$this->createUser($role);
                }
                $this->command->info('Roles ' . $input_array . ' agregado exitosamente');
            } else {
                Role::firstOrCreate(['name' => 'ADMIN']);
                $this->command->info('Solo se agregó la función de usuario predeterminada a lectura.');
            }

        //    $this->command->info('Creando 5 usuarios Falsos.');
        //     User::factory(5)->create();
           //User::factory(20)->withPersonalTeam()->create();
          
          
           $this->call([
             //SchoolSeeder::class,
             DataPrincipalSeeder::class,
             DocumentCategorySeeder::class,
            // GradeSeeder::class,
            // DepartamentSeeder::class,
            // SubjectSeeder::class,
            // DocumentTypeSeeder::class,
            // DocumentTimelineSeeder::class,
         ]);
    
    }
}
