<?php

namespace App\Console\Commands;

use App\Models\DocumentType;
use Modules\Registration\Models\RegistrationType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDocumentChecklists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:sync-checklists {--registration-type= : Sync for specific registration type code} {--force : Force update existing checklists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize document checklists based on registration types required_documents';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sincronizando checklists de documentos...');

        $query = RegistrationType::query();
        if ($this->option('registration-type')) {
            $query->where('code', $this->option('registration-type'));
        }

        $registrationTypes = $query->get();

        if ($registrationTypes->isEmpty()) {
            $this->warn('Nenhum tipo de inscrição encontrado.');

            return self::FAILURE;
        }

        $totalSynced = 0;
        $totalCreated = 0;
        $totalUpdated = 0;

        foreach ($registrationTypes as $registrationType) {
            $this->info("Processando: {$registrationType->name} ({$registrationType->code})");

            $requiredDocuments = $registrationType->required_documents ?? [];
            if (empty($requiredDocuments)) {
                $this->warn('  ⚠️  Tipo de inscrição não possui required_documents definido.');

                continue;
            }

            $order = 0;
            foreach ($requiredDocuments as $documentCode) {
                $order++;

                // Find document type by code
                $documentType = DocumentType::where('code', $documentCode)->first();

                if (! $documentType) {
                    $this->warn("  ⚠️  DocumentType com código '{$documentCode}' não encontrado. Pulando...");

                    continue;
                }

                // Check if checklist entry already exists
                $checklist = DB::table('document_checklists')
                    ->where('registration_type_id', $registrationType->id)
                    ->where('document_type_id', $documentType->id)
                    ->first();

                $data = [
                    'registration_type_id' => $registrationType->id,
                    'document_type_id' => $documentType->id,
                    'is_required' => true,
                    'requires_translation' => $documentType->requires_translation ?? false,
                    'requires_validation' => $documentType->requires_validation ?? true,
                    'order' => $order,
                    'instructions' => $documentType->instructions,
                    'is_active' => $documentType->is_active ?? true,
                    'updated_at' => now(),
                ];

                if ($checklist) {
                    if ($this->option('force')) {
                        DB::table('document_checklists')
                            ->where('id', $checklist->id)
                            ->update($data);
                        $totalUpdated++;
                        $this->line("  ✓ Atualizado: {$documentType->name}");
                    } else {
                        $this->line("  ⊘ Já existe: {$documentType->name} (use --force para atualizar)");
                    }
                } else {
                    $data['created_at'] = now();
                    DB::table('document_checklists')->insert($data);
                    $totalCreated++;
                    $this->line("  + Criado: {$documentType->name}");
                }
                $totalSynced++;
            }

            $this->newLine();
        }

        $this->info('✓ Sincronização concluída!');
        $this->table(
            ['Métrica', 'Total'],
            [
                ['Total processado', $totalSynced],
                ['Criados', $totalCreated],
                ['Atualizados', $totalUpdated],
                ['Já existiam', $totalSynced - $totalCreated - $totalUpdated],
            ]
        );

        return self::SUCCESS;
    }
}
