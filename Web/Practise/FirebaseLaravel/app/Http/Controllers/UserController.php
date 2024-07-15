<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Kreait\Firebase\Factory;
// use Illuminate\Support\Facades\Storage;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;


class UserController extends Controller
{
    public function addUser(Request $req)
    {
        if ($req->has('submit')) {
            try {
                $factory = (new Factory)
                    ->withServiceAccount(storage_path('app/firebase-auth.json'))
                    ->withDatabaseUri('https://laravelmysqlfirebase-default-rtdb.firebaseio.com');

                $auth = $factory->createAuth();
                $storage = $factory->createStorage(); // Get the Storage client instance
                $storageClient = $storage->getStorageClient();
                $defaultBucket = $storage->getBucket();

                

                $name = $req->name;
                $email = $req->email;
                $password = $req->password;
                $image = $req->file('ProfileImage');

                // $upload = $defaultBucket->upload($image->getPathname(), [
                //     'name' => $image->getClientOriginalName()
                // ]);
                $imageName = time(). $image->getClientOriginalName();
                $uploadedFile = $defaultBucket->upload(file_get_contents($image->getRealPath()), [
                    'name' => 'User/' . $imageName,
                    'metadata' => [
                        'contentType' => $image->getClientMimeType(),
                    ],
                ]);

                $uploadedFile->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

                // Get the public URL
                $imageUrl = "https://storage.googleapis.com/" . $defaultBucket->name() . "/" . $uploadedFile->name();



                $data = [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'image' => $imageName,
                    'image_url' => $imageUrl,
                    // 'image' => $image,
                    // 'upload' => $uploadedFile->info(),
                    // 'imageUrl' => $imageUrl
                ];

                User::create($data);
            } catch (FirebaseException $e) {
                return 'Error creating Firebase client: '. $e->getMessage();
            }
        }

        return view('User');
    }


    public function viewUser()
    {
        $users = User::all();
        return view('ViewUser',compact('users'));
    }



    public function deleteUser($id)
    {
        $user = User::find($id);
        $factory = (new Factory)
                    ->withServiceAccount(storage_path('app/firebase-auth.json'))
                    ->withDatabaseUri('https://laravelmysqlfirebase-default-rtdb.firebaseio.com');

        
        $storage = $factory->createStorage();
        $defaultBucket = $storage->getBucket();
        $object = $defaultBucket->object("User/$user->image");
        if($object->exists())
        {
            $object->delete();
            $delete = true;
        }
        else
        {
            $delete = false;
        }

            return response()->json([
                'user' => $user,
                'message' => "User deleted $delete",
                'delete' => $delete,
            ]);
    }
}







