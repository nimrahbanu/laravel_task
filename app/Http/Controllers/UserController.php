<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roleInfo')->get();

        return response()->json(['data'=> $users]);
    }



    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:100|unique:users,email',
            'phone' => 'required|numeric|digits:10',
            'description' => [
                'required',
                'string',
                'max:500',
                'regex:/^(?!.*(?:<script.*?>.*?<\/script>|http:\/\/|https:\/\/|\/\/)).*$/',
            ],
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'];
            $user->description = strip_tags(trim($validated['description']));
            $user->role_id = $validated['role_id'];

            if ($request->hasFile('profile_image')) {
                $imageName = $request->file('profile_image')->getClientOriginalName();
                $imagePath = $request->file('profile_image')->storeAs('profile_images', $imageName, 'public');
                $user->profile_image = $imageName;
            }

            $user->save();

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error creating role',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('users.index');

    }

}
