<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Vanguard\Country;
use Vanguard\Services\Logging\UserActivity\Activity;
use Vanguard\Support\Enum\UserStatus;

/*$factory->define(Vanguard\User::class, function (Faker\Generator $faker, array $attrs) {

    $countryId = isset($attrs['country_id'])
        ? $attrs['country_id']
        : $faker->randomElement(Country::lists('id')->toArray());

    return [
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'avatar' => null,
        'address' => $faker->address,
        'country_id' => $countryId,
        'status' => UserStatus::ACTIVE,
        'birthday' => $faker->date()
    ];
});*/

$factory->define(Vanguard\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(5),
        'display_name' => implode(" ", $faker->words(2)),
        'description' => $faker->paragraph,
        'removable' => true,
    ];
});

$factory->define(Vanguard\Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => str_random(5),
        'display_name' => implode(" ", $faker->words(2)),
        'description' => $faker->paragraph,
        'removable' => true
    ];
});

$factory->define(Activity::class, function (Faker\Generator $faker, array $attrs) {

    $userId = isset($attrs['user_id'])
        ? $attrs['user_id']
        : factory(\Vanguard\User::class)->create()->id;

    return [
        'user_id' => $userId,
        'description' => $faker->paragraph,
        'ip_address' => $faker->ipv4,
        'user_agent' => $faker->userAgent
    ];
});

$factory->define(Country::class, function (Faker\Generator $faker) {
    return [
        'country_code' => $faker->countryCode,
        'iso_3166_2' => strtoupper($faker->randomLetter . $faker->randomLetter),
        'iso_3166_3' => $faker->countryISOAlpha3,
        'name' => $faker->country,
        'region_code' => 123,
        'sub_region_code' => 123
    ];
});
$factory->define(Vanguard\Planilla::class, function ($faker)  {
    
    return [
        'pais_id'                             => $faker->numberBetween($min =1, $max = 2),
        'rrhh_id'                             => $faker->numberBetween($min =1, $max = 2),
        'gerente_financiero_id'               => $faker->numberBetween($min =1, $max = 2),
        'director_id'                         => $faker->numberBetween($min =1, $max = 2), 
        'aprobacion_gerente_financiero'       => $faker->randomElement($array = array (0,1)),  
        'fecha_aprobacion_gerente_financiero' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'aprobacion_director'                 => $faker->randomElement($array = array (0,1)),      
        'fecha_aprobacion_director'          => $faker->date($format = 'Y-m-d', $max = 'now'),
        'tipo_planilla'                       => $faker->randomElement($array = array ('normal','aguinaldo')),       
        
        'nombre_renta_pais'                   => $faker->randomElement($array = array ('ISR','Impuesto de Renta','Renta')),      
        'porcentaje_pension_pais'             =>$faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 9),
        'nombre_seguridad_social_pais'        => $faker->randomElement($array = array ('IGSS','INSS','IHSS')), 
        'porcentaje_seguridad_social_pais'    =>$faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 9),
        


    ];
});

$factory->define(Vanguard\User::class, function ($faker)  {
    
    return [
        'email'                         => $faker->email,
        'password'                      => bcrypt('123456'),
        'first_name'                    => $faker->firstName,
        'last_name'                     => $faker->lastName,     
        'n_contrato'                    => $faker->randomNumber($nbDigits = NULL, $strict = false),
        'documento'                     => $faker->randomNumber($nbDigits = NULL, $strict = false), // 79907610
        'fecha_inicio'                  => $faker->date($format = 'Y-m-d', $max = 'now'),        
        'n_identificacion_tributaria'   => $faker->randomNumber($nbDigits = NULL, $strict = false),
        'regimen_tributario'            => $faker->randomNumber($nbDigits = NULL, $strict = false),        
        'contacto_emergencia'           => $faker->name ,    // '(888) 937-7238'
        'tlf_contacto_emergencia'       => $faker->phoneNumber ,        
        'cellphone'                     => $faker->phoneNumber ,
        'phone'                         => $faker->phoneNumber ,
        'tipo_sangre'                   => $faker->randomElement($array = array ('A+','B+','C+','A-','B-','C-')),
        'sexo'                          => $faker->randomElement($array = array ('M','F')),
        'skype'                         => $faker->userName,        
        'status'                        => 1,
        'acumulado_vacaciones'          => $faker->numberBetween($min =3, $max = 16),
        'salario_base'                  => $faker->randomFloat($nbMaxDecimals = 2, $min = 2000, $max = 20000),
        'birthday'                      => $faker->date($format = 'Y-m-d', $max = '-20 years'),
        'oficina_id'                    => $faker->numberBetween($min =1, $max = 6),
        'cargo_id'                      => $faker->numberBetween($min =1, $max = 20),
        'tipo_documento_id'             => $faker->numberBetween($min =1, $max = 4),
        'categoria_id'                  => $faker->numberBetween($min =1, $max = 3),
        'profesion_id'                  => $faker->numberBetween($min =1, $max = 20),
        
    ];
});
