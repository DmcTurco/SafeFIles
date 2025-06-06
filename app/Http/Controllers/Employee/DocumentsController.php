<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Documents::query();

        // Aplicar filtros de búsqueda
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('ruc', 'like', '%' . $request->buscar . '%')
                    ->orWhere('tipo_documento', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Ordenar y paginar resultados
        $documents = $query->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();
        return view('employee.pages.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('employee.pages.documents.form');
    }

    public function edit($id)
    {
        $documents = Documents::findOrFail($id);
        $documentsCount = $documents->productos()->count();

        return view('employee.pages.documents.form', compact('documents', 'documentsCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'nullable|string|max:20',
            'razon_social' => 'nullable|string|max:255',
            'tipo_documento' => 'required|exists:tipo_documentos,id',
            'employee_id' => 'required|exists:employees,id',
            'serie' => 'nullable|string|max:10',
            'correlativo' => 'nullable|string|max:20',
            'estado' => 'required|in:0,1',
            'archivo' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $rutaArchivo = null;

        if ($request->hasFile('archivo')) {
            $rutaArchivo = $request->file('archivo')->store('documentos', 'public');
        }

        Documents::create([
            'ruc' => $request->ruc,
            'razon_social' => $request->razon_social,
            'tipo_documento' => $request->tipo_documento,
            'employee_id' => $request->employee_id,
            'serie' => $request->serie,
            'correlativo' => $request->correlativo,
            'estado' => $request->estado,
            'archivo' => $rutaArchivo,
        ]);

        return redirect()->route('employee.pages.documents.index')->with('success', 'Documento registrado correctamente.');
    }

    //     public function destroy(Documents $document)
    //     {
    //         if (Storage::disk('public')->exists($document->archivo)) {
    //             Storage::disk('public')->delete($document->archivo);
    //         }

    //         $document->delete();

    //         return back()->with('success', 'Documento eliminado.');
    //     }
}
