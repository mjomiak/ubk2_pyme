<?php

namespace App\Imports;



use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Log;

class CargaMasivaTrab implements ToCollection
{
    protected $id_cliente;
    private $errors = []; // este va aser un array de filas
    private $filas = []; //aqui van los errores por columna

    public function __construct($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    public function collection(Collection $rows)
    {
        $tabla = 'ubk2_trabajadores_aux';
        foreach ($rows->slice(1, 100) as $row) {
            // Empieza desde la fila 2 hasta la fila 101
            //las columnas nombre, run, movil y correo no estan vacias
            /*
            1- nombre
            2-run
            3-movil
            4-correo
            5-area
             */
            //todo se inicializa como no ok
            //general
            $this->filas['nro'] = strval(strtoupper($row[0]));
            $this->filas['trabajador'] = 'nok';
            //run             
            $this->filas['RUN_formato'] = 'nok';
            $this->filas['RUN_invalido'] = 'nok';
            $this->filas['RUN_NU'] = 'nok';

            //movil
            $this->filas['movil'] = 'nok';
            $this->filas['movil_NU'] = 'nok';

            //correo

            $this->filas['correo_NU'] = 'nok';

            $this->filas['area'] = 'nok';
            $this->filas['estado'] = 'nok';






            if ($row[1] != null && $row[2] != null && $row[3] != null && $row[4] != null) {
                $this->filas = [];
                $this->filas['nro'] = strval($row[0]);
                $validacion = 0;
                //preparacion del
                $runArray = explode("-", $row[2]);
                $division = sizeof($runArray);
                if ($division === 2) {
                    $separaRun = 0;
                    $run = str_replace(".", "", $runArray[0]);
                    $run = str_replace(",", "", $run);
                    $run =trim($run);
                    $dv = strtoupper($runArray[1]);
                    $this->filas['RUN_formato'] = 'ok';
                } else {
                    $separaRun = 1;
                    $this->filas['RUN_formato'] = 'nok';
                }
                //preparacion de movil
                $movil = preg_replace("/[^0-9]/", "", $row[3]);

                Log::info('movil: ' . $movil . ' ' . $row[1]);
                //validacion de datos
                //run valido
                //existe el run
                $movilValido = $this->validarMovil($movil, 11); // 11 es el largo, deveria ser parametro "regional"
                if ($movilValido === 0) {
                    $movilValido = 0;
                    $this->filas['movil'] = 'ok';
                } else {
                    $movilValido = 1;
                    $this->filas['movil'] = 'nok';
                }
                $runValido = abs($this->validaRUN($run, $dv) - 1);
                if ($runValido === 0) {
                    $runValido = 0;
                    $this->filas['RUN_invalido'] = 'ok';
                } else {
                    $runValido = 1;
                    $this->filas['RUN_invalido'] = 'nok';
                }
                $runUnico = DB::table($tabla)->where('rut', $row[2])->count();
                if ($runUnico === 0) {
                    $runUnico = 0;
                    $this->filas['RUN_NU'] = 'ok';
                } else {
                    $runUnico = 1;
                    $this->filas['RUN_NU'] = 'nok';
                }
                $movilUnico = DB::table($tabla)->where('movil', $movil)->count();
                if ($movilUnico === 0) {
                    $movilUnico = 0;
                    $this->filas['movil_NU'] = 'ok';
                } else {
                    $movilUnico = 1;
                    $this->filas['movil_NU'] = 'nok';
                }
                $correoUnico = DB::table($tabla)->where('correo', $row[4])->count();
                if ($correoUnico === 0) {
                    $correoUnico = 0;
                    $this->filas['correo_NU'] = 'ok';
                } else {
                    $correoUnico = 1;
                    $this->filas['correo_NU'] = 'nok';
                }

                //buscar el area

                $idArea = DB::table('ubk2_areas')->where('id_cliente', $this->id_cliente)->where('nombre', $row[5])->value('id_area');
                Log::info('id area-------------> ' . $idArea);
                if ($idArea == null) {
                    $idArea = 0;
                    $this->filas['area'] = 'nok';
                } else {
                    $this->filas['area'] = 'ok';
                }



                Log::info('separacion run: ' . $separaRun . ' ' . $row[1]);
                Log::info('movil valido: ' . $movilValido . ' ' . $row[1]);
                Log::info('run valido ' . $runValido . ' ' . $row[1]);
                Log::info('run unico ' . $runUnico . ' ' . $row[1]);
                //Log::info('movil: unico: '.print_r($movilUnico));
                Log::info(' movil unico' . $movilUnico . ' ' . $row[1]);
                Log::info(' correo unico ' . $correoUnico . ' ' . $row[1]);

                $validado = $separaRun + $movilValido + $runValido + $runUnico + $movilUnico + $correoUnico;

                if ($validado == 0) {

                    //insersion en base de datoas
                    DB::table($tabla)->insert([
                        'id_cliente' => $this->id_cliente,
                        'nombreCompleto' => strtoupper($row[1]),
                        'rut' => $run,
                        'dv' => strtoupper($dv),
                        'movil' => $row[3],
                        'correo' => $row[4],
                        'id_area' => $idArea,
                        'activo' => 1,
                        'accion' => 0,
                        'borrado' => 0,
                    ]);
                    $this->filas['estado'] = 'ok';
                    $this->filas['trabajador'] = strval(strtoupper($row[1]));
                } else {
                    $this->filas['estado'] = 'nok';
                    $this->filas['trabajador'] = strval(strtoupper($row[1]));
                    // array_push($this->errors, 'error de validacion de datos');
                }
                array_push($this->errors, $this->filas);
            } else {   // los nulos
                if ($row[1] != null || $row[2] != null || $row[3] != null || $row[4] != null) {
                    if($row[1]==null){$this->filas['trabajador']='nd';}else{$this->filas['trabajador']=strtoupper($row[1]);}
                    if($row[2]==null){
                        $this->filas['RUN_formato']='nd';
                        $this->filas['RUN_invalido']='nok';
                        $this->filas['RUN_UN']='nok';
                    }else{
                        $this->filas['RUN_formato']='ok';
                        $this->filas['RUN_invalido']='ok';
                        $this->filas['RUN_UN']='ok';
                    }
                    if($row[3]==null){
                        $this->filas['movil']='nd';
                        $this->filas['movil_UN']='nok';
                      
                    }else{
                        $this->filas['movil']='ok';
                        $this->filas['movil_UN']='ok';
                        
                    }
                    if($row[4]==null){
                        $this->filas['correo_NU']='nd';
                       
                      
                    }else{
                        $this->filas['correo_NU']='ok';
                     
                        
                    }
                    array_push($this->errors, $this->filas);
                }
            }
           
        }

        //array_push($this->errors, 'Nuevo de prueba para ver en el resumen');
    }

    public function getResultado()
    {
        //dd($this->errors);
        return $this->errors;
    }


    public function validaRUN($rutSolo, $dv)
    {
        if ($dv == 'K') {
            $dv = 10;
        }
        if ($dv == 0) {
            $dv = 11;
        }

        $rutArray = str_split($rutSolo);
        $suma = 0;
        $secuencia = [2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7];
        $i = 0;
        for ($x = sizeof($rutArray) - 1; $x >= 0; $x--) {
            $suma = ($rutArray[$x] * $secuencia[$i]) + $suma;
            $i++;
        }
        $modulo = $suma % 11;
        $dvc = 11 - $modulo;
        if ($dvc == $dv) {
            return 1;
        } else {
            return 0;
        }
    }
    //$this->errors[] = $e->errors();

    public function validarMovil($movil, $largo)
    {
        // lógica inversa para que si es true sume 0
        $largoMovil = strlen($movil);

        Log::info('largo movil' . $largoMovil . ' largo esperado' . $largo);
        if ($largoMovil == $largo) {
            return 0;
        } else {
            return 1;
        }
    }
}
