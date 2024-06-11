<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\File;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    /**
     * Afficher tous les modules.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules);
    }

    /**
     * Créer un nouveau module.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */


     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'name' => 'required|string',
             'syllabus' => 'file|mimes:pdf,doc,docx',
             'hourly_rate' => 'required|numeric|min:0',
             'promotion' => 'required|string|min:0|max:100',
             'comment' => 'nullable|string',
         ]);
     
         if ($validator->fails()) {
             return response()->json($validator->errors(), 400);
         }
     
         $moduleData = $request->only(['name', 'hourly_rate', 'promotion', 'comment']);
     
         if ($request->hasFile('syllabus')) {
             $syllabusPath = $request->file('syllabus')->store('syllabus');
             $moduleData['syllabus'] = $syllabusPath;
         }
     
         $module = Module::create($moduleData);
     
         return response()->json(['message' => 'Module created successfully', 'module' => $module], 201);
     }


/**
 * Mettre à jour un module existant.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \App\Models\Module  $module
 * @return \Illuminate\Http\JsonResponse
 */
public function update(Request $request, Module $module)
{
    // Valider les données de la requête
    $validator = Validator::make($request->all(), [
        'name' => 'nullable|string',
        'syllabus' => 'nullable|file|mimes:pdf,doc,docx', // Permet de mettre à jour le syllabus
        'hourly_rate' => 'numeric|min:0',
        'promotion' => 'nullable|string',
        'comment' => 'nullable|string',
    ]);

    // Retourner les erreurs de validation s'il y en a
    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    // Mettre à jour le module avec les données fournies
    $module->update($request->except('syllabus')); // Exclure le syllabus de la mise à jour

    // Si un nouveau syllabus est fourni, le mettre à jour
    if ($request->hasFile('syllabus')) {
        $syllabusPath = $request->file('syllabus')->store('syllabus');
        $module->syllabus = $syllabusPath;
        $module->save();
    }

    // Retourner le module mis à jour
    return response()->json(['message' => 'Module updated successfully', 'module' => $module]);
}

    
    
    /**
     * Supprimer un module existant.
     *
     * @param  \App\Models\Module  $module
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Module $module)
    {
        DB::transaction(function () use ($module) {
            foreach ($module->files as $file) {
                if ($file->link && Storage::disk('public')->exists($file->link)) {
                    Storage::disk('public')->delete($file->link);
                }
                $file->delete();
            }
    
            if ($module->syllabus && Storage::disk('public')->exists($module->syllabus)) {
                Storage::disk('public')->delete($module->syllabus);
            }
    
            $module->delete();
        });
    
        return response()->json([], 204);
    }
    
    
    
}
