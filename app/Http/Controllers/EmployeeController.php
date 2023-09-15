<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    //
    // public function index(){
    public function index(Request $request){
        // $data = Employee::all();
        if($request->has('search')){
            $data= Employee::where('nama', 'LIKE','%' .$request->search .'%')->paginate(3);
        }
        else{
            $data = Employee::paginate(3);
        }
        // dd($data);
        return view('datapegawai', compact('data'));
    }
    public function tambahpegawai(){
        // sesuai dengan nama file di view tambahpegawai.blade.php
        return view('tambahpegawai');
    }
    public function insertdata(Request $request){
        // dd($request->all());
        $data = Employee::create($request->all());
        // dd($data);
        if($request->hasFile('foto')){
            $request->file('foto')->move('fotopegawai/', $request->file('foto')->getClientOriginalName());
            $data->foto = $request->file('foto')->getClientOriginalName();
            $data->save();
        }
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function tampildata($id){
        $data = Employee::find($id);
        // dd($data);
        return view('tampildata', compact('data'));
    }

    public function updatedata(Request $request, $id){
        $data = Employee::find($id);
        $data->update($request->all());
        // dd($data);
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Diubah');
    }

    public function deletedata($id){
        $data = Employee::find($id);
        $data->delete();
        // dd($data);
        return redirect()->route('pegawai')->with('success', 'Data Berhasil Dihapus');
    }

    public function exportpdf(){
        $data = Employee::all();
        view()->share('data', $data);
        $pdf = PDF::loadview('datapegawai-pdf');
        return $pdf->download('data.pdf');
    }

    public function exportexcel(){
        // $data = Employee::all();
        // view()->share('data', $data);
        // $pdf = PDF::loadview('datapegawai-pdf');
        // return $pdf->download('data.pdf');
        return Excel::download(new EmployeeExport, 'datapegawai.xlsx');
    }

    public function importexcel(Request $request){
            $data = $request->file('file');

            $nama_file = $data->getClientOriginalName();
            $data->move('EmployeeData', $nama_file);

            Excel::import(new EmployeeImport, \public_path('/EmployeeData/'.$nama_file));

            return redirect()->back();
    }
}
