<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Session;

class CompanyController extends Controller
{
  public function company()
  {
     if (auth()->user()->type == 'editor' && auth()->user()->company == 1)
    {
      $company = Company::find(1);

      return view('admin.datasetup.company')->with('company', $company);
    }
    else
    {
      return back()->with('status', 'You do not have access');
    }
  }

  public function addcompany()
  {
    if (auth()->user()->type == 'editor' && auth()->user()->company == 1)
    {
      return view('admin.datasetup.addcompany');
    } else if (auth()->user()->type == 'checker') {
      return back()->with('status', 'You do not have access');
    }
  }


  public function savecompany(Request $request)
  {

    $this->validate($request, [
      'company_code' => 'required',
      'company_name' => 'required',
      'company_address'=>'required',
      'company_phno'=>'required',
      'image'=>'required'
    ]);

    $company = new Company();

    if ($request->hasFile('image')) {
      $fileName = $request->file('image')->getClientOriginalName();

      $ext = $request->file('image')->getClientOriginalExtension();

      $fileName = pathinfo($fileName, PATHINFO_FILENAME);
      $destinationPath = public_path().'/images/company' ;

      $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
      
      $path = $request->file('image')->move($destinationPath, $fileNameToStore);
    }

    
    

    $company->id=1;
    $company->image = $fileNameToStore;
    $company->company_code = $request->input('company_code');
    $company->company_name = $request->input('company_name');
    $company->company_address = $request->input('company_address');
    $company->company_phno = $request->input('company_phno');
    $company->save();

    return redirect('/company')->with('status', 'Company has been added!');
  }

  public function editcompany($id)
  {

   if (auth()->user()->type == 'editor' && auth()->user()->company == 1)
    {
      $company = Company::find($id);

      return view('admin.datasetup.editcompany')->with('company', $company);
    } else if (auth()->user()->type == 'checker') {
      return back()->with('status', 'You do not have access');
    }
  }


  public function updatecompany(Request $request)
  {
        $this->validate($request, [
          'company_code' => 'required',
          'company_name' => 'required',
          'company_address'=>'required',
          'company_phno'=>'required',
          'image'=>'required'
        ]);


    $company = Company::find($request->input('id'));

    if ($request->hasFile('image')) {

      $fileName = $request->file('image')->getClientOriginalName();

        $ext = $request->file('image')->getClientOriginalExtension();

        $fileName = pathinfo($fileName, PATHINFO_FILENAME);
        $destinationPath = public_path().'/images/company' ;

        $fileNameToStore = $fileName.'_'.time().'.'.$ext;   
        
        $path = $request->file('image')->move($destinationPath, $fileNameToStore);
    }

  
    $company->image = $fileNameToStore;
    $company->company_code = $request->input('company_code');
    $company->company_name = $request->input('company_name');
    $company->company_address = $request->input('company_address');
    $company->company_phno = $request->input('company_phno');

    $company->update();

    return redirect('/company')->with('status', 'Company has been updated!');
  }

  public function deletecompany($id)
  {

    $company = Company::find($id);

    $company->delete();

    return back()->with('status', 'Company has been deleted!');
  }
}
