<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use App\Constants\DocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Documents::query();

        // Aplicar filtros de búsqueda
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('ruc', 'like', '%' . $request->buscar . '%')
                    ->orWhere('razon_social', 'like', '%' . $request->buscar . '%')
                    ->orWhere('serie', 'like', '%' . $request->buscar . '%')
                    ->orWhere('correlativo', 'like', '%' . $request->buscar . '%');
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->tipo_documento);
        }

        // Ordenar y paginar resultados
        $documents = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
            
        $documentTypes = DocumentTypes::all();
        
        return view('employee.pages.documents.index', compact('documents', 'documentTypes'));
    }

    public function create()
    {
        $documentTypes = DocumentTypes::all();
        return view('employee.pages.documents.form', compact('documentTypes'));
    }

    public function edit($id)
    {
        $documents = Documents::findOrFail($id);
        $documentTypes = DocumentTypes::all();
        
        // Si necesitas contar productos relacionados
        // $documentsCount = $documents->productos()->count();
        
        return view('employee.pages.documents.form', compact('documents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validIds = implode(',', DocumentTypes::getValidIds());
        
        $request->validate([
            'ruc' => 'nullable|string|size:11|regex:/^[0-9]+$/',
            'razon_social' => 'nullable|string|max:255',
            'tipo_documento' => 'required|in:' . $validIds,
            'employee_id' => 'required|exists:employees,id',
            'serie' => 'nullable|string|max:10',
            'correlativo' => 'nullable|string|max:20',
            'estado' => 'required|in:0,1',
            'archivo' => 'required|file|mimes:pdf,xml,zip|max:10240', // 10MB máximo
        ], [
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.regex' => 'El RUC solo debe contener números.',
            'razon_social.max' => 'La razón social no puede exceder 255 caracteres.',
            'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
            'serie.max' => 'La serie no puede exceder 10 caracteres.',
            'correlativo.max' => 'El correlativo no puede exceder 20 caracteres.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'archivo.required' => 'Debe adjuntar un archivo.',
            'archivo.file' => 'El campo debe ser un archivo válido.',
            'archivo.mimes' => 'Solo se permiten archivos PDF, XML o ZIP.',
            'archivo.max' => 'El archivo no puede superar los 10MB.',
        ]);

        $rutaArchivo = null;

        if ($request->hasFile('archivo')) {
            // Crear nombre personalizado para el archivo
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();

            // Obtener la carpeta según el tipo de documento usando constantes
            $carpetaTipo = DocumentTypes::getStorageFolder($request->tipo_documento);

            $nombreArchivo = ($request->ruc ?? 'sin_ruc') . '_' .
                ($request->serie ?? 'sin_serie') . '-' .
                ($request->correlativo ?? 'sin_correlativo') . '_' .
                time() . '.' . $extension;

            // Organizar por tipo de documento y año/mes
            $carpeta = 'documentos/' . $carpetaTipo . '/' . date('Y') . '/' . date('m');

            // Guardar el archivo
            $rutaArchivo = $archivo->storeAs($carpeta, $nombreArchivo, 'public');
        }

        try {
            $documento = Documents::create([
                'ruc' => $request->ruc,
                'razon_social' => $request->razon_social,
                'tipo_documento' => $request->tipo_documento,
                'employee_id' => $request->employee_id,
                'serie' => $request->serie ? strtoupper($request->serie) : null,
                'correlativo' => $request->correlativo,
                'estado' => $request->estado,
                'archivo' => $rutaArchivo,
            ]);

            return redirect()
                ->route('employee.documents.index')
                ->with('success', 'Documento registrado correctamente.');
        } catch (\Exception $e) {
            // Si hay error, eliminar el archivo subido
            if ($rutaArchivo && Storage::disk('public')->exists($rutaArchivo)) {
                Storage::disk('public')->delete($rutaArchivo);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al registrar el documento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $documento = Documents::findOrFail($id);
        $validIds = implode(',', DocumentTypes::getValidIds());

        $request->validate([
            'ruc' => 'nullable|string|size:11|regex:/^[0-9]+$/',
            'razon_social' => 'nullable|string|max:255',
            'tipo_documento' => 'required|in:' . $validIds,
            'serie' => 'nullable|string|max:10',
            'correlativo' => 'nullable|string|max:20',
            'estado' => 'required|in:0,1',
            'archivo' => 'nullable|file|mimes:pdf,xml,zip|max:10240', // Opcional en actualización
        ], [
            'ruc.size' => 'El RUC debe tener exactamente 11 dígitos.',
            'ruc.regex' => 'El RUC solo debe contener números.',
            'razon_social.max' => 'La razón social no puede exceder 255 caracteres.',
            'tipo_documento.required' => 'Debe seleccionar un tipo de documento.',
            'tipo_documento.in' => 'El tipo de documento seleccionado no es válido.',
            'serie.max' => 'La serie no puede exceder 10 caracteres.',
            'correlativo.max' => 'El correlativo no puede exceder 20 caracteres.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'archivo.file' => 'El campo debe ser un archivo válido.',
            'archivo.mimes' => 'Solo se permiten archivos PDF, XML o ZIP.',
            'archivo.max' => 'El archivo no puede superar los 10MB.',
        ]);

        $rutaArchivo = $documento->archivo;

        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($documento->archivo && Storage::disk('public')->exists($documento->archivo)) {
                Storage::disk('public')->delete($documento->archivo);
            }

            // Subir nuevo archivo
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();

            // Obtener la carpeta según el tipo de documento usando constantes
            $carpetaTipo = DocumentTypes::getStorageFolder($request->tipo_documento);

            $nombreArchivo = ($request->ruc ?? 'sin_ruc') . '_' .
                ($request->serie ?? 'sin_serie') . '-' .
                ($request->correlativo ?? 'sin_correlativo') . '_' .
                time() . '.' . $extension;

            $carpeta = 'documentos/' . $carpetaTipo . '/' . date('Y') . '/' . date('m');
            $rutaArchivo = $archivo->storeAs($carpeta, $nombreArchivo, 'public');
        }

        try {
            $documento->update([
                'ruc' => $request->ruc,
                'razon_social' => $request->razon_social,
                'tipo_documento' => $request->tipo_documento,
                'serie' => $request->serie ? strtoupper($request->serie) : null,
                'correlativo' => $request->correlativo,
                'estado' => $request->estado,
                'archivo' => $rutaArchivo,
            ]);

            return redirect()
                ->route('employee.documents.index')
                ->with('success', 'Documento actualizado correctamente.');
        } catch (\Exception $e) {
            // Si hay error con el nuevo archivo, restaurar el anterior
            if ($request->hasFile('archivo') && $rutaArchivo && Storage::disk('public')->exists($rutaArchivo)) {
                Storage::disk('public')->delete($rutaArchivo);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al actualizar el documento: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        $documento = Documents::findOrFail($id);

        if (!$documento->archivo || !Storage::disk('public')->exists($documento->archivo)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        // Generar nombre descriptivo para la descarga usando constantes
        $tipoNombre = DocumentTypes::getName($documento->tipo_documento);
        $nombreDescarga = $tipoNombre . '_' .
            ($documento->serie ?? 'SIN_SERIE') . '-' .
            ($documento->correlativo ?? 'SIN_CORRELATIVO') . '.' .
            pathinfo($documento->archivo, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($documento->archivo, $nombreDescarga);
    }

    public function preview($id)
    {
        $documento = Documents::findOrFail($id);

        if (!$documento->archivo || !Storage::disk('public')->exists($documento->archivo)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        $rutaCompleta = storage_path('app/public/' . $documento->archivo);
        $mimeType = Storage::disk('public')->mimeType($documento->archivo);

        // Solo permitir vista previa para PDFs
        if ($mimeType === 'application/pdf') {
            return response()->file($rutaCompleta);
        }

        // Para XML y ZIP, forzar descarga
        return $this->download($id);
    }

    public function destroy($id)
    {
        $documento = Documents::findOrFail($id);

        try {
            // Eliminar archivo si existe
            if ($documento->archivo && Storage::disk('public')->exists($documento->archivo)) {
                Storage::disk('public')->delete($documento->archivo);
            }

            $documento->delete();

            return redirect()
                ->route('employee.documents.index')
                ->with('success', 'Documento eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el documento: ' . $e->getMessage());
        }
    }
}