<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Blog;
use App\Http\Resources\UserResource;
use File;

class UserController extends Controller
{
    public function addUser(Request $req)
    {
        $validateData = $req->validate([
            'Name' => 'required',
            'Mobile' => 'required',
            'Email' =>'required|email',
            'Password' => 'required',
            'ConfirmPassword' => 'required|same:Password',
        ]);

        if(!(User::where('email',$req->Email)->exists()))
        {  
            if($req->file('Image'))
            {
                $image = time() . rand(000000000,999999999) . $req->Image->getClientOriginalName();
                $req->Image->storeAs('image/user',$image,'public');
            }
            else
            {
                $image = "Profile.jpg";
            }

            $data = [
                "name" => $req->Name,
                "email" => $req->Email,
                "password" => $req->Password,
                "mobile" => $req->Mobile,
                "user_image" => $image,
            ];

            $user = User::create($data);

            return response()->json([
                "message" => "User Added SuccessFully"
            ]);
        }
        else
        {
            return response()->json([
                "message" => "Email Already registered"
            ]);
        }

    }

    public function viewAllUsers()
    {
        $data = User::all();
        $users = UserResource::collection($data);
        return $users;
        // return response()->json($users);
    }

    public function login(Request $req)
    {
        $validateData = $req->validate([
            'Email' =>'required|email',
            'Password' => 'required'
        ]);

        if(User::where('email',$req->Email)->exists())
        {
            $user = User::where('email',$req->Email)->first();
            if($user->password == $req->Password)
            {
                $token = $user->createToken('authToken')->accessToken;
                return response()->json([
                    "message" => "Login Successfully",
                    "token" => $token
                ]);
            }
            else
            {
                return response()->json([
                "message" => "Wrong Password"
            ]);
            }
        }
        else
        {
            return response()->json([
                "message" => "User Not Found"
            ]);
        }
    }

    public function logout(Request $req)
    {
        Auth::guard()->user()->token()->revoke();
        Auth::guard()->user()->token()->delete();
        return response()->json([
            "message" => "User Logout Successfully"
        ]);
    }


    public function viewUser()
    {
        $user = Auth::guard()->user();
        return response()->json($user);
    }

    public function editUser(Request $req)
    {
            $user = Auth::guard()->user();
            $user->name =is_null($req->input('Name'))? $user->name : $req->input('Name');
            $user->email = is_null($req->input('Email'))? $user->email :$req->input('Email');
            $user->password = is_null($req->input('Password'))? $user->password :$req->input('Password');
            $user->mobile =is_null($req->input('Mobile'))? $user->mobile : $req->input('Mobile');
            $user->save();
            return response()->json([
                "message" => "User Updated Successfully"
            ]);
    }

    public function deleteUser(Request $req)
    {
        $user = Auth::guard()->user();
        if(Blog::where('user_id',$user->id)->exists())
        {
            $blogs = Blog::where('user_id',$user->id)->get();
            foreach ($blogs as $blog) {
                $blog->delete();
            }
        }
        $user->delete();
        return response()->json([
            "message" => "User Deleted Successfully"
        ]);
    }


    public function editUserImage(Request $req)
    {
        $user = Auth::guard()->user();

        $validateData = $req->validate([
            'Image' => 'required|image'
        ]);

        if($user->image != 'Profile.jpg')
        {
            if (File::exists(public_path("storage/image/user/$user->user_image"))) {
                File::delete(public_path("storage/image/user/$user->user_image"));
            }
        }

        $image = time() . rand(000000000,999999999) . $req->Image->getClientOriginalName();
        $req->Image->storeAs('image/user',$image,'public');
        $user->user_image = $image;
        $user->save();

        return response()->json([
            "message" => "Profile Image Changed Successfully"
        ]);
    }


    public function Het()
    {
        return response()->json([


    "result" => "success",
    "documentation" => "https://www.exchangerate-api.com/docs",
    "terms_of_use" => "https://www.exchangerate-api.com/terms",
    "time_last_update_unix" => 1716940801,
    "time_last_update_utc" => "Wed, 29 May 2024 00:00:01 +0000",
    "time_next_update_unix" => 1717027201,
    "time_next_update_utc" => "Thu, 30 May 2024 00:00:01 +0000",
    "base_code" => "USD",
    "conversion_rates"=> 
    [
        "USD" => 1,
        "AED" => 3.6725,
        "AFN" => 71.8026,
        "ALL" => 92.3920,
        "AMD" => 387.5278,
        "ANG" => 1.7900,
        "AOA" => 862.0864,
        "ARS" => 864.7500,
        "AUD" => 1.5026,
        "AWG" => 1.7900,
        "AZN" => 1.6996,
        "BAM" => 1.8001,
        "BBD" => 2.0000,
        "BDT" => 117.3674,
        "BGN" => 1.7998,
        "BHD" => 0.3760,
        "BIF" => 2859.8305,
        "BMD" => 1.0000,
        "BND" => 1.3483,
        "BOB" => 6.9168,
        "BRL" => 5.1601,
        "BSD" => 1.0000,
        "BTN" => 83.1969,
        "BWP" => 13.5785,
        "BYN" => 3.2670,
        "BZD" => 2.0000,
        "CAD" => 1.3633,
        "CDF" => 2756.9212,
        "CHF" => 0.9116,
        "CLP" => 901.5896,
        "CNY" => 7.2518,
        "COP" => 3875.0469,
        "CRC" => 516.0818,
        "CUP" => 24.0000,
        "CVE" => 101.4874,
        "CZK" => 22.7004,
        "DJF" => 177.7210,
        "DKK" => 6.8689,
        "DOP" => 58.8657,
        "DZD" => 134.5420,
        "EGP" => 47.5076,
        "ERN" => 15.0000,
        "ETB" => 57.4887,
        "EUR" => 0.9204,
        "FJD" => 2.2280,
        "FKP" => 0.7834,
        "FOK" => 6.8686,
        "GBP" => 0.7834,
        "GEL" => 2.7672,
        "GGP" => 0.7834,
        "GHS" => 14.9755,
        "GIP" => 0.7834,
        "GMD" => 64.9025,
        "GNF" => 8564.1991,
        "GTQ" => 7.7638,
        "GYD" => 209.3171,
        "HKD" => 7.8116,
        "HNL" => 24.6936,
        "HRK" => 6.9347,
        "HTG" => 132.6467,
        "HUF" => 353.6543,
        "IDR" => 16076.5570,
        "ILS" => 3.6805,
        "IMP" => 0.7834,
        "INR" => 83.1921,
        "IQD" => 1310.4722,
        "IRR" => 42046.8361,
        "ISK" => 137.2769,
        "JEP" => 0.7834,
        "JMD" => 155.7773,
        "JOD" => 0.7090,
        "JPY" => 157.0397,
        "KES" => 132.4370,
        "KGS" => 88.0088,
        "KHR" => 4088.5065,
        "KID" => 1.5025,
        "KMF" => 452.8047,
        "KRW" => 1361.0907,
        "KWD" => 0.3067,
        "KYD" => 0.8333,
        "KZT" => 441.7889,
        "LAK" => 21681.0068,
        "LBP" => 89500.0000,
        "LKR" => 301.4418,
        "LRD" => 193.4640,
        "LSL" => 18.2910,
        "LYD" => 4.8452,
        "MAD" => 9.9356,
        "MDL" => 17.7177,
        "MGA" => 4436.1800,
        "MKD" => 56.7356,
        "MMK" => 2098.9213,
        "MNT" => 3409.5422,
        "MOP" => 8.0459,
        "MRU" => 39.3973,
        "MUR" => 45.9588,
        "MVR" => 15.4346,
        "MWK" => 1742.9117,
        "MXN" => 16.7659,
        "MYR" => 4.6957,
        "MZN" => 63.7773,
        "NAD" => 18.2910,
        "NGN" => 1398.9613,
        "NIO" => 36.7879,
        "NOK" => 10.5038,
        "NPR" => 133.1151,
        "NZD" => 1.6267,
        "OMR" => 0.3845,
        "PAB" => 1.0000,
        "PEN" => 3.7470,
        "PGK" => 3.8402,
        "PHP" => 58.0110,
        "PKR" => 278.3515,
        "PLN" => 3.9161,
        "PYG" => 7500.9455,
        "QAR" => 3.6400,
        "RON" => 4.5739,
        "RSD" => 107.6592,
        "RUB" => 88.5706,
        "RWF" => 1310.7016,
        "SAR" => 3.7500,
        "SBD" => 8.5040,
        "SCR" => 13.5799,
        "SDG" => 449.2366,
        "SEK" => 10.5648,
        "SGD" => 1.3483,
        "SHP" => 0.7834,
        "SLE" => 22.6613,
        "SLL" => 22661.2592,
        "SOS" => 571.0528,
        "SRD" => 32.4862,
        "SSP" => 1797.7222,
        "STN" => 22.5497,
        "SYP" => 12882.4154,
        "SZL" => 18.2910,
        "THB" => 36.5876,
        "TJS" => 10.8526,
        "TMT" => 3.5000,
        "TND" => 3.1092,
        "TOP" => 2.3291,
        "TRY" => 32.2315,
        "TTD" => 6.7555,
        "TVD" => 1.5025,
        "TWD" => 32.1881,
        "TZS" => 2597.9969,
        "UAH" => 40.3938,
        "UGX" => 3800.2570,
        "UYU" => 38.4858,
        "UZS" => 12725.4406,
        "VES" => 36.5103,
        "VND" => 25457.1530,
        "VUV" => 118.7949,
        "WST" => 2.7302,
        "XAF" => 603.7396,
        "XCD" => 2.7000,
        "XDR" => 0.7539,
        "XOF" => 603.7396,
        "XPF" => 109.8326,
        "YER" => 250.0956,
        "ZAR" => 18.2912,
        "ZMW" => 27.0092,
        "ZWL" => 13.2789
    ]
        ]);
    }
}