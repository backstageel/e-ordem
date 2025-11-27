<?php

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadResultsAction
{
    public function execute(Exam $exam, UploadedFile $file): array
    {
        return DB::transaction(function () use ($exam, $file) {
            // Validate file type
            $extension = $file->getClientOriginalExtension();
            $allowedExtensions = ['csv', 'xlsx', 'xls'];

            if (! in_array(strtolower($extension), $allowedExtensions)) {
                throw new \Exception('Formato de ficheiro não suportado. Use CSV ou Excel.');
            }

            // Store file in private storage
            $filename = 'exam_results_'.$exam->id.'_'.time().'.'.$extension;
            $path = $file->storeAs('exam_results', $filename, 'local');

            // Parse file based on extension
            $results = $this->parseFile($path, $extension);

            // Validate results structure
            $validated = $this->validateResults($exam, $results);

            return [
                'file_path' => $path,
                'filename' => $filename,
                'results' => $validated,
                'total_records' => count($validated),
            ];
        });
    }

    private function parseFile(string $path, string $extension): array
    {
        $fullPath = Storage::disk('local')->path($path);

        if ($extension === 'csv') {
            return $this->parseCsv($fullPath);
        } else {
            return $this->parseExcel($fullPath);
        }
    }

    private function parseCsv(string $path): array
    {
        $results = [];
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new \Exception('Não foi possível ler o ficheiro CSV.');
        }

        // Read header
        $headers = fgetcsv($handle);

        // Read data rows
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($headers)) {
                continue; // Skip invalid rows
            }

            $result = array_combine($headers, $row);
            $results[] = $result;
        }

        fclose($handle);

        return $results;
    }

    private function parseExcel(string $path): array
    {
        // This would require PhpSpreadsheet package
        // For now, return empty array with a note that Excel parsing needs implementation
        throw new \Exception('Análise de ficheiros Excel requer o pacote PhpSpreadsheet. Por favor, use CSV por enquanto.');
    }

    private function validateResults(Exam $exam, array $results): array
    {
        $validated = [];

        foreach ($results as $index => $result) {
            // Expected columns: application_id, grade, status
            if (! isset($result['application_id'])) {
                continue; // Skip invalid rows
            }

            $applicationId = (int) $result['application_id'];

            // Verify application belongs to this exam
            $application = \App\Models\ExamApplication::where('id', $applicationId)
                ->where('exam_id', $exam->id)
                ->first();

            if (! $application) {
                continue; // Skip if application not found or doesn't belong to exam
            }

            $validated[] = [
                'application_id' => $applicationId,
                'grade' => isset($result['grade']) ? (float) $result['grade'] : null,
                'status' => $result['status'] ?? 'ausente',
                'notes' => $result['notes'] ?? null,
            ];
        }

        return $validated;
    }
}
