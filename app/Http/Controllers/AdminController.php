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
        $total_user=User::count('*');
        return view('admin.users', [
            'users'=> $users,
            'total_user'=>$total_user,
        ]);
    }

    /**
     *recuperer la listes des transactions effectuées sur le site
     */
    public function getTransactions(){
        $transactions = Transaction::orderBy('id','desc')->paginate(20);
        $total=Transaction::sum('montant');

        return view('admin.transactions', [
            'transactions'=> $transactions,
            'total'=>$total,
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
