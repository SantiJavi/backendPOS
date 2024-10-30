<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodigosRetencionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'1',
            'descripcion_retencion'=>'IVA 30%',
            'porcentaje_cod_retencion'=>30,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'10',
            'descripcion_retencion'=>'IVA 20%',
            'porcentaje_cod_retencion'=>20,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'11',
            'descripcion_retencion'=>'IVA 50%',
            'porcentaje_cod_retencion'=>50,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'2',
            'descripcion_retencion'=>'IVA 70%',
            'porcentaje_cod_retencion'=>70,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'3',
            'descripcion_retencion'=>'IVA 100%',
            'porcentaje_cod_retencion'=>100,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'7',
            'descripcion_retencion'=>'IVA RET 0%',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'8',
            'descripcion_retencion'=>'IVA SIN RET 0%',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'9',
            'descripcion_retencion'=>'IVA 10%',
            'porcentaje_cod_retencion'=>10,
            'tipo_cod_impuesto'=>'iva'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'303',
            'descripcion_retencion'=>'Honorarios profesionales y demás pagos por servicios relacionados con el título profesional',
            'porcentaje_cod_retencion'=>10,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304',
            'descripcion_retencion'=>'Servicios predomina el intelecto no relacionados con el título profesional',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304A',
            'descripcion_retencion'=>'Comisiones y demás pagos por servicios predomina intelecto no relacionados con el título profesional',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304B',
            'descripcion_retencion'=>'Pagos a notarios y registradores de la propiedad y mercantil por sus actividades ejercidas como tales',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304C',
            'descripcion_retencion'=>'Pagos a deportistas, entrenadores, árbitros, miembros del cuerpo técnico por sus actividades ejercidas como tale',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304D',
            'descripcion_retencion'=>'Pagos a artistas por sus actividades ejercidas como tales',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'304E',
            'descripcion_retencion'=>'Honorarios y demás pagos por servicios de docencia',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'307',
            'descripcion_retencion'=>'Servicios predomina la mano de obra',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'308',
            'descripcion_retencion'=>'Utilización o aprovechamiento de la imagen o renombre',
            'porcentaje_cod_retencion'=>10,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'309',
            'descripcion_retencion'=>'Servicios prestados por medios de comunicación y agencias de publicidad',
            'porcentaje_cod_retencion'=>1.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'310',
            'descripcion_retencion'=>'Servicio de transporte privado de pasajeros o transporte público o privado de carga',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'311',
            'descripcion_retencion'=>'Pagos a través de liquidación de compra (nivel cultural o rusticidad)',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'312',
            'descripcion_retencion'=>'Transferencia de bienes muebles de naturaleza corporal',
            'porcentaje_cod_retencion'=>1.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'312A',
            'descripcion_retencion'=>'Compra de bienes de origen agrícola, avícola, pecuario, apícola, cunícola, bioacuático, forestal y carnes en estado natural',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'312B',
            'descripcion_retencion'=>'Impuesto a la Renta único para la actividad de producción y cultivo de palma aceitera',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'314A',
            'descripcion_retencion'=>'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual - pago a personas naturales',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'314B',
            'descripcion_retencion'=>'Cánones, derechos de autor, marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a personas naturales',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'314C',
            'descripcion_retencion'=>'Regalías por concepto de franquicias de acuerdo a Ley de Propiedad Intelectual - pago a sociedades',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'314D',
            'descripcion_retencion'=>'Cánones, derechos de autor, marcas, patentes y similares de acuerdo a Ley de Propiedad Intelectual – pago a sociedades',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'319',
            'descripcion_retencion'=>'Cuotas de arrendamiento mercantil (prestado por sociedades), inclusive la de opción de compra',
            'porcentaje_cod_retencion'=>1.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'320',
            'descripcion_retencion'=>'Arrendamiento bienes inmuebles',
            'porcentaje_cod_retencion'=>10,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'322',
            'descripcion_retencion'=>'Seguros y reaseguros (primas y cesiones)',
            'porcentaje_cod_retencion'=>1.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323',
            'descripcion_retencion'=>'Rendimientos financieros pagados a naturales y sociedades (No a IFIs)',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323A',
            'descripcion_retencion'=>'Rendimientos financieros depósitos Cta. Corriente',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323B1',
            'descripcion_retencion'=>'Rendimientos financieros depósitos Cta. Ahorros Sociedades',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323E',
            'descripcion_retencion'=>'Rendimientos financieros depósito a plazo fijo gravados',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323E2',
            'descripcion_retencion'=>'Rendimientos financieros depósito a plazo fijo exentos',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323F',
            'descripcion_retencion'=>'Rendimientos financieros operaciones de reporto - repos',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323G',
            'descripcion_retencion'=>'Inversiones (captaciones) rendimientos distintos de aquellos pagados a IFIs',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323H',
            'descripcion_retencion'=>'Rendimientos financieros obligaciones',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323I',
            'descripcion_retencion'=>'Rendimientos financieros bonos convertible en acciones',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323 M',
            'descripcion_retencion'=>'Rendimientos financieros: Inversiones en títulos valores en renta fija gravados',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323 N',
            'descripcion_retencion'=>'Rendimientos financieros Inversiones en títulos valores en renta fija exentos',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323 O',
            'descripcion_retencion'=>'Intereses y demás rendimientos financieros pagados a bancos y otras entidades sometidas al control de la Superintendencia de Bancos y de la Economía Popular y Solidaria',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323 P',
            'descripcion_retencion'=>'Intereses pagados por entidades del sector público a favor de sujetos pasivos',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323Q',
            'descripcion_retencion'=>'Otros intereses y rendimientos financieros gravados',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323R',
            'descripcion_retencion'=>'Otros intereses y rendimientos financieros exentos',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323S',
            'descripcion_retencion'=>'Pagos y créditos en cuenta efectuados por el BCE y los depósitos centralizados de valores, en calidad de intermediarios, a instituciones del sistema financiero por cuenta de otras personas naturales y sociedades',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323T',
            'descripcion_retencion'=>'Rendimientos financieros originados en la deuda pública ecuatoriana',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'323U',
            'descripcion_retencion'=>'Rendimientos financieros originados en títulos valores de obligaciones de 360 días o más para el financiamiento de proyectos públicos en asociación público-privada',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'324A',
            'descripcion_retencion'=>'Intereses en operaciones de crédito entre instituciones del sistema financiero y entidades economía popular y solidaria',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'324B',
            'descripcion_retencion'=>'Inversiones entre instituciones del sistema financiero y
            entidades economía popular y solidaria',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'324C',
            'descripcion_retencion'=>'Pagos y créditos en cuenta efectuados por el BCE y los depósitos centralizados de valores, en calidad de intermediarios, a instituciones del sistema financiero por cuenta de otras instituciones del sistema financiero',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'325',
            'descripcion_retencion'=>'Anticipo dividendos',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'325A',
            'descripcion_retencion'=>'Préstamos accionistas, beneficiarios o partícipes residentes o establecidos en el Ecuador',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'326',
            'descripcion_retencion'=>'Dividendos distribuidos que correspondan al impuesto a la renta único establecido en el art. 27 de la LRTI.',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'327',
            'descripcion_retencion'=>'Dividendos distribuidos a personas naturales residentes.',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'328',
            'descripcion_retencion'=>'Dividendos distribuidos a sociedades residentes.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'329',
            'descripcion_retencion'=>'Dividendos distribuidos a fideicomisos residentes.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'331',
            'descripcion_retencion'=>'Dividendos en acciones (capitalización de utilidades)',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332',
            'descripcion_retencion'=>'Otras compras de bienes y servicios no sujetas a retención (incluye régimen RIMPE - Negocios Populares, para este caso aplica con cualquier forma de pago inclusive los pagos que deban realizar las tarjetas de crédito/débito).',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332B',
            'descripcion_retencion'=>'Compra de bienes inmuebles.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332C',
            'descripcion_retencion'=>'Transporte público de pasajeros.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332D',
            'descripcion_retencion'=>'Pagos en el país por transporte de pasajeros o transporte internacional de carga, a compañías nacionales o extranjeras de aviación o marítimas',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332E',
            'descripcion_retencion'=>'Valores entregados por las cooperativas de transporte a sus socios.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332F',
            'descripcion_retencion'=>'Compraventa de divisas distintas al dólar de los Estados Unidos de América.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332G',
            'descripcion_retencion'=>'Pagos con tarjeta de crédito.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332H',
            'descripcion_retencion'=>'Pago al exterior tarjeta de crédito reportada por la Emisora de tarjeta de crédito, solo recap.',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'332I',
            'descripcion_retencion'=>'Pago a través de convenio de débito (Clientes IFI`s)',
            'porcentaje_cod_retencion'=>0,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'333',
            'descripcion_retencion'=>'Ganancia en la enajenación de derechos representativos de capital u otros derechos que permitan la exploración, explotación, concesión o similares de sociedades, que se coticen en bolsa de valores del Ecuador.',
            'porcentaje_cod_retencion'=>10,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'334',
            'descripcion_retencion'=>'Contraprestación producida por la enajenación de derechos representativos de capital u otros derechos que permitan la exploración, explotación, concesión o similares de sociedades, no cotizados en bolsa de valores del Ecuador.',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'335',
            'descripcion_retencion'=>'Loterías, rifas, pronósticos deportivos, apuestas y similares',
            'porcentaje_cod_retencion'=>15,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'336',
            'descripcion_retencion'=>'Venta de combustibles a comercializadoras',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'337',
            'descripcion_retencion'=>'Venta de combustibles a distribuidore',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'338',
            'descripcion_retencion'=>'Producción y venta local de banano producido o no por el mismo sujeto pasivo.',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'340',
            'descripcion_retencion'=>'Impuesto único a la exportación de banano.',
            'porcentaje_cod_retencion'=>3,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'343',
            'descripcion_retencion'=>'Otras retenciones aplicables el 1% (incluye régimen RIMPE - Emprendedores, para este caso aplica con cualquier forma de pago inclusive los pagos que deban realizar las tarjetas de crédito/débito).',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'343A',
            'descripcion_retencion'=>'Energía eléctrica',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'343B',
            'descripcion_retencion'=>'Actividades de construcción de obra material inmueble, urbanización, lotización o actividades similares.',
            'porcentaje_cod_retencion'=>1.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'343C',
            'descripcion_retencion'=>'Impuesto Redimible a las botellas plásticas – IRBP.',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'3440',
            'descripcion_retencion'=>'Otras retenciones aplicables el 2,75%',
            'porcentaje_cod_retencion'=>2.75,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'344A',
            'descripcion_retencion'=>'Pago local tarjeta de crédito /débito reportada por la Emisora de tarjeta de crédito / entidades del sistema financiero.',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'344B',
            'descripcion_retencion'=>'Adquisición de sustancias minerales dentro del territorio nacional.',
            'porcentaje_cod_retencion'=>2,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'345',
            'descripcion_retencion'=>'Otras retenciones aplicables el 8%.',
            'porcentaje_cod_retencion'=>8,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'346',
            'descripcion_retencion'=>'Otras retenciones aplicables a otros porcentajes',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'346A',
            'descripcion_retencion'=>'Otras ganancias de capital distintas de enajenación de derechos representativos de capital',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'346B',
            'descripcion_retencion'=>'Donaciones en dinero - Impuesto a las donaciones',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'346C',
            'descripcion_retencion'=>'Retención a cargo del propio sujeto pasivo por la producción y/o comercialización de minerales y otros bienes',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'346D',
            'descripcion_retencion'=>'Retención a cargo del propio sujeto pasivo por la comercialización de productos forestales.',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'348',
            'descripcion_retencion'=>'Impuesto único a ingresos provenientes de actividades agropecuarias en etapa de producción / comercialización local o exportación.',
            'porcentaje_cod_retencion'=>1,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'350',
            'descripcion_retencion'=>'Otras autorretenciones.',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'3480',
            'descripcion_retencion'=>'Impuesto a la renta único sobre los ingresos percibidos por los operadores de pronósticos deportivos',
            'porcentaje_cod_retencion'=>15,
            'tipo_cod_impuesto'=>'renta'
            ] );
                        
            DB::table('codigos_retencions')->insert( [
            'codigo_retencion'=>'3481',
            'descripcion_retencion'=>'Autorretenciones Sociedades Grandes Contribuyentes',
            'porcentaje_cod_retencion'=>NULL,
            'tipo_cod_impuesto'=>'renta'
            ] );

    }
}
