<?php

use Illuminate\Database\Seeder;

class pais extends Seeder
{
    /**
     * Run the database seeds,
     *
     * @return void
     */
    public function run()
    {
        DB::table('paises')->delete();
       
		Vanguard\Pais::create( [	
			'id'							=>1,	
			'pais'							=>'Guatemala',
			'color'							=>'#4577EA',
			'moneda_simbolo'				=>'Q',
			'moneda_nombre'					=>'Quetzal',
			'porcentaje_seguridad_social'	=>'4.83',
			'tipo_seguridad_social'			=>'porcentaje',

			'seguridad_social_p'			=>'4.83',
			'tipo_seguridad_social_p'		=>'porcentaje',

			'pago_indemnizacion'			=>'anual',
			'pago_pension'					=>'anual',

			'porcentaje_pension'			=>'6.33',
			'campo_deducciones'				=>'fondo_pension,impuesto_renta,seguridad_social,otras_deducciones'		
		
		] );
		Vanguard\Pais::create( [	
			'id'								=>2,	
			'pais'								=>'Bolivia',
			'color'								=>'#30A530',
			'moneda_simbolo'					=>'Bs',
			'moneda_nombre'						=>'Boliviano',
			'porcentaje_seguridad_social'		=>'0',
			'tipo_seguridad_social'				=>'valor',

			'seguridad_social_p'				=>'0',
			'tipo_seguridad_social_p'			=>'porcentaje',

			'pago_indemnizacion'				=>'anual',
			'pago_pension'						=>'anual',

			'porcentaje_pension'				=>'',
			'campo_deducciones'					=>'fondo_pension'		
		
		] );
		Vanguard\Pais::create( [	
			'id'								=>3,	
			'pais'								=>'Nicaragua',
			'color'								=>'#D77C23',
			'moneda_simbolo'					=>'‎C$',
			'moneda_nombre'						=>'Córdoba',
			'porcentaje_seguridad_social'		=>'6.25',
			'tipo_seguridad_social'				=>'valor',

			'seguridad_social_p'				=>'0',
			'tipo_seguridad_social_p'			=>'valor',

			'pago_indemnizacion'				=>'anual',
			'pago_pension'						=>'anual',

			'porcentaje_pension'				=>'6',
			'campo_deducciones'					=>'fondo_pension,impuesto_renta,seguridad_social,otras_deducciones'		
		
		] );
		Vanguard\Pais::create( [	
			'id'								=>4,	
			'pais'								=>'Honduras',
			'color'								=>'#4542C0',
			'moneda_simbolo'					=>'L',
			'moneda_nombre'						=>'Lempira',
			'porcentaje_seguridad_social'		=>'287.06',
			'tipo_seguridad_social'				=>'valor',

			'seguridad_social_p'				=>'590.34',
			'tipo_seguridad_social_p'			=>'valor',

			'pago_indemnizacion'				=>'anual',
			'pago_pension'						=>'anual',

			'porcentaje_pension'				=>'8',
			'campo_deducciones'					=>'fondo_pension,impuesto_renta,seguridad_social'		
		
		] );
		Vanguard\Pais::create( [	
			'id'								=>5,	
			'pais'								=>'Paraguay',
			'color'								=>'#D83737',
			'moneda_simbolo'					=>'₲',
			'moneda_nombre'						=>'Guaraní',

			'porcentaje_seguridad_social'		=>'9',
			'tipo_seguridad_social'				=>'porcentaje',

			'seguridad_social_p'				=>'16.5',
			'tipo_seguridad_social_p'			=>'porcentaje',

			'pago_indemnizacion'				=>'anual',
			'pago_pension'						=>'anual',

			'porcentaje_pension'				=>'2.39',
			'campo_deducciones'					=>'fondo_pension,impuesto_renta,seguridad_social,prestamo,interes,otras_deducciones'		
		
		] );
		Vanguard\Pais::create( [	
			'id'								=>6,	
			'pais'								=>'Salvador',
			'color'								=>'#9FE151',
			'moneda_simbolo'					=>'$',
			'moneda_nombre'						=>'Dolar estadounidense',
			'porcentaje_seguridad_social'		=>'30',
			'tipo_seguridad_social'				=>'valor',

			'seguridad_social_p'				=>'75',
			'tipo_seguridad_social_p'			=>'valor',

			'pago_indemnizacion'				=>'retiro',
			'pago_pension'						=>'retiro',

			'porcentaje_pension'				=>'3.25',
			'campo_deducciones'					=>'fondo_pension,impuesto_renta,seguridad_social,interes,otras_deducciones'	
		
		] );
		//////////////////////////////////////////Nombre campos/////////////////////////////////////
		DB::table('nombre_campos')->delete();
		//Guatemala
		Vanguard\Campo::create( [	
			'id'						=>1,
			'salario_base'				=>'Sueldo mensual',		
			'catorceavo'				=>'Bono 14 (Dto 42-92)',			
			'otras_deducciones'			=>'Otros Descuentos',
			'impuestos'					=>'ISR mensual a Retener',
			'total_deducciones'			=>'Descuentos Totales',
			'seguridad_social'			=>'Cuota Mensual IGSS (4,83%)',	
			'liquido'					=>'Liquido a Recibir Qtz',
			'seguridad_social_patronal'	=>'IGGS',
			'pais_id'					=>1,
		
		] );
		//Bolivia
		Vanguard\Campo::create( [	
			'id'						=>2,
			'salario_base'				=>'HABER BÁSICO',		
			'ajustes'					=>'BONO DE ANTIGÜEDAD',		
			'total_salario'				=>'TOTAL GANADO',		
			'total_deducciones'			=>'TOTAL RETENCIONES',				
			'liquido'					=>'LÍQUIDO PAGABLE',			
			'pais_id'					=>2,
		
		] );
		//Salvador
		Vanguard\Campo::create( [	
			'id'						=>6,				
			'total_salario'				=>'Salario mensual local',	
			'interes'					=>'Interés',		
			'otras_deducciones'			=>'Otros',		
			'impuestos'					=>'RENTA',				
			'seguridad_social'			=>'ISSS',			
			'liquido'					=>'Total',
			'seguridad_social_patronal'	=>'ISSS',
			'pais_id'					=>6,
		
		] );
		//Honduras
		Vanguard\Campo::create( [	
			'id'						=>4,				
			'total_salario'				=>'Salario mensual HNL',						
			'seguridad_social'			=>'IHSS',	
			'impuestos'					=>'Impuesto de Renta',				
			'liquido'					=>'Saldo a Cancelar',
			'seguridad_social_patronal'	=>'IHSS',
			'pais_id'					=>4,
		
		] );
		//Nicaragua
		Vanguard\Campo::create( [	
			'id'						=>3,				
			'ajustes'					=>'Salario retroactivo',						
			'impuestos'					=>'IR',						
			'seguridad_social'			=>'INSS',
			'otras_deducciones'			=>'Otras deducciones',			
			'liquido'					=>'Total a recibir',
			'seguridad_social_patronal'	=>'INSS',
			'pais_id'					=>3,
		
		] );
		//Paraguay
		Vanguard\Campo::create( [	
			'id'						=>5,				
			'total_salario'				=>'Total',						
			'interes'					=>'Deducciones interés',
			'otras_deducciones'			=>'Otras deducciones',							
			'prestamo'					=>'Deducciones prestamo',
			'impuestos'					=>'Impuesto de Renta',						
			'seguridad_social'			=>'Seguro Social 9%',			
			'liquido'					=>'Total a recibir',
			'seguridad_social_patronal'	=>'Seguro Social Empleador (16.5%)',
			'pais_id'					=>5,
		
		] );


    }
}
