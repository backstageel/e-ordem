<?php

namespace Database\States;

use Illuminate\Support\Facades\DB;

class EnsureDocumentTypesArePresent
{
    public function __invoke()
    {
        // Check if required tables exist before proceeding
        if (!$this->tablesExist()) {
            return;
        }

        if ($this->present()) {
            return;
        }

        $documentTypes = [];
        // Base documents (always required)
        $documentTypes[] = [
            'code' => 'registration_form',
            'name' => 'Formulário de pedido de inscrição preenchido',
            'description' => 'Formulário de inscrição na Ordem dos Médicos de Moçambique',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 1,
            'instructions' => 'Formulário preenchido e assinado',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'identity_document',
            'name' => 'Fotocópia do documento de identificação',
            'description' => 'BI, DIRE ou Passaporte autenticado',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 2,
            'instructions' => 'Documento deve estar autenticado e válido',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'passport_photos',
            'name' => 'Duas (2) fotografias tipo passe',
            'description' => 'Fotografias recentes para identificação',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => false,
            'order' => 3,
            'instructions' => 'Duas fotografias recentes, tipo passe',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'diploma',
            'name' => 'Cópia autenticada do certificado do curso',
            'description' => 'Diploma ou certificado do curso autenticado',
            'is_required' => true,
            'requires_translation' => true,
            'requires_validation' => true,
            'order' => 4,
            'instructions' => 'Documento deve estar autenticado e traduzido se necessário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'curriculum_vitae',
            'name' => 'Curriculum Vitae',
            'description' => 'Curriculum Vitae elaborado e instruído',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 5,
            'instructions' => 'CV completo e atualizado',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'nuit',
            'name' => 'Fotocópia do cartão ou Declaração do NUIT',
            'description' => 'Número Único de Identificação Tributária',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 6,
            'instructions' => 'Documento deve estar autenticado',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'criminal_record',
            'name' => 'Certificado de Registo Criminal',
            'description' => 'Certidão de registo criminal',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 7,
            'instructions' => 'Documento deve estar atualizado (máximo 90 dias)',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Payment documents
        $documentTypes[] = [
            'code' => 'processing_fee_payment',
            'name' => 'Comprovativo de pagamento de taxa de tramitação',
            'description' => 'Comprovativo de pagamento da taxa de tramitação',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 50,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'examination_fee_payment',
            'name' => 'Comprovativo de pagamento de taxa de exame',
            'description' => 'Comprovativo de pagamento da taxa de exame (pagos após a autorização para inscrição)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 51,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'membership_fee_payment',
            'name' => 'Comprovativo de pagamento da jóia, quota e cartão da OrMM',
            'description' => 'Comprovativo de pagamento da jóia, quota e cartão da OrMM (pagos após a autorização do Conselho Directivo Nacional para inscrição)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 52,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'provisional_registration_fee_payment',
            'name' => 'Comprovativo de pagamento da taxa da inscrição provisória',
            'description' => 'Comprovativo de pagamento da taxa da inscrição provisória (paga após autorização da inscrição)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 53,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'ormm_card_payment',
            'name' => 'Comprovativo de pagamento do cartão da OrMM',
            'description' => 'Comprovativo de pagamento do cartão da OrMM',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 54,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'specialty_examination_fee_payment',
            'name' => 'Comprovativo de pagamento da taxa de exame de certificação de especialidade',
            'description' => 'Comprovativo de pagamento da taxa de exame de certificação de especialidade',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 55,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'college_enrollment_fee_payment',
            'name' => 'Comprovativo de pagamento de taxa de inscrição no colégio e cartão',
            'description' => 'Comprovativo de pagamento de taxa de inscrição no colégio de especialidade e cartão',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 56,
            'instructions' => 'Comprovativo de pagamento bancário',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Academic documents
        $documentTypes[] = [
            'code' => 'curriculum_program',
            'name' => 'Programa curricular da formação',
            'description' => 'Programa curricular com carga horária da formação',
            'is_required' => false,
            'requires_translation' => true,
            'requires_validation' => true,
            'order' => 20,
            'instructions' => 'Obrigatório para formados no exterior',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'specialty_certificate',
            'name' => 'Certificado de especialidade',
            'description' => 'Certificado do curso de especialidade, para inscrição no respectivo colégio de especialidade',
            'is_required' => false,
            'requires_translation' => true,
            'requires_validation' => true,
            'order' => 21,
            'instructions' => 'Para especialistas',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'specialty_certificate_validated',
            'name' => 'Certificado do curso de especialidade validado',
            'description' => 'Certificado do curso de especialidade verificado/validado por instituição internacional de verificação indicada pelo Conselho de Certificação da Ordem dos Médicos de Moçambique',
            'is_required' => false,
            'requires_translation' => true,
            'requires_validation' => true,
            'order' => 22,
            'instructions' => 'Obrigatório para especialistas formados no exterior',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'accreditation_certificate',
            'name' => 'Comprovativo de acreditação da instituição',
            'description' => 'Comprovativo de acreditação da instituição que emitiu o diploma, pela Ordem dos Médicos ou o regulador do seu país',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 23,
            'instructions' => 'Obrigatório para formados no exterior',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'education_ministry_recognition',
            'name' => 'Carta de reconhecimento do programa de estudos',
            'description' => 'Carta de reconhecimento do programa de estudos pelo Ministério que tutela o ensino superior no país em que fez a licenciatura',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 24,
            'instructions' => 'Obrigatório para formados no exterior',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Professional documents
        $documentTypes[] = [
            'code' => 'professional_license',
            'name' => 'Cartão ou cédula profissional',
            'description' => 'Cartão ou cédula profissional do médico convidado, reconhecido na Embaixada de Moçambique no país de origem',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 30,
            'instructions' => 'Obrigatório para especialistas estrangeiros',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'good_standing_declaration',
            'name' => 'Certificado de Idoneidade',
            'description' => 'Certificado de Idoneidade emitido pela entidade competente do país de origem atestando condições legais para exercer a profissão sem restrições',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 31,
            'instructions' => 'Obrigatório para estrangeiros',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'experience_10_years_proof',
            'name' => 'Comprovativo de exercício médico especializado',
            'description' => 'Comprovativo de exercício médico especializado de pelo menos 10 anos, emitido pelo órgão regulador da profissão médica do país de origem ou exercício',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 32,
            'instructions' => 'Mínimo 10 anos de experiência',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'teaching_5_years_proof',
            'name' => 'Comprovativo de exercício da docência em formação médica especializada',
            'description' => 'Comprovativo de exercício da docência em formação médica especializada de pelo menos 05 anos, emitido pela instituição onde a exerceu',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 33,
            'instructions' => 'Mínimo 5 anos de docência',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'recommendation_letter',
            'name' => 'Carta de recomendação',
            'description' => 'Carta de recomendação passada pelo responsável máximo da instituição onde trabalhou como docente em formação médica especializada',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 34,
            'instructions' => 'Carta de recomendação oficial',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'recommendation_letters',
            'name' => 'Cartas de recomendação',
            'description' => 'Duas cartas de recomendação passadas pelos responsáveis máximos das instituições onde trabalhou como médico especialista',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 35,
            'instructions' => 'Duas cartas de recomendação oficiais',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'employer_reference',
            'name' => 'Carta de referência da instituição empregadora',
            'description' => 'Carta de referência da instituição empregadora, devendo conter informação sobre o comportamento',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 36,
            'instructions' => 'Carta de referência com informações comportamentais',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Provisional registration specific documents
        $documentTypes[] = [
            'code' => 'invitation_letter',
            'name' => 'Carta convite',
            'description' => 'Carta convite (contendo o tipo, datas de início e fim, bem como o local das actividades a serem realizadas)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 40,
            'instructions' => 'Carta convite oficial com datas e local',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'supervisor_indication',
            'name' => 'Indicação do médico supervisor',
            'description' => 'Indicação do médico moçambicano ou equipe de médicos moçambicanos que irão acompanhar e supervisionar as actividades em Moçambique',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 41,
            'instructions' => 'Documento com indicação do supervisor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'supervisor_declaration',
            'name' => 'Declaração escrita do médico supervisor',
            'description' => 'Declaração escrita do médico supervisor, aceitando supervisionar as actividades',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 42,
            'instructions' => 'Declaração oficial do supervisor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'supervisor_ormm_card',
            'name' => 'Cópia do cartão da OrMM do médico supervisor',
            'description' => 'Cópia do cartão da OrMM do médico supervisor',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 43,
            'instructions' => 'Cópia do cartão do supervisor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'ethics_course_certificate',
            'name' => 'Certificado de curso de ética médica',
            'description' => 'Certificado de curso de ética médica (realizado nos últimos 24 meses)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 44,
            'instructions' => 'Válido por 24 meses',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'portuguese_proficiency',
            'name' => 'Comprovativo de proficiência em língua portuguesa',
            'description' => 'Comprovativo de proficiência em língua portuguesa com menos de 2 anos, se a língua de ensino não tiver sido a portuguesa',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 45,
            'instructions' => 'Válido por 2 anos',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'conformity_declaration',
            'name' => 'Declaração de conformidade curricular e documental',
            'description' => 'Declaração de conformidade curricular e documental passada pelo Colégio de Especialidade e Conselho de Certificação',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 46,
            'instructions' => 'Emitida após avaliação documental',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'competence_verification',
            'name' => 'Comprovativo de verificação de competências',
            'description' => 'Comprovativo pelo Colégio de Especialidade afim da inexistência ou indisponibilidade de moçambicanos com iguais ou melhores competências na área em questão',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 47,
            'instructions' => 'Emitida após avaliação documental',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Trainee specific documents
        $documentTypes[] = [
            'code' => 'health_ministry_letter',
            'name' => 'Carta do Ministério da Saúde',
            'description' => 'Carta do Ministério da Saúde do país de origem comprometendo-se pelo regresso do formando após fim da residência médica',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 48,
            'instructions' => 'Carta oficial do Ministério da Saúde',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'cnrm_acceptance',
            'name' => 'Carta de aceitação da Comissão Nacional de Residências Médicas',
            'description' => 'Carta de aceitação da CNRM com especialidade, instituição de formação e data prevista para início da residência médica especializada',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 49,
            'instructions' => 'Carta oficial da CNRM',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'reciprocity_declaration',
            'name' => 'Declaração de reciprocidade',
            'description' => 'Declaração de reciprocidade emitida pelo órgão regulador da profissão médica do país de origem garantindo que os médicos moçambicanos têm permissão para prática médica naquele país',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 50,
            'instructions' => 'Declaração oficial de reciprocidade',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Research specific documents
        $documentTypes[] = [
            'code' => 'ethics_exam_certificate',
            'name' => 'Aprovação no exame de ética e bioética',
            'description' => 'Aprovação no exame de ética e bioética em investigação científica, realizado pela OrMM',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 60,
            'instructions' => 'Certificado de aprovação no exame',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'ethics_approval',
            'name' => 'Comprovativo de aprovação do protocolo de investigação',
            'description' => 'Comprovativo de aprovação do protocolo de investigação pelo Comité Nacional de Bioética em Saúde de Moçambique',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 61,
            'instructions' => 'Aprovação oficial do protocolo',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'research_protocol',
            'name' => 'Cópia do protocolo de investigação científica',
            'description' => 'Cópia do protocolo de investigação científica',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 62,
            'instructions' => 'Protocolo completo de investigação',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'publication_evidence',
            'name' => 'Comprovativo de publicação de artigos científicos',
            'description' => 'Comprovativo de publicação de pelo menos 2 artigos científicos em revistas indexadas como investigador principal, nos últimos 5 anos',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 63,
            'instructions' => 'Mínimo 2 artigos nos últimos 5 anos',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'supervisor_curriculum',
            'name' => 'Curriculum Vitae do tutor da pesquisa',
            'description' => 'Curriculum Vitae do tutor da pesquisa elaborado e instruído de forma a comprovar o exercício da investigação médica, se aplicável',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 64,
            'instructions' => 'CV completo do tutor',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'institution_recommendation',
            'name' => 'Carta de recomendação da instituição',
            'description' => 'Carta de recomendação passada pelo responsável máximo da instituição onde trabalha como investigador',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 65,
            'instructions' => 'Carta oficial da instituição',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Work visa and employment documents
        $documentTypes[] = [
            'code' => 'work_visa',
            'name' => 'Visto de trabalho',
            'description' => 'Visto de trabalho',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 70,
            'instructions' => 'Visto de trabalho válido',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'employment_contract_promise',
            'name' => 'Contrato-promessa de trabalho',
            'description' => 'Contrato-promessa de trabalho',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 71,
            'instructions' => 'Contrato-promessa oficial',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $documentTypes[] = [
            'code' => 'health_ministry_authorization',
            'name' => 'Carta de autorização do Ministério da Saúde',
            'description' => 'Carta de autorização passada pelo Ministério da Saúde do país de origem que autoriza a sua candidatura para inscrição para exercício da Medicina em Moçambique',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 72,
            'instructions' => 'Carta oficial do Ministério da Saúde',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Liability insurance
        $documentTypes[] = [
            'code' => 'liability_insurance',
            'name' => 'Seguro de responsabilidade civil',
            'description' => 'Seguro de responsabilidade civil da instituição que o contrata, pelos eventuais danos sobre os pacientes',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 80,
            'instructions' => 'Seguro válido e atualizado',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Verification certificate
        $documentTypes[] = [
            'code' => 'verification_certificate',
            'name' => 'Certificado de verificação',
            'description' => 'Certificado do curso validado por instituição internacional de verificação indicada pelo Conselho de Certificação da Ordem dos Médicos de Moçambique',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 81,
            'instructions' => 'Certificado de verificação internacional',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // OrMM card copy
        $documentTypes[] = [
            'code' => 'ormm_card_copy',
            'name' => 'Fotocópia do cartão da Ordem dos Médicos de Moçambique',
            'description' => 'Fotocópia do cartão da Ordem dos Médicos de Moçambique',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 82,
            'instructions' => 'Cópia do cartão válido',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Regular status declaration
        $documentTypes[] = [
            'code' => 'regular_status_declaration',
            'name' => 'Declaração de situação regular na OrMM',
            'description' => 'Declaração de situação regular na OrMM',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 83,
            'instructions' => 'Declaração oficial da OrMM',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Specialty college authorization
        $documentTypes[] = [
            'code' => 'specialty_college_authorization',
            'name' => 'Autorização do Colégio de Especialidade',
            'description' => 'Autorização do Colégio de Especialidade para conformidade curricular e documental para realização do exame de certificação',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 84,
            'instructions' => 'Autorização oficial do colégio',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Supervisor responsibility term
        $documentTypes[] = [
            'code' => 'supervisor_responsibility_term',
            'name' => 'Termo de responsabilidade do médico supervisor',
            'description' => 'Termo de responsabilidade do médico supervisor',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 85,
            'instructions' => 'Termo oficial de responsabilidade',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Specialized training program
        $documentTypes[] = [
            'code' => 'specialized_training_program',
            'name' => 'Programa de formação com horas e anos',
            'description' => 'Programa de formação com horas e anos que o curso durou, conforme requisitos dos colégios ou do Conselho Nacional para Colégios de Especialidades',
            'is_required' => false,
            'requires_translation' => true,
            'requires_validation' => true,
            'order' => 86,
            'instructions' => 'Programa detalhado com carga horária',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Additional document from DocumentSeeder
        $documentTypes[] = [
            'code' => 'residence_proof',
            'name' => 'Comprovante de Residência',
            'description' => 'Comprovante de endereço residencial',
            'is_required' => true,
            'requires_translation' => false,
            'requires_validation' => false,
            'order' => 87,
            'instructions' => 'Comprovante de endereço válido',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Payment proof document (used in controllers)
        $documentTypes[] = [
            'code' => 'payment_proof',
            'name' => 'Comprovativo de pagamento',
            'description' => 'Comprovativo de pagamento (proof of payment)',
            'is_required' => false,
            'requires_translation' => false,
            'requires_validation' => true,
            'order' => 88,
            'instructions' => 'Comprovativo de pagamento bancário ou recibo',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Insert all document types using updateOrInsert to avoid duplicates
        foreach ($documentTypes as $documentType) {
            DB::table('document_types')->updateOrInsert(
                ['code' => $documentType['code']],
                $documentType
            );
        }

    }

    private function tablesExist(): bool
    {
        try {
            // Check if required tables exist
            if (!DB::getSchemaBuilder()->hasTable('document_types')) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            // If there's any error checking tables, assume they don't exist yet
            return false;
        }
    }

    private function present()
    {
        return DB::table('document_types')->count() > 0;
    }
}
