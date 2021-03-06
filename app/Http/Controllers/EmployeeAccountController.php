<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\employeeInfo;
use Illuminate\Http\Request;
use App\Models\employeeAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountController extends Controller
{
    public function authenticate(Request $request){
        $request->validate([
            'email' => 'required|email|exists:employee_accounts,email',
            'password' => 'required|min:5|max:30',
        ],[
            'email.exists' => 'This email is not exists on users database.'
        ]);

        $credentials = $request->only('email','password');
        if(Auth::guard('employee')->attempt($credentials)){
            
            return redirect()->route('admin.home');
        }else{
            return redirect()->route('admin.login')->with('fail','Incorrect email or password.');
        }
    }
     public function logout()
    {
        # code...
        Auth::guard('employee')->logout();
        return redirect()->route('admin.login');
    }

// create

public function create(Request $request){
    //validate input
    $request->validate([
        'email' => 'required|email|unique:employee_infos,email',
        'fullname'=> 'required',
        'role'=>'required',
        'emp_branch'=> 'required',
        'password' => 'required|min:5|max:30',
        'ConfirmPassword' => 'required|min:5|max:30|same:password',
    ]);

    $employee_info = new employeeInfo();
    $employee_info->email =  $request->email;
    $employee_info->fullname = $request->fullname;
    $employee_info->emp_branch = $request->emp_branch;
    $employee_info->phone_number = "updating";
    if($request->role='dealer'){
    $employee_info->salary = 10000000;}
    else{$employee_info->salary = 15000000;}
    $save_info=$employee_info->save();

    $user = new employeeAccount();
    $user->email = $request->email;
    $user->role=$request->role;
    $user->password = Hash::make($request->password);
    $save = $user->save();

    

    


    if($save && $save_info ){
        return redirect('admin/general/employee')->with("<script>alert('T???o T??i Kho???n Th??nh C??ng')</script>"); 
    }else{
        return redirect()->back()->with("<script>alert('C?? G?? ???? Sai Sai! T???o T??i Kho???n Kh??ng Th??nh C??ng')</script>");
    }
    
    
}




public function empchangepass(Request $request, $id){
    //validate input
    $request->validate([
        'password' => 'required|min:5|max:30',
        'ConfirmPassword' => 'required|min:5|max:30|same:password',
    ]);

 
    $emp = employeeInfo::find($id);
    if($emp->employee_Account==null){
     
        return view('admin.general.accountcreate')->with(['email'=>$emp->email]);

                        }
    else{$account=$emp->employee_Account;
        $account->password=Hash::make($request->password);
        $account->save();
    }
        return redirect('admin/general/employee/')->with(['message'=>'C???p Nh???t M???t Kh???u Th??nh C??ng']); 
    }

    
    public function accountcreate(Request $request){
      
        

            $account=new employeeAccount();
            $account->email=$request->email;
            $account->role=$request->role;
            $account->password=Hash::make($request->password);
            
        
            $account->save();
            $emp_info=$account->employeeinfo;
    //    dd($account);
            if($account->role=='warehouse'){$emp_info->emp_branch=1;}
            // dd($emp_info);
            $emp_info->save();
        
            return redirect('/admin/general/employee')->with(['message'=>'T???o T??i Kho???n Th??nh C??ng']);
        }
    















        



}
