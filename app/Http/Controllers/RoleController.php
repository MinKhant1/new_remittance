<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Session;
use DB;

class RoleController extends Controller
{
    public function role()
      {
          $roles = DB::table('users')->select()->groupby('type')->get();

          

          if(auth()->user()->type == 'editor') 
          {
            return view('admin.usersetup.role')->with('roles', $roles);
          }
          else if(auth()->user()->type == 'checker') 
          {
            return back()->with('status', 'You do not have access');
          }

      }
  
      public function addrole()
      {
          return view('admin.usersetup.addrole');
      }
  
      public function saverole(Request $request)
      {
        $this->validate($request, ['rolename'=>'required|unique:roles']);

        $role = new Role();
        $role->rolename = $request->input('rolename');
        $role->save();

        return redirect('/role')->with('status', 'Role has been added!');
      }
  
      public function editrole($id)
      {
        
          $roles = Role::find($id);
 
          return view('admin.usersetup.editrole')->with('roles', $roles);
      }
  
      public function updaterole(Request $request)
      {
        $this->validate($request, ['rolename'=>'required|unique:roles']);

        $role = Role::find($request->input('id'));

        $role->rolename = $request->input('rolename');
        $role->update();

        return redirect('/role')->with('status', 'Role has been updated!');       
      }
  
      public function deleterole($id)
      {
        $role = Role::find($id);
         
        $role->delete();

        return back()->with('status', 'Role has been deleted!'); 
      }
}
