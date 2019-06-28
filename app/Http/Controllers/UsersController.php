<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        $users = User::all();
        return response()->json([
            'users' => $users,
            'admin' => $admin
            ]);
        //
    }

    public function edit($id)
    {

        $admin = auth()->user();
        if($admin->is_admin == 1){
            $user = User::find($id);
            return response()->json([
                'user' => $user,
                'admin' => $admin
            ]);
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Permission denied.'
            ]);
        }

        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $admin = auth()->user();
        if($admin->is_admin == 1){
            $request->validate([
                'name' => 'required', 'string', 'max:255',
                'email' => 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $id,
                'password' => 'string', 'min:6'
            ]);
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if (!empty($request->input('password'))) {
                $user->password = bcrypt($request->input('password'));
            }
            $user->save();
            return response()->json('Successfully updated.');
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Permission denied.'
            ]);
        }

        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = auth()->user();
        if($admin->is_admin == 1){
            $user = User::find($id);
            $user->delete();
            return response()->json('Successfully deleted.');
        }else{
            return response()->json([
                'status_code' => 400,
                'message' => 'Permission denied.'
            ]);
        }

    }
}
