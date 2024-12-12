<?php

/*
* Doc
* https://demos.pixinvent.com/vuexy-vuejs-admin-template/documentation/guide/laravel-integration/laravel-sanctum-authentication.html
*
*
*/


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Etudiant;
use Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] nom
     * @param  [string] prenom
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request)
    {   

        //return response()->json($request);
        
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'c_password' => 'required|same:password',
            'is_enseignant' => 'required|in:true,false',
            'CNE' => $request->is_enseignant === 'false' ? 'required|string|unique:etudiants,id' : 'nullable|string',
        ]);
    
        // Cast is_enseignant to boolean (ensures it is stored as true or false)
        $isEnseignant = filter_var($request->is_enseignant, FILTER_VALIDATE_BOOLEAN);
        
        
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_enseignant' => $isEnseignant,
        ]);

        if ($user->save()) {
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->plainTextToken;

            //Creating enseignant or student 

            if (!$user->is_enseignant){
                if(!$request->CNE){
                    return response()->json(500,['error' => 'Provide proper details: CNE']);
                }

                $etudiant = Etudiant::create([
                    'id' => $request->CNE,
                    'user_id' => $user->id
                ]);

                if(!$etudiant->save()){
                    $user->delete();
                }

                return response()->json([
                    'message' => 'Successfully created student!',
                    'accessToken' => $token,
                ], 201);

            }else{
                return response()->json([
                    'message' => 'Successfully created Enseignat!',
                    'accessToken' => $token,
                ], 201);
            }

            
        } else {
            return response()->json(500,['error' => 'Provide proper details']);
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'string|in:true,false'
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }



    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = $request->user();


        // If the user is an enseignant, load the related Etudiant
        if ($user->is_enseignant!=1) {
            
            $etudiant = $user->etudiant()->first();
            
            if ($etudiant) {
                $user->CNE = $etudiant->id;
            }
        }

        $user->is_enseignant = (bool) $user->is_enseignant;

        return response()->json($user);
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);

    }
}
