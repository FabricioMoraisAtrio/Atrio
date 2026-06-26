<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Invoice;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        $q = Invoice::with('school');

        if ($request->filled('school_id')) {
            $q->where('school_id', $request->school_id);
        }
        if ($request->filled('reference')) {
            $q->where('reference', $request->reference);
        }
        if ($request->filled('status')) {
            if ($request->status === 'vencido') {
                $q->where('status', 'aberto')->whereDate('due_date', '<', $today);
            } else {
                $q->where('status', $request->status);
            }
        }

        $invoices = $q->orderByDesc('due_date')->paginate(20)->withQueryString();
        $schools  = School::orderBy('name')->get(['id', 'name']);

        $resumo = [
            'recebido' => (float) Invoice::where('status', 'pago')
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            'aberto'   => (float) Invoice::where('status', 'aberto')->whereDate('due_date', '>=', $today)->sum('amount'),
            'vencido'  => (float) Invoice::where('status', 'aberto')->whereDate('due_date', '<', $today)->sum('amount'),
        ];

        return view('admin.financeiro.index', compact('invoices', 'schools', 'resumo'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'reference' => 'required|date_format:Y-m',
            'amount'    => 'required|numeric|min:0',
            'due_date'  => 'required|date',
            'notes'     => 'nullable|string',
        ]);

        $inv = Invoice::create($data + ['status' => 'aberto']);
        AdminLog::record('fatura_criada', "Fatura {$inv->reference} de R$ " . number_format((float) $inv->amount, 2, ',', '.') . " criada", (int) $inv->school_id);

        return back()->with('success', 'Fatura criada.');
    }

    /** Gera faturas do mês para escolas ativas com valor mensal, sem duplicar. */
    public function generate(Request $request)
    {
        $data = $request->validate(['reference' => 'required|date_format:Y-m']);
        $ref  = $data['reference'];
        [$y, $m] = explode('-', $ref);
        $due = Carbon::create((int) $y, (int) $m, 10)->toDateString();

        $created = 0;
        School::where('is_active', true)->where('monthly_fee', '>', 0)->get()
            ->each(function (School $s) use ($ref, $due, &$created) {
                $exists = Invoice::where('school_id', $s->id)->where('reference', $ref)->exists();
                if (! $exists) {
                    Invoice::create([
                        'school_id' => $s->id,
                        'reference' => $ref,
                        'amount'    => $s->monthly_fee,
                        'due_date'  => $due,
                        'status'    => 'aberto',
                    ]);
                    $created++;
                }
            });

        AdminLog::record('faturas_geradas', "{$created} fatura(s) geradas para {$ref}");

        return back()->with('success', "{$created} fatura(s) gerada(s) para {$ref}.");
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'status'  => 'pago',
            'paid_at' => now()->toDateString(),
            'method'  => $request->input('method'),
        ]);
        AdminLog::record('fatura_paga', "Fatura {$invoice->reference} marcada como paga", (int) $invoice->school_id);

        return back()->with('success', 'Fatura marcada como paga.');
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelado']);
        AdminLog::record('fatura_cancelada', "Fatura {$invoice->reference} cancelada", (int) $invoice->school_id);

        return back()->with('success', 'Fatura cancelada.');
    }

    public function destroy(Invoice $invoice)
    {
        $ref = $invoice->reference;
        $sid = (int) $invoice->school_id;
        $invoice->delete();
        AdminLog::record('fatura_excluida', "Fatura {$ref} excluída", $sid);

        return back()->with('success', 'Fatura excluída.');
    }
}
