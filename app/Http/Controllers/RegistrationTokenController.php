<?php

namespace App\Http\Controllers;

use App\Models\RegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RegistrationTokenController extends Controller
{
    public function index(Request $request)
    {
        $query = RegistrationToken::with(['creator', 'usedBy'])->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $tokens = $query->paginate(15)->withQueryString();

        return view('registration-tokens.index', compact('tokens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:etudiant,entreprise,enseignant',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $plainTokens = [];

        for ($i = 0; $i < $validated['quantity']; $i++) {
            $plainToken = strtoupper($validated['role']) . '-' . Str::upper(Str::random(32));
            $plainTokens[] = $plainToken;

            RegistrationToken::create([
                'token_hash' => RegistrationToken::hashToken($plainToken),
                'token_value' => $plainToken,
                'role' => $validated['role'],
                'created_by' => auth()->id(),
            ]);
        }

        return back()
            ->with('success', count($plainTokens) . ' token(s) genere(s). Ils seront visibles dans l\'export admin.')
            ->with('generated_tokens', $plainTokens);
    }

    public function export(Request $request)
    {
        $request->validate([
            'role' => 'nullable|in:etudiant,entreprise,enseignant',
        ]);

        $query = RegistrationToken::with(['creator', 'usedBy'])->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tokens');

        $headers = ['ID', 'Token', 'Profil', 'Statut', 'Cree par', 'Utilise par', 'Cree le', 'Utilise le'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1F4D']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        $row = 2;
        $query->chunk(200, function ($tokens) use ($sheet, &$row) {
            foreach ($tokens as $token) {
                $sheet->fromArray([
                    $token->id,
                    $token->plain_token ?: 'Non disponible',
                    $token->role,
                    $token->used_at ? 'utilise' : 'disponible',
                    optional($token->creator)->email,
                    optional($token->usedBy)->email,
                    optional($token->created_at)->format('d/m/Y H:i'),
                    optional($token->used_at)->format('d/m/Y H:i'),
                ], null, 'A' . $row);
                $row++;
            }
        });

        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'tokens_inscription_' . ($request->role ?: 'tous') . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'tokens_');
        (new Xlsx($spreadsheet))->save($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
