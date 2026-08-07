<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAccessLog;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class DocumentWordController extends Controller
{
public function __invoke(Document $documento)
{
    $turmasDoProfesor = auth()->user()->schoolClasses()->pluck('school_classes.id');

    $alunoNaTurma = \App\Models\SchoolClass::withoutGlobalScopes()
        ->whereIn('id', $turmasDoProfesor)
        ->whereHas('students', fn($q) => $q->withoutGlobalScopes()->where('students.id', $documento->student_id))
        ->exists();

    if (! $alunoNaTurma) abort(403);

    $documento->load('student', 'author');

    DocumentAccessLog::create([
        'school_id'     => $documento->school_id,
        'document_id'   => $documento->id,
        'student_id'    => $documento->student_id,
        'user_id'       => auth()->id(),
        'action'        => 'exported',
        'document_type' => $documento->type,
        'document_year' => $documento->year,
        'student_name'  => $documento->student?->name,
        'ip'            => request()->ip(),
        'accessed_at'   => now(),
    ]);

    return $this->gerarWord($documento);
}


    public function gerarWord(Document $documento)
{
    $word = new PhpWord();
    $word->setDefaultFontName('Arial');
    $word->setDefaultFontSize(11);

    $word->addTitleStyle(1, ['bold' => true, 'size' => 16, 'color' => '004B8D', 'name' => 'Arial']);
    $word->addTitleStyle(2, ['bold' => true, 'size' => 12, 'color' => '374151', 'name' => 'Arial']);

    $section = $word->addSection([
        'marginTop' => 1200, 'marginBottom' => 1200,
        'marginLeft' => 1200, 'marginRight' => 1200,
    ]);

    $header = $section->addHeader();
    $headerTable = $header->addTable();
    $headerTable->addRow();
    $headerTable->addCell(7000)->addText('ÁTRIO — Sistema de Gestão Educacional Inclusiva', ['bold' => true, 'size' => 10, 'color' => '004B8D', 'name' => 'Arial']);
    $headerTable->addCell(3000)->addText(date('d/m/Y'), ['size' => 10, 'color' => '9CA3AF', 'name' => 'Arial'], ['alignment' => 'right']);

    $footer = $section->addFooter();
    $footer->addText('Documento confidencial — LGPD · Gerado em ' . now()->format('d/m/Y \à\s H:i'), ['size' => 9, 'color' => '9CA3AF', 'name' => 'Arial'], ['alignment' => 'center']);

    $section->addTitle(strtoupper(str_replace('_', ' ', $documento->type)), 1);
    $section->addTextBreak(1);

    $metaTable = $section->addTable(['borderSize' => 0, 'cellMargin' => 80]);
    $metas = [
        'Aluno'         => $documento->student->name,
        'Matrícula'     => $documento->student->registration_number,
        'Ano letivo'    => $documento->year,
        'Elaborado por' => $documento->author->name,
        'Data'          => $documento->created_at->format('d/m/Y'),
    ];
    if ($documento->student->condition) {
        $metas['Condição'] = $documento->student->condition;
    }
    foreach ($metas as $label => $value) {
        $metaTable->addRow();
        $metaTable->addCell(2500, ['bgColor' => 'F3F4F6'])->addText($label, ['bold' => true, 'size' => 10, 'color' => '6B7280', 'name' => 'Arial']);
        $metaTable->addCell(7500)->addText($value, ['size' => 10, 'name' => 'Arial']);
    }

    $section->addTextBreak(2);

    $camposLabel = [
        'historico'          => 'Histórico do estudante',
        'barreiras'          => 'Barreiras de aprendizagem',
        'potencialidades'    => 'Potencialidades',
        'objetivos'          => 'Objetivos pedagógicos',
        'adaptacoes'         => 'Adaptações curriculares',
        'avaliacao'          => 'Adaptações de avaliação',
        'progresso'          => 'Registro de progresso',
        'cronograma'         => 'Cronograma de atendimento',
        'recursos'           => 'Recursos necessários',
        'acessibilidade'     => 'Acessibilidade',
        'parcerias'          => 'Parcerias com rede de saúde',
        'observacoes_livres' => 'Observações livres',
    ];

    foreach ($documento->content as $campo => $valor) {
        if (! $valor) continue;
        $label = $camposLabel[$campo] ?? ucfirst(str_replace('_', ' ', $campo));
        $section->addTitle($label, 2);
        $section->addText(htmlspecialchars($valor), ['size' => 11, 'name' => 'Arial'], ['lineHeight' => 1.5]);
        $section->addTextBreak(1);
    }

    $section->addTextBreak(2);
    $section->addText('Assinaturas', ['bold' => true, 'size' => 11, 'color' => '374151', 'name' => 'Arial']);
    $section->addTextBreak(1);

    $sigTable = $section->addTable(['borderSize' => 0]);
    $sigTable->addRow(800);
    foreach (['Professor / Elaborador', 'Coordenação / Secretaria', 'Responsável pelo estudante'] as $sig) {
        $sigTable->addCell(3333, ['borderBottomSize' => 6, 'borderBottomColor' => '374151'])
            ->addText($sig, ['size' => 9, 'color' => '6B7280', 'name' => 'Arial'], ['alignment' => 'center', 'spaceAfter' => 200]);
    }

    $filename = strtoupper(str_replace('_', '-', $documento->type))
        . '_' . str($documento->student->name)->slug()
        . '_' . $documento->year . '.docx';

    $tempFile = tempnam(sys_get_temp_dir(), 'word_');
    $writer = IOFactory::createWriter($word, 'Word2007');
    $writer->save($tempFile);

    return response()->download($tempFile, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ])->deleteFileAfterSend(true);
}
}