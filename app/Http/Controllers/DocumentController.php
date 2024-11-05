<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function document_list(){
        $user = Auth::user()->id;
        $documents =  Document::with('user')->where('user_id', $user)->get();
        return view('documents.documents_list', compact('documents'));
    }


    public function add_document(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:documents,name',
            'purpose' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,csv,png,jpg,jpeg,gif|max:2048',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'purpose' => $request->purpose,
            'file_path' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Document created successfully.');
    }


    public function update_document(Request $request, $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return redirect()->back()->withErrors(['document not found.']);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:documents,name,' . $document->id,
            'purpose' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,csv,png,jpg,jpeg,gif|max:2048',
        ]);


        $document->name = $request->name;
        $document->purpose = $request->purpose;

        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            $document->file_path = $filePath;
        }

        $document->save();

        return redirect()->back()->with('success', 'Document updated successfully.');
    }


    public function delete_document($id)
    {
        $document = Document::find($id);
        $document->delete();
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
