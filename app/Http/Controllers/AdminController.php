<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class AdminController extends Controller
{
    /**
     *recuperer la listes des utilisateurs du site
     */
    public function getUsers(){
        $users = User::orderBy('created_at','asc')->paginate(20);
        return view('admin.users', [
            'users'=> $users,
        ]);
    }
    
    /**
     *recuperer la listes des transactions effectuées sur le site
     */
    public function getTransactions(){
        $transactions = Transaction::orderBy('id','desc')->paginate(20);

        //dd($transactions[0]);
        return view('admin.transactions', [
            'transactions'=> $transactions,
        ]);
    }

    /**
     * Désactiver ou réactiver le compte d'un utilisateur
     */
    public function desactivateOrActivate(Request $request){
        $id= $request->user_id;
        $user=User::find($id);
        $user->isBanned = !$user->isBanned;
        $user->save();
        return redirect()->route('admin.users');
    }

}
