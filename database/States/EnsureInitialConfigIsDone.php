<?php

namespace Database\States;

use App\Models\CivilState;
use App\Models\Gender;
use Exception;
use Illuminate\Support\Facades\DB;
use Log;

class EnsureInitialConfigIsDone
{
    public function __invoke()
    {
        // Check if required tables exist before proceeding
        if (!$this->tablesExist()) {
            return;
        }

        if ($this->alreadyRun()) {
            return;
        }
        // Genders
        $genders = ['Masculino', 'Feminino'];
        foreach ($genders as $gender) {
            Gender::firstOrCreate(['name' => $gender]);
        }

        // CivilStates
        $civilStates = ['Solteiro', 'Casado', 'Divorciado', 'Viuvo'];
        foreach ($civilStates as $civilState) {
            CivilState::firstOrCreate(['name' => $civilState]);
        }

        // identityDocuments
        $identityDocuments = ['BI', 'Passaporte', 'DIRE'];
        foreach ($identityDocuments as $identityDocument) {
            \DB::table('identity_documents')->insert(['name' => $identityDocument]);
        }
        $continents = collect([
            ['id' => 1, 'name' => 'África'],
            ['id' => 2, 'name' => 'América'],
            ['id' => 3, 'name' => 'Ásia'],
            ['id' => 4, 'name' => 'Europa'],
            ['id' => 5, 'name' => 'Oceânia'],
        ])->each(function ($continent) {
            \DB::table('continents')->insert($continent);
        });

        $countries = collect([
            ['id' => 1, 'name' => 'Afeganistão', 'code' => 'AF', 'iso' => 'AFG', 'continent_id' => 3],
            ['id' => 2, 'name' => 'África do Sul', 'code' => 'ZA', 'iso' => 'ZAF', 'continent_id' => 1],
            ['id' => 3, 'name' => 'Albânia', 'code' => 'AL', 'iso' => 'ALB', 'continent_id' => 4],
            ['id' => 4, 'name' => 'Alemanha', 'code' => 'DE', 'iso' => 'DEU', 'continent_id' => 4],
            ['id' => 5, 'name' => 'Andorra', 'code' => 'AD', 'iso' => 'AND', 'continent_id' => 4],
            ['id' => 6, 'name' => 'Angola', 'code' => 'AO', 'iso' => 'AGO', 'continent_id' => 1],
            ['id' => 7, 'name' => 'Anguilla', 'code' => 'AI', 'iso' => 'AIA', 'continent_id' => 2],
            ['id' => 8, 'name' => 'Antígua e Barbuda', 'code' => 'AG', 'iso' => 'ATG', 'continent_id' => 2],
            ['id' => 9, 'name' => 'Antilhas Neerlandesas', 'code' => 'AN', 'iso' => 'ANT', 'continent_id' => 2],
            ['id' => 10, 'name' => 'Arábia Saudita', 'code' => 'SA', 'iso' => 'SAU', 'continent_id' => 3],
            ['id' => 11, 'name' => 'Argélia', 'code' => 'DZ', 'iso' => 'DZA', 'continent_id' => 1],
            ['id' => 12, 'name' => 'Argentina', 'code' => 'AR', 'iso' => 'ARG', 'continent_id' => 2],
            ['id' => 13, 'name' => 'Armênia', 'code' => 'AM', 'iso' => 'ARM', 'continent_id' => 3],
            ['id' => 14, 'name' => 'Aruba', 'code' => 'AW', 'iso' => 'ABW', 'continent_id' => 2],
            ['id' => 15, 'name' => 'Austrália', 'code' => 'AU', 'iso' => 'AUS', 'continent_id' => 5],
            ['id' => 16, 'name' => 'Áustria', 'code' => 'AT', 'iso' => 'AUT', 'continent_id' => 4],
            ['id' => 17, 'name' => 'Azerbaijão', 'code' => 'AZ', 'iso' => 'AZE', 'continent_id' => 3],
            ['id' => 18, 'name' => 'Bahamas', 'code' => 'BS', 'iso' => 'BHS', 'continent_id' => 2],
            ['id' => 19, 'name' => 'Bahrein', 'code' => 'BH', 'iso' => 'BHR', 'continent_id' => 3],
            ['id' => 20, 'name' => 'Bangladesh', 'code' => 'BD', 'iso' => 'BGD', 'continent_id' => 3],
            ['id' => 21, 'name' => 'Barbados', 'code' => 'BB', 'iso' => 'BRB', 'continent_id' => 2],
            ['id' => 22, 'name' => 'Bélgica', 'code' => 'BE', 'iso' => 'BEL', 'continent_id' => 4],
            ['id' => 23, 'name' => 'Belize', 'code' => 'BZ', 'iso' => 'BLZ', 'continent_id' => 2],
            ['id' => 24, 'name' => 'Benin', 'code' => 'BJ', 'iso' => 'BEN', 'continent_id' => 1],
            ['id' => 25, 'name' => 'Bermuda', 'code' => 'BM', 'iso' => 'BMU', 'continent_id' => 2],
            ['id' => 26, 'name' => 'Bielorrússia', 'code' => 'BY', 'iso' => 'BLR', 'continent_id' => 4],
            ['id' => 27, 'name' => 'Bolívia', 'code' => 'BO', 'iso' => 'BOL', 'continent_id' => 2],
            ['id' => 28, 'name' => 'Bósnia e Herzegovina', 'code' => 'BA', 'iso' => 'BIH', 'continent_id' => 4],
            ['id' => 29, 'name' => 'Botswana', 'code' => 'BW', 'iso' => 'BWA', 'continent_id' => 1],
            ['id' => 30, 'name' => 'Brasil', 'code' => 'BR', 'iso' => 'BRA', 'continent_id' => 2],
            ['id' => 31, 'name' => 'Brunei', 'code' => 'BN', 'iso' => 'BRN', 'continent_id' => 3],
            ['id' => 32, 'name' => 'Bulgária', 'code' => 'BG', 'iso' => 'BGR', 'continent_id' => 4],
            ['id' => 33, 'name' => 'Burkina Faso', 'code' => 'BF', 'iso' => 'BFA', 'continent_id' => 1],
            ['id' => 34, 'name' => 'Burundi', 'code' => 'BI', 'iso' => 'BDI', 'continent_id' => 1],
            ['id' => 35, 'name' => 'Butão', 'code' => 'BT', 'iso' => 'BTN', 'continent_id' => 3],
            ['id' => 36, 'name' => 'Cabo Verde', 'code' => 'CV', 'iso' => 'CPV', 'continent_id' => 1],
            ['id' => 37, 'name' => 'Camarões', 'code' => 'CM', 'iso' => 'CMR', 'continent_id' => 1],
            ['id' => 38, 'name' => 'Camboja', 'code' => 'KH', 'iso' => 'KHM', 'continent_id' => 3],
            ['id' => 39, 'name' => 'Canadá', 'code' => 'CA', 'iso' => 'CAN', 'continent_id' => 2],
            ['id' => 40, 'name' => 'Catar', 'code' => 'QA', 'iso' => 'QAT', 'continent_id' => 3],
            ['id' => 41, 'name' => 'Cazaquistão', 'code' => 'KZ', 'iso' => 'KAZ', 'continent_id' => 3],
            ['id' => 42, 'name' => 'Chade', 'code' => 'TD', 'iso' => 'TCD', 'continent_id' => 1],
            ['id' => 43, 'name' => 'Chile', 'code' => 'CL', 'iso' => 'CHL', 'continent_id' => 2],
            ['id' => 44, 'name' => 'China', 'code' => 'CN', 'iso' => 'CHN', 'continent_id' => 3],
            ['id' => 45, 'name' => 'Chipre', 'code' => 'CY', 'iso' => 'CYP', 'continent_id' => 3],
            ['id' => 46, 'name' => 'Cingapura', 'code' => 'SG', 'iso' => 'SGP', 'continent_id' => 3],
            ['id' => 47, 'name' => 'Colômbia', 'code' => 'CO', 'iso' => 'COL', 'continent_id' => 2],
            ['id' => 48, 'name' => 'Comores', 'code' => 'KM', 'iso' => 'COM', 'continent_id' => 1],
            ['id' => 49, 'name' => 'Congo', 'code' => 'CG', 'iso' => 'COG', 'continent_id' => 1],
            ['id' => 50, 'name' => 'Coreia do Norte', 'code' => 'KP', 'iso' => 'PRK', 'continent_id' => 3],
            ['id' => 51, 'name' => 'Coreia do Sul', 'code' => 'KR', 'iso' => 'KOR', 'continent_id' => 3],
            ['id' => 52, 'name' => 'Costa do Marfim', 'code' => 'CI', 'iso' => 'CIV', 'continent_id' => 1],
            ['id' => 53, 'name' => 'Costa Rica', 'code' => 'CR', 'iso' => 'CRI', 'continent_id' => 2],
            ['id' => 54, 'name' => 'Croácia', 'code' => 'HR', 'iso' => 'HRV', 'continent_id' => 4],
            ['id' => 55, 'name' => 'Cuba', 'code' => 'CU', 'iso' => 'CUB', 'continent_id' => 2],
            ['id' => 56, 'name' => 'Dinamarca', 'code' => 'DK', 'iso' => 'DNK', 'continent_id' => 4],
            ['id' => 57, 'name' => 'Djibuti', 'code' => 'DJ', 'iso' => 'DJI', 'continent_id' => 1],
            ['id' => 58, 'name' => 'Dominica', 'code' => 'DM', 'iso' => 'DMA', 'continent_id' => 2],
            ['id' => 59, 'name' => 'Egito', 'code' => 'EG', 'iso' => 'EGY', 'continent_id' => 1],
            ['id' => 60, 'name' => 'El Salvador', 'code' => 'SV', 'iso' => 'SLV', 'continent_id' => 2],
            ['id' => 61, 'name' => 'Emirados Árabes Unidos', 'code' => 'AE', 'iso' => 'ARE', 'continent_id' => 3],
            ['id' => 62, 'name' => 'Equador', 'code' => 'EC', 'iso' => 'ECU', 'continent_id' => 2],
            ['id' => 63, 'name' => 'Eritreia', 'code' => 'ER', 'iso' => 'ERI', 'continent_id' => 1],
            ['id' => 64, 'name' => 'Eslováquia', 'code' => 'SK', 'iso' => 'SVK', 'continent_id' => 4],
            ['id' => 65, 'name' => 'Eslovênia', 'code' => 'SI', 'iso' => 'SVN', 'continent_id' => 4],
            ['id' => 66, 'name' => 'Espanha', 'code' => 'ES', 'iso' => 'ESP', 'continent_id' => 4],
            ['id' => 67, 'name' => 'Estados Unidos', 'code' => 'US', 'iso' => 'USA', 'continent_id' => 2],
            ['id' => 68, 'name' => 'Estônia', 'code' => 'EE', 'iso' => 'EST', 'continent_id' => 4],
            ['id' => 69, 'name' => 'Etiópia', 'code' => 'ET', 'iso' => 'ETH', 'continent_id' => 1],
            ['id' => 70, 'name' => 'Fiji', 'code' => 'FJ', 'iso' => 'FJI', 'continent_id' => 5],
            ['id' => 71, 'name' => 'Filipinas', 'code' => 'PH', 'iso' => 'PHL', 'continent_id' => 3],
            ['id' => 72, 'name' => 'Finlândia', 'code' => 'FI', 'iso' => 'FIN', 'continent_id' => 4],
            ['id' => 73, 'name' => 'França', 'code' => 'FR', 'iso' => 'FRA', 'continent_id' => 4],
            ['id' => 74, 'name' => 'Gabão', 'code' => 'GA', 'iso' => 'GAB', 'continent_id' => 1],
            ['id' => 75, 'name' => 'Gâmbia', 'code' => 'GM', 'iso' => 'GMB', 'continent_id' => 1],
            ['id' => 76, 'name' => 'Gana', 'code' => 'GH', 'iso' => 'GHA', 'continent_id' => 1],
            ['id' => 77, 'name' => 'Geórgia', 'code' => 'GE', 'iso' => 'GEO', 'continent_id' => 3],
            ['id' => 78, 'name' => 'Gibraltar', 'code' => 'GI', 'iso' => 'GIB', 'continent_id' => 4],
            ['id' => 79, 'name' => 'Granada', 'code' => 'GD', 'iso' => 'GRD', 'continent_id' => 2],
            ['id' => 80, 'name' => 'Grécia', 'code' => 'GR', 'iso' => 'GRC', 'continent_id' => 4],
            ['id' => 81, 'name' => 'Groenlândia', 'code' => 'GL', 'iso' => 'GRL', 'continent_id' => 2],
            ['id' => 82, 'name' => 'Guadalupe', 'code' => 'GP', 'iso' => 'GLP', 'continent_id' => 2],
            ['id' => 83, 'name' => 'Guam', 'code' => 'GU', 'iso' => 'GUM', 'continent_id' => 5],
            ['id' => 84, 'name' => 'Guatemala', 'code' => 'GT', 'iso' => 'GTM', 'continent_id' => 2],
            ['id' => 85, 'name' => 'Guiana', 'code' => 'GY', 'iso' => 'GUY', 'continent_id' => 2],
            ['id' => 86, 'name' => 'Guiana Francesa', 'code' => 'GF', 'iso' => 'GUF', 'continent_id' => 2],
            ['id' => 87, 'name' => 'Guiné', 'code' => 'GN', 'iso' => 'GIN', 'continent_id' => 1],
            ['id' => 88, 'name' => 'Guiné Equatorial', 'code' => 'GQ', 'iso' => 'GNQ', 'continent_id' => 1],
            ['id' => 89, 'name' => 'Guiné-Bissau', 'code' => 'GW', 'iso' => 'GNB', 'continent_id' => 1],
            ['id' => 90, 'name' => 'Haiti', 'code' => 'HT', 'iso' => 'HTI', 'continent_id' => 2],
            ['id' => 91, 'name' => 'Honduras', 'code' => 'HN', 'iso' => 'HND', 'continent_id' => 2],
            ['id' => 92, 'name' => 'Hong Kong', 'code' => 'HK', 'iso' => 'HKG', 'continent_id' => 3],
            ['id' => 93, 'name' => 'Hungria', 'code' => 'HU', 'iso' => 'HUN', 'continent_id' => 4],
            ['id' => 94, 'name' => 'Iémen', 'code' => 'YE', 'iso' => 'YEM', 'continent_id' => 3],
            ['id' => 95, 'name' => 'Ilha Bouvet', 'code' => 'BV', 'iso' => 'BVT', 'continent_id' => 1],
            ['id' => 96, 'name' => 'Ilha Christmas', 'code' => 'CX', 'iso' => 'CXR', 'continent_id' => 5],
            ['id' => 97, 'name' => 'Ilha Norfolk', 'code' => 'NF', 'iso' => 'NFK', 'continent_id' => 5],
            ['id' => 98, 'name' => 'Ilhas Caimã', 'code' => 'KY', 'iso' => 'CYM', 'continent_id' => 2],
            ['id' => 99, 'name' => 'Ilhas Cook', 'code' => 'CK', 'iso' => 'COK', 'continent_id' => 5],
            ['id' => 100, 'name' => 'Ilhas Feroé', 'code' => 'FO', 'iso' => 'FRO', 'continent_id' => 4],
            [
                'id' => 101, 'name' => 'Ilhas Geórgia do Sul e Sandwich do Sul', 'code' => 'GS', 'iso' => 'SGS',
                'continent_id' => 2,
            ],
            ['id' => 102, 'name' => 'Ilhas Heard e McDonald', 'code' => 'HM', 'iso' => 'HMD', 'continent_id' => 5],
            ['id' => 103, 'name' => 'Ilhas Malvinas', 'code' => 'FK', 'iso' => 'FLK', 'continent_id' => 2],
            ['id' => 104, 'name' => 'Ilhas Marianas do Norte', 'code' => 'MP', 'iso' => 'MNP', 'continent_id' => 5],
            ['id' => 105, 'name' => 'Ilhas Marshall', 'code' => 'MH', 'iso' => 'MHL', 'continent_id' => 5],
            ['id' => 106, 'name' => 'Ilhas Pitcairn', 'code' => 'PN', 'iso' => 'PCN', 'continent_id' => 5],
            ['id' => 107, 'name' => 'Ilhas Salomão', 'code' => 'SB', 'iso' => 'SLB', 'continent_id' => 5],
            ['id' => 108, 'name' => 'Ilhas Virgens Britânicas', 'code' => 'VG', 'iso' => 'VGB', 'continent_id' => 2],
            [
                'id' => 109, 'name' => 'Ilhas Virgens dos Estados Unidos', 'code' => 'VI', 'iso' => 'VIR',
                'continent_id' => 2,
            ],
            ['id' => 110, 'name' => 'Índia', 'code' => 'IN', 'iso' => 'IND', 'continent_id' => 3],
            ['id' => 111, 'name' => 'Indonésia', 'code' => 'ID', 'iso' => 'IDN', 'continent_id' => 3],
            ['id' => 112, 'name' => 'Irã', 'code' => 'IR', 'iso' => 'IRN', 'continent_id' => 3],
            ['id' => 113, 'name' => 'Iraque', 'code' => 'IQ', 'iso' => 'IRQ', 'continent_id' => 3],
            ['id' => 114, 'name' => 'Irlanda', 'code' => 'IE', 'iso' => 'IRL', 'continent_id' => 4],
            ['id' => 115, 'name' => 'Islândia', 'code' => 'IS', 'iso' => 'ISL', 'continent_id' => 4],
            ['id' => 116, 'name' => 'Israel', 'code' => 'IL', 'iso' => 'ISR', 'continent_id' => 3],
            ['id' => 117, 'name' => 'Itália', 'code' => 'IT', 'iso' => 'ITA', 'continent_id' => 4],
            ['id' => 118, 'name' => 'Jamaica', 'code' => 'JM', 'iso' => 'JAM', 'continent_id' => 2],
            ['id' => 119, 'name' => 'Japão', 'code' => 'JP', 'iso' => 'JPN', 'continent_id' => 3],
            ['id' => 120, 'name' => 'Jordânia', 'code' => 'JO', 'iso' => 'JOR', 'continent_id' => 3],
            ['id' => 121, 'name' => 'Kiribati', 'code' => 'KI', 'iso' => 'KIR', 'continent_id' => 5],
            ['id' => 122, 'name' => 'Kosovo', 'code' => 'XK', 'iso' => 'XKX', 'continent_id' => 4],
            ['id' => 123, 'name' => 'Kuwait', 'code' => 'KW', 'iso' => 'KWT', 'continent_id' => 3],
            ['id' => 124, 'name' => 'Laos', 'code' => 'LA', 'iso' => 'LAO', 'continent_id' => 3],
            ['id' => 125, 'name' => 'Lesoto', 'code' => 'LS', 'iso' => 'LSO', 'continent_id' => 1],
            ['id' => 126, 'name' => 'Letônia', 'code' => 'LV', 'iso' => 'LVA', 'continent_id' => 4],
            ['id' => 127, 'name' => 'Líbano', 'code' => 'LB', 'iso' => 'LBN', 'continent_id' => 3],
            ['id' => 128, 'name' => 'Libéria', 'code' => 'LR', 'iso' => 'LBR', 'continent_id' => 1],
            ['id' => 129, 'name' => 'Líbia', 'code' => 'LY', 'iso' => 'LBY', 'continent_id' => 1],
            ['id' => 130, 'name' => 'Liechtenstein', 'code' => 'LI', 'iso' => 'LIE', 'continent_id' => 4],
            ['id' => 131, 'name' => 'Lituânia', 'code' => 'LT', 'iso' => 'LTU', 'continent_id' => 4],
            ['id' => 132, 'name' => 'Luxemburgo', 'code' => 'LU', 'iso' => 'LUX', 'continent_id' => 4],
            ['id' => 133, 'name' => 'Macau', 'code' => 'MO', 'iso' => 'MAC', 'continent_id' => 3],
            ['id' => 134, 'name' => 'Macedônia do Norte', 'code' => 'MK', 'iso' => 'MKD', 'continent_id' => 4],
            ['id' => 135, 'name' => 'Madagáscar', 'code' => 'MG', 'iso' => 'MDG', 'continent_id' => 1],
            ['id' => 136, 'name' => 'Malásia', 'code' => 'MY', 'iso' => 'MYS', 'continent_id' => 3],
            ['id' => 137, 'name' => 'Malaui', 'code' => 'MW', 'iso' => 'MWI', 'continent_id' => 1],
            ['id' => 138, 'name' => 'Maldivas', 'code' => 'MV', 'iso' => 'MDV', 'continent_id' => 3],
            ['id' => 139, 'name' => 'Mali', 'code' => 'ML', 'iso' => 'MLI', 'continent_id' => 1],
            ['id' => 140, 'name' => 'Malta', 'code' => 'MT', 'iso' => 'MLT', 'continent_id' => 4],
            ['id' => 141, 'name' => 'Marrocos', 'code' => 'MA', 'iso' => 'MAR', 'continent_id' => 1],
            ['id' => 142, 'name' => 'Martinica', 'code' => 'MQ', 'iso' => 'MTQ', 'continent_id' => 2],
            ['id' => 143, 'name' => 'Maurício', 'code' => 'MU', 'iso' => 'MUS', 'continent_id' => 1],
            ['id' => 144, 'name' => 'Mauritânia', 'code' => 'MR', 'iso' => 'MRT', 'continent_id' => 1],
            ['id' => 145, 'name' => 'Mayotte', 'code' => 'YT', 'iso' => 'MYT', 'continent_id' => 1],
            ['id' => 146, 'name' => 'México', 'code' => 'MX', 'iso' => 'MEX', 'continent_id' => 2],
            ['id' => 147, 'name' => 'Micronésia', 'code' => 'FM', 'iso' => 'FSM', 'continent_id' => 5],
            ['id' => 148, 'name' => 'Moçambique', 'code' => 'MZ', 'iso' => 'MOZ', 'continent_id' => 1],
            ['id' => 149, 'name' => 'Moldávia', 'code' => 'MD', 'iso' => 'MDA', 'continent_id' => 4],
            ['id' => 150, 'name' => 'Mônaco', 'code' => 'MC', 'iso' => 'MCO', 'continent_id' => 4],
            ['id' => 151, 'name' => 'Mongólia', 'code' => 'MN', 'iso' => 'MNG', 'continent_id' => 3],
            ['id' => 152, 'name' => 'Montenegro', 'code' => 'ME', 'iso' => 'MNE', 'continent_id' => 4],
            ['id' => 153, 'name' => 'Montserrat', 'code' => 'MS', 'iso' => 'MSR', 'continent_id' => 2],
            ['id' => 154, 'name' => 'Myanmar', 'code' => 'MM', 'iso' => 'MMR', 'continent_id' => 3],
            ['id' => 155, 'name' => 'Namíbia', 'code' => 'NA', 'iso' => 'NAM', 'continent_id' => 1],
            ['id' => 156, 'name' => 'Nauru', 'code' => 'NR', 'iso' => 'NRU', 'continent_id' => 5],
            ['id' => 157, 'name' => 'Nepal', 'code' => 'NP', 'iso' => 'NPL', 'continent_id' => 3],
            ['id' => 158, 'name' => 'Nicarágua', 'code' => 'NI', 'iso' => 'NIC', 'continent_id' => 2],
            ['id' => 159, 'name' => 'Níger', 'code' => 'NE', 'iso' => 'NER', 'continent_id' => 1],
            ['id' => 160, 'name' => 'Nigéria', 'code' => 'NG', 'iso' => 'NGA', 'continent_id' => 1],
            ['id' => 161, 'name' => 'Niue', 'code' => 'NU', 'iso' => 'NIU', 'continent_id' => 5],
            ['id' => 162, 'name' => 'Noruega', 'code' => 'NO', 'iso' => 'NOR', 'continent_id' => 4],
            ['id' => 163, 'name' => 'Nova Caledônia', 'code' => 'NC', 'iso' => 'NCL', 'continent_id' => 5],
            ['id' => 164, 'name' => 'Nova Zelândia', 'code' => 'NZ', 'iso' => 'NZL', 'continent_id' => 5],
            ['id' => 165, 'name' => 'Omã', 'code' => 'OM', 'iso' => 'OMN', 'continent_id' => 3],
            ['id' => 166, 'name' => 'Palau', 'code' => 'PW', 'iso' => 'PLW', 'continent_id' => 5],
            ['id' => 167, 'name' => 'Palestina', 'code' => 'PS', 'iso' => 'PSE', 'continent_id' => 3],
            ['id' => 168, 'name' => 'Panamá', 'code' => 'PA', 'iso' => 'PAN', 'continent_id' => 2],
            ['id' => 169, 'name' => 'Papua Nova Guiné', 'code' => 'PG', 'iso' => 'PNG', 'continent_id' => 5],
            ['id' => 170, 'name' => 'Paquistão', 'code' => 'PK', 'iso' => 'PAK', 'continent_id' => 3],
            ['id' => 171, 'name' => 'Paraguai', 'code' => 'PY', 'iso' => 'PRY', 'continent_id' => 2],
            ['id' => 172, 'name' => 'Peru', 'code' => 'PE', 'iso' => 'PER', 'continent_id' => 2],
            ['id' => 173, 'name' => 'Polinésia Francesa', 'code' => 'PF', 'iso' => 'PYF', 'continent_id' => 5],
            ['id' => 174, 'name' => 'Polônia', 'code' => 'PL', 'iso' => 'POL', 'continent_id' => 4],
            ['id' => 175, 'name' => 'Porto Rico', 'code' => 'PR', 'iso' => 'PRI', 'continent_id' => 2],
            ['id' => 176, 'name' => 'Portugal', 'code' => 'PT', 'iso' => 'PRT', 'continent_id' => 4],
            ['id' => 177, 'name' => 'Qatar', 'code' => 'QA', 'iso' => 'QAT', 'continent_id' => 3],
            ['id' => 178, 'name' => 'Quênia', 'code' => 'KE', 'iso' => 'KEN', 'continent_id' => 1],
            ['id' => 179, 'name' => 'Quirguistão', 'code' => 'KG', 'iso' => 'KGZ', 'continent_id' => 3],
            ['id' => 180, 'name' => 'Reino Unido', 'code' => 'GB', 'iso' => 'GBR', 'continent_id' => 4],
            ['id' => 181, 'name' => 'República Centro-Africana', 'code' => 'CF', 'iso' => 'CAF', 'continent_id' => 1],
            ['id' => 182, 'name' => 'República Dominicana', 'code' => 'DO', 'iso' => 'DOM', 'continent_id' => 2],
            ['id' => 183, 'name' => 'República Tcheca', 'code' => 'CZ', 'iso' => 'CZE', 'continent_id' => 4],
            ['id' => 184, 'name' => 'Romênia', 'code' => 'RO', 'iso' => 'ROU', 'continent_id' => 4],
            ['id' => 185, 'name' => 'Ruanda', 'code' => 'RW', 'iso' => 'RWA', 'continent_id' => 1],
            ['id' => 186, 'name' => 'Rússia', 'code' => 'RU', 'iso' => 'RUS', 'continent_id' => 4],
            ['id' => 187, 'name' => 'Saara Ocidental', 'code' => 'EH', 'iso' => 'ESH', 'continent_id' => 1],
            ['id' => 188, 'name' => 'Samoa', 'code' => 'WS', 'iso' => 'WSM', 'continent_id' => 5],
            ['id' => 189, 'name' => 'Samoa Americana', 'code' => 'AS', 'iso' => 'ASM', 'continent_id' => 5],
            [
                'id' => 190, 'name' => 'Santa Helena, Ascensão e Tristão da Cunha', 'code' => 'SH', 'iso' => 'SHN',
                'continent_id' => 1,
            ],
            ['id' => 191, 'name' => 'Santa Lúcia', 'code' => 'LC', 'iso' => 'LCA', 'continent_id' => 2],
            ['id' => 192, 'name' => 'São Bartolomeu', 'code' => 'BL', 'iso' => 'BLM', 'continent_id' => 2],
            ['id' => 193, 'name' => 'São Cristóvão e Nevis', 'code' => 'KN', 'iso' => 'KNA', 'continent_id' => 2],
            ['id' => 194, 'name' => 'São Marinho', 'code' => 'SM', 'iso' => 'SMR', 'continent_id' => 4],
            ['id' => 195, 'name' => 'São Martinho', 'code' => 'MF', 'iso' => 'MAF', 'continent_id' => 2],
            ['id' => 196, 'name' => 'São Pedro e Miquelão', 'code' => 'PM', 'iso' => 'SPM', 'continent_id' => 2],
            ['id' => 197, 'name' => 'São Tomé e Príncipe', 'code' => 'ST', 'iso' => 'STP', 'continent_id' => 1],
            ['id' => 198, 'name' => 'São Vicente e Granadinas', 'code' => 'VC', 'iso' => 'VCT', 'continent_id' => 2],
            ['id' => 199, 'name' => 'Senegal', 'code' => 'SN', 'iso' => 'SEN', 'continent_id' => 1],
            ['id' => 200, 'name' => 'Serra Leoa', 'code' => 'SL', 'iso' => 'SLE', 'continent_id' => 1],
            ['id' => 201, 'name' => 'Sérvia', 'code' => 'RS', 'iso' => 'SRB', 'continent_id' => 4],
            ['id' => 202, 'name' => 'Síria', 'code' => 'SY', 'iso' => 'SYR', 'continent_id' => 3],
            ['id' => 203, 'name' => 'Somália', 'code' => 'SO', 'iso' => 'SOM', 'continent_id' => 1],
            ['id' => 204, 'name' => 'Sri Lanka', 'code' => 'LK', 'iso' => 'LKA', 'continent_id' => 3],
            ['id' => 205, 'name' => 'Suazilândia', 'code' => 'SZ', 'iso' => 'SWZ', 'continent_id' => 1],
            ['id' => 206, 'name' => 'Sudão', 'code' => 'SD', 'iso' => 'SDN', 'continent_id' => 1],
            ['id' => 207, 'name' => 'Sudão do Sul', 'code' => 'SS', 'iso' => 'SSD', 'continent_id' => 1],
            ['id' => 208, 'name' => 'Suécia', 'code' => 'SE', 'iso' => 'SWE', 'continent_id' => 4],
            ['id' => 209, 'name' => 'Suíça', 'code' => 'CH', 'iso' => 'CHE', 'continent_id' => 4],
            ['id' => 210, 'name' => 'Suriname', 'code' => 'SR', 'iso' => 'SUR', 'continent_id' => 2],
            ['id' => 211, 'name' => 'Tailândia', 'code' => 'TH', 'iso' => 'THA', 'continent_id' => 3],
            ['id' => 212, 'name' => 'Taiwan', 'code' => 'TW', 'iso' => 'TWN', 'continent_id' => 3],
            ['id' => 213, 'name' => 'Tajiquistão', 'code' => 'TJ', 'iso' => 'TJK', 'continent_id' => 3],
            ['id' => 214, 'name' => 'Tanzânia', 'code' => 'TZ', 'iso' => 'TZA', 'continent_id' => 1],
            [
                'id' => 215, 'name' => 'Território Britânico do Oceano Índico', 'code' => 'IO', 'iso' => 'IOT',
                'continent_id' => 1,
            ],
            [
                'id' => 216, 'name' => 'Territórios Franceses do Sul', 'code' => 'TF', 'iso' => 'ATF',
                'continent_id' => 1,
            ],
            ['id' => 217, 'name' => 'Territórios Palestinos', 'code' => 'PS', 'iso' => 'PSE', 'continent_id' => 3],
            ['id' => 218, 'name' => 'Timor-Leste', 'code' => 'TL', 'iso' => 'TLS', 'continent_id' => 3],
            ['id' => 219, 'name' => 'Togo', 'code' => 'TG', 'iso' => 'TGO', 'continent_id' => 1],
            ['id' => 220, 'name' => 'Tokelau', 'code' => 'TK', 'iso' => 'TKL', 'continent_id' => 5],
            ['id' => 221, 'name' => 'Tonga', 'code' => 'TO', 'iso' => 'TON', 'continent_id' => 5],
            ['id' => 222, 'name' => 'Trinidad e Tobago', 'code' => 'TT', 'iso' => 'TTO', 'continent_id' => 2],
            ['id' => 223, 'name' => 'Tunísia', 'code' => 'TN', 'iso' => 'TUN', 'continent_id' => 1],
            ['id' => 224, 'name' => 'Turcomenistão', 'code' => 'TM', 'iso' => 'TKM', 'continent_id' => 3],
            ['id' => 225, 'name' => 'Turquia', 'code' => 'TR', 'iso' => 'TUR', 'continent_id' => 3],
            ['id' => 226, 'name' => 'Tuvalu', 'code' => 'TV', 'iso' => 'TUV', 'continent_id' => 5],
            ['id' => 227, 'name' => 'Ucrânia', 'code' => 'UA', 'iso' => 'UKR', 'continent_id' => 4],
            ['id' => 228, 'name' => 'Uganda', 'code' => 'UG', 'iso' => 'UGA', 'continent_id' => 1],
            ['id' => 229, 'name' => 'Uruguai', 'code' => 'UY', 'iso' => 'URY', 'continent_id' => 2],
            ['id' => 230, 'name' => 'Uzbequistão', 'code' => 'UZ', 'iso' => 'UZB', 'continent_id' => 3],
            ['id' => 231, 'name' => 'Vanuatu', 'code' => 'VU', 'iso' => 'VUT', 'continent_id' => 5],
            ['id' => 232, 'name' => 'Vaticano', 'code' => 'VA', 'iso' => 'VAT', 'continent_id' => 4],
            ['id' => 233, 'name' => 'Venezuela', 'code' => 'VE', 'iso' => 'VEN', 'continent_id' => 2],
            ['id' => 234, 'name' => 'Vietnã', 'code' => 'VN', 'iso' => 'VNM', 'continent_id' => 3],
            ['id' => 235, 'name' => 'Wallis e Futuna', 'code' => 'WF', 'iso' => 'WLF', 'continent_id' => 5],
            ['id' => 236, 'name' => 'Zâmbia', 'code' => 'ZM', 'iso' => 'ZMB', 'continent_id' => 1],
            ['id' => 237, 'name' => 'Zimbábue', 'code' => 'ZW', 'iso' => 'ZWE', 'continent_id' => 1],

        ])->each(function ($country) {
            \DB::table('countries')->insert($country);
        });

        $mozambiqueProvinces = collect([
            ['id' => 1, 'name' => 'Maputo Provincia', 'code' => 'MP', 'country_id' => 148], // Maputo Province
            ['id' => 2, 'name' => 'Cidade de Maputo', 'code' => 'MPM', 'country_id' => 148], // Capital city
            ['id' => 3, 'name' => 'Gaza', 'code' => 'GA', 'country_id' => 148],
            ['id' => 4, 'name' => 'Inhambane', 'code' => 'IN', 'country_id' => 148],
            ['id' => 5, 'name' => 'Sofala', 'code' => 'SO', 'country_id' => 148],
            ['id' => 6, 'name' => 'Manica', 'code' => 'MA', 'country_id' => 148],
            ['id' => 7, 'name' => 'Tete', 'code' => 'TE', 'country_id' => 148],
            ['id' => 8, 'name' => 'Zambezia', 'code' => 'ZA', 'country_id' => 148],
            ['id' => 9, 'name' => 'Nampula', 'code' => 'NA', 'country_id' => 148],
            ['id' => 10, 'name' => 'Cabo Delgado', 'code' => 'CD', 'country_id' => 148],
            ['id' => 11, 'name' => 'Niassa', 'code' => 'NI', 'country_id' => 148],

        ])->each(function ($province) {
            \DB::table('provinces')->insert($province);
        });

        $mozambiqueDistricts = collect([
            // Maputo Provincia (Province)
            ['id' => 1, 'name' => 'Matola', 'province_id' => 1],
            ['id' => 2, 'name' => 'Boane', 'province_id' => 1],
            ['id' => 3, 'name' => 'Magude', 'province_id' => 1],
            ['id' => 4, 'name' => 'Manhiça', 'province_id' => 1],
            ['id' => 5, 'name' => 'Marracuene', 'province_id' => 1],
            ['id' => 6, 'name' => 'Moamba', 'province_id' => 1],
            ['id' => 7, 'name' => 'Namaacha', 'province_id' => 1],
            ['id' => 144, 'name' => 'Matutuine', 'province_id' => 1],

            // Cidade de Maputo (Capital City)
            ['id' => 8, 'name' => 'KaMpfumo', 'province_id' => 2],
            ['id' => 9, 'name' => 'Nhlamankulu', 'province_id' => 2],
            ['id' => 10, 'name' => 'KaMaxakeni', 'province_id' => 2],
            ['id' => 11, 'name' => 'KaMavota', 'province_id' => 2],
            ['id' => 12, 'name' => 'KaMubukwana', 'province_id' => 2],
            ['id' => 13, 'name' => 'KaTembe', 'province_id' => 2],
            ['id' => 14, 'name' => 'KaNyaka', 'province_id' => 2],

            // Gaza
            ['id' => 15, 'name' => 'Xai-Xai', 'province_id' => 3],
            ['id' => 16, 'name' => 'Chibuto', 'province_id' => 3],
            ['id' => 17, 'name' => 'Chicualacuala', 'province_id' => 3],
            ['id' => 18, 'name' => 'Chigubo', 'province_id' => 3],
            ['id' => 19, 'name' => 'Chókwè', 'province_id' => 3],
            ['id' => 20, 'name' => 'Guijá', 'province_id' => 3],
            ['id' => 21, 'name' => 'Limpopo', 'province_id' => 3],
            ['id' => 22, 'name' => 'Mabalane', 'province_id' => 3],
            ['id' => 23, 'name' => 'Manjacaze', 'province_id' => 3],
            ['id' => 24, 'name' => 'Massangena', 'province_id' => 3],
            ['id' => 25, 'name' => 'Massingir', 'province_id' => 3],

            // Inhambane
            ['id' => 26, 'name' => 'Inhambane', 'province_id' => 4],
            ['id' => 27, 'name' => 'Funhalouro', 'province_id' => 4],
            ['id' => 28, 'name' => 'Govuro', 'province_id' => 4],
            ['id' => 29, 'name' => 'Homoine', 'province_id' => 4],
            ['id' => 30, 'name' => 'Inharrime', 'province_id' => 4],
            ['id' => 31, 'name' => 'Inhassoro', 'province_id' => 4],
            ['id' => 32, 'name' => 'Jangamo', 'province_id' => 4],
            ['id' => 33, 'name' => 'Mabote', 'province_id' => 4],
            ['id' => 34, 'name' => 'Massinga', 'province_id' => 4],
            ['id' => 35, 'name' => 'Morrumbene', 'province_id' => 4],
            ['id' => 36, 'name' => 'Panda', 'province_id' => 4],
            ['id' => 37, 'name' => 'Vilanculos', 'province_id' => 4],
            ['id' => 38, 'name' => 'Zavala', 'province_id' => 4],

            // Sofala
            ['id' => 39, 'name' => 'Beira', 'province_id' => 5],
            ['id' => 40, 'name' => 'Buzi', 'province_id' => 5],
            ['id' => 41, 'name' => 'Caia', 'province_id' => 5],
            ['id' => 42, 'name' => 'Chemba', 'province_id' => 5],
            ['id' => 43, 'name' => 'Cheringoma', 'province_id' => 5],
            ['id' => 44, 'name' => 'Chibabava', 'province_id' => 5],
            ['id' => 45, 'name' => 'Dondo', 'province_id' => 5],
            ['id' => 46, 'name' => 'Gorongosa', 'province_id' => 5],
            ['id' => 47, 'name' => 'Machanga', 'province_id' => 5],
            ['id' => 48, 'name' => 'Maringué', 'province_id' => 5],
            ['id' => 49, 'name' => 'Marromeu', 'province_id' => 5],
            ['id' => 50, 'name' => 'Nhamatanda', 'province_id' => 5],

            // Manica
            ['id' => 51, 'name' => 'Chimoio', 'province_id' => 6],
            ['id' => 52, 'name' => 'Barué', 'province_id' => 6],
            ['id' => 53, 'name' => 'Gondola', 'province_id' => 6],
            ['id' => 54, 'name' => 'Guro', 'province_id' => 6],
            ['id' => 55, 'name' => 'Macate', 'province_id' => 6],
            ['id' => 56, 'name' => 'Macossa', 'province_id' => 6],
            ['id' => 57, 'name' => 'Manica', 'province_id' => 6],
            ['id' => 58, 'name' => 'Mossurize', 'province_id' => 6],
            ['id' => 59, 'name' => 'Sussundenga', 'province_id' => 6],
            ['id' => 60, 'name' => 'Tambara', 'province_id' => 6],

            // Tete
            ['id' => 61, 'name' => 'Tete', 'province_id' => 7],
            ['id' => 62, 'name' => 'Angónia', 'province_id' => 7],
            ['id' => 63, 'name' => 'Cahora-Bassa', 'province_id' => 7],
            ['id' => 64, 'name' => 'Changara', 'province_id' => 7],
            ['id' => 65, 'name' => 'Chifunde', 'province_id' => 7],
            ['id' => 66, 'name' => 'Chiuta', 'province_id' => 7],
            ['id' => 67, 'name' => 'Macanga', 'province_id' => 7],
            ['id' => 68, 'name' => 'Magoé', 'province_id' => 7],
            ['id' => 69, 'name' => 'Marara', 'province_id' => 7],
            ['id' => 70, 'name' => 'Marávia', 'province_id' => 7],
            ['id' => 71, 'name' => 'Moatize', 'province_id' => 7],
            ['id' => 72, 'name' => 'Mutarara', 'province_id' => 7],
            ['id' => 73, 'name' => 'Tsangano', 'province_id' => 7],
            ['id' => 74, 'name' => 'Zumbo', 'province_id' => 7],

            // Zambezia
            ['id' => 75, 'name' => 'Quelimane', 'province_id' => 8],
            ['id' => 76, 'name' => 'Alto Molócuè', 'province_id' => 8],
            ['id' => 77, 'name' => 'Chinde', 'province_id' => 8],
            ['id' => 78, 'name' => 'Gilé', 'province_id' => 8],
            ['id' => 79, 'name' => 'Gurué', 'province_id' => 8],
            ['id' => 80, 'name' => 'Ile', 'province_id' => 8],
            ['id' => 81, 'name' => 'Inhassunge', 'province_id' => 8],
            ['id' => 82, 'name' => 'Luabo', 'province_id' => 8],
            ['id' => 83, 'name' => 'Lugela', 'province_id' => 8],
            ['id' => 84, 'name' => 'Maganja da Costa', 'province_id' => 8],
            ['id' => 85, 'name' => 'Milange', 'province_id' => 8],
            ['id' => 86, 'name' => 'Mocuba', 'province_id' => 8],
            ['id' => 87, 'name' => 'Mopeia', 'province_id' => 8],
            ['id' => 88, 'name' => 'Morrumbala', 'province_id' => 8],
            ['id' => 89, 'name' => 'Namacurra', 'province_id' => 8],
            ['id' => 90, 'name' => 'Namarroi', 'province_id' => 8],
            ['id' => 91, 'name' => 'Nicoadala', 'province_id' => 8],
            ['id' => 92, 'name' => 'Pebane', 'province_id' => 8],

            // Nampula
            ['id' => 93, 'name' => 'Nampula', 'province_id' => 9],
            ['id' => 94, 'name' => 'Angoche', 'province_id' => 9],
            ['id' => 95, 'name' => 'Eráti', 'province_id' => 9],
            ['id' => 96, 'name' => 'Ilha de Moçambique', 'province_id' => 9],
            ['id' => 97, 'name' => 'Lalaua', 'province_id' => 9],
            ['id' => 98, 'name' => 'Malema', 'province_id' => 9],
            ['id' => 99, 'name' => 'Meconta', 'province_id' => 9],
            ['id' => 100, 'name' => 'Mecubúri', 'province_id' => 9],
            ['id' => 101, 'name' => 'Memba', 'province_id' => 9],
            ['id' => 102, 'name' => 'Mogincual', 'province_id' => 9],
            ['id' => 103, 'name' => 'Mogovolas', 'province_id' => 9],
            ['id' => 104, 'name' => 'Moma', 'province_id' => 9],
            ['id' => 105, 'name' => 'Monapo', 'province_id' => 9],
            ['id' => 106, 'name' => 'Mossuril', 'province_id' => 9],
            ['id' => 107, 'name' => 'Muecate', 'province_id' => 9],
            ['id' => 108, 'name' => 'Murrupula', 'province_id' => 9],
            ['id' => 109, 'name' => 'Nacala', 'province_id' => 9],
            ['id' => 110, 'name' => 'Nacaroa', 'province_id' => 9],
            ['id' => 111, 'name' => 'Rapale', 'province_id' => 9],
            ['id' => 112, 'name' => 'Ribáuè', 'province_id' => 9],

            // Cabo Delgado
            ['id' => 113, 'name' => 'Pemba', 'province_id' => 10],
            ['id' => 114, 'name' => 'Ancuabe', 'province_id' => 10],
            ['id' => 115, 'name' => 'Balama', 'province_id' => 10],
            ['id' => 116, 'name' => 'Chiúre', 'province_id' => 10],
            ['id' => 117, 'name' => 'Ibo', 'province_id' => 10],
            ['id' => 118, 'name' => 'Macomia', 'province_id' => 10],
            ['id' => 119, 'name' => 'Mecúfi', 'province_id' => 10],
            ['id' => 120, 'name' => 'Meluco', 'province_id' => 10],
            ['id' => 121, 'name' => 'Mocímboa da Praia', 'province_id' => 10],
            ['id' => 122, 'name' => 'Montepuez', 'province_id' => 10],
            ['id' => 123, 'name' => 'Mueda', 'province_id' => 10],
            ['id' => 124, 'name' => 'Muidumbe', 'province_id' => 10],
            ['id' => 125, 'name' => 'Namuno', 'province_id' => 10],
            ['id' => 126, 'name' => 'Nangade', 'province_id' => 10],
            ['id' => 127, 'name' => 'Palma', 'province_id' => 10],
            ['id' => 128, 'name' => 'Quissanga', 'province_id' => 10],

            // Niassa
            ['id' => 129, 'name' => 'Lichinga', 'province_id' => 11],
            ['id' => 130, 'name' => 'Chimbonila', 'province_id' => 11],
            ['id' => 131, 'name' => 'Cuamba', 'province_id' => 11],
            ['id' => 132, 'name' => 'Lago', 'province_id' => 11],
            ['id' => 133, 'name' => 'Lichinga', 'province_id' => 11],
            ['id' => 134, 'name' => 'Majune', 'province_id' => 11],
            ['id' => 135, 'name' => 'Mandimba', 'province_id' => 11],
            ['id' => 136, 'name' => 'Marrupa', 'province_id' => 11],
            ['id' => 137, 'name' => 'Maua', 'province_id' => 11],
            ['id' => 138, 'name' => 'Mavago', 'province_id' => 11],
            ['id' => 139, 'name' => 'Mecula', 'province_id' => 11],
            ['id' => 140, 'name' => 'Metarica', 'province_id' => 11],
            ['id' => 141, 'name' => 'Muembe', 'province_id' => 11],
            ['id' => 142, 'name' => 'N\'gauma', 'province_id' => 11],
            ['id' => 143, 'name' => 'Sanga', 'province_id' => 11],
        ]);

        $mozambiqueDistricts->each(function ($district) {
            try {
                \DB::table('districts')->insert($district);
            } catch (Exception $e) {
                Log::error('Error inserting district: '.$district['name'].'. Error: '.$e->getMessage());
            }
        });

        // Medical Specialities
        $specialties = [
            ['name' => 'Medicina Geral', 'code' => 'MG', 'sort_order' => 1],
            ['name' => 'Pediatria', 'code' => 'PED', 'sort_order' => 2],
            ['name' => 'Ginecologia e Obstetrícia', 'code' => 'GO', 'sort_order' => 3],
            ['name' => 'Cirurgia Geral', 'code' => 'CG', 'sort_order' => 4],
            ['name' => 'Medicina Interna', 'code' => 'MIN', 'sort_order' => 5],
            ['name' => 'Cardiologia', 'code' => 'CAR', 'sort_order' => 6],
            ['name' => 'Ortopedia', 'code' => 'ORT', 'sort_order' => 7],
            ['name' => 'Oftalmologia', 'code' => 'OFT', 'sort_order' => 8],
            ['name' => 'Psiquiatria', 'code' => 'PSI', 'sort_order' => 9],
            ['name' => 'Dermatologia', 'code' => 'DER', 'sort_order' => 10],
            ['name' => 'Neurologia', 'code' => 'NEU', 'sort_order' => 11],
            ['name' => 'Urologia', 'code' => 'URO', 'sort_order' => 12],
            ['name' => 'Otorrinolaringologia', 'code' => 'ORL', 'sort_order' => 13],
            ['name' => 'Anestesiologia', 'code' => 'ANE', 'sort_order' => 14],
            ['name' => 'Radiologia', 'code' => 'RAD', 'sort_order' => 15],
            ['name' => 'Medicina Familiar', 'code' => 'MF', 'sort_order' => 16],
            ['name' => 'Medicina Tropical', 'code' => 'MT', 'sort_order' => 17],
            ['name' => 'Pneumologia', 'code' => 'PNE', 'sort_order' => 18],
            ['name' => 'Nefrologia', 'code' => 'NEF', 'sort_order' => 19],
            ['name' => 'Oncologia', 'code' => 'ONC', 'sort_order' => 20],
            ['name' => 'Endocrinologia', 'code' => 'END', 'sort_order' => 21],
            ['name' => 'Gastroenterologia', 'code' => 'GAS', 'sort_order' => 22],
            ['name' => 'Hematologia', 'code' => 'HEM', 'sort_order' => 23],
            ['name' => 'Doenças Infecciosas', 'code' => 'DI', 'sort_order' => 24],
            ['name' => 'Medicina Intensiva', 'code' => 'MINT', 'sort_order' => 25],
            ['name' => 'Reumatologia', 'code' => 'REU', 'sort_order' => 26],
        ];

        foreach ($specialties as $specialty) {
            \App\Models\MedicalSpeciality::updateOrCreate(
                ['code' => $specialty['code']],
                $specialty
            );
        }
    }

    private function tablesExist(): bool
    {
        try {
            // Check if required tables exist
            $requiredTables = ['genders', 'civil_states', 'continents', 'countries', 'medical_specialities'];

            foreach ($requiredTables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    return false;
                }
            }

            return true;
        } catch (\Exception $e) {
            // If there's any error checking tables, assume they don't exist yet
            return false;
        }
    }

    private function alreadyRun()
    {
        return DB::table('continents')->count() > 0;
    }
}
