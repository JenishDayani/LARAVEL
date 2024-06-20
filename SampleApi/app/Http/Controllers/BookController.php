<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;

class BookController extends Controller
{
    public function index()
    {
        $books = Books::all();
        return response()->json($books);
    }

    public function show($id)
    {
        $book = Books::find($id);
        if(!empty($book))
        {
            // return $book;
            return response()->json($book);
        }
        else{
            return response()->json([
                "message" => "Book not Found!!!"
            ],404);
        }
    }

    public function store(Request $r)
    {
        $book = new Books;
        $book->name = $r->input('bookName');
        $book->author = $r->input('authorName');
        $book->publish_date =$r->input('publishDate');
        $book->save();

        return response()->json([
            "message" => "Book Added."
        ],201);
    }


    public function update(Request $r,$id)
    {
        if(Books::where('id',$id)->exists())
        {
            $book = Books::find($id);
            // echo $book;
            // echo is_null($r->input('bookName'));
            // echo $r->input('bookName');
            // echo is_null($r->input('authorName'));
            // echo is_null($r->input('publishDate'));
            $book->name = is_null($r->input('bookName')) ? $book->name : $r->input('bookName');
            $book->author = is_null($r->input('authorName')) ? $book->author : $r->input('authorName');
            $book->publish_date = is_null($r->input('publishDate')) ? $book->publish_date : $r->input('publishDate');
            $book->save();
            // return response()->json([
            //     "message" => $r->input('bookName')
            // ]);
            // $book->name =  $r->input('bookName');
            // $book->author = $r->input('authorName');
            // $book->publish_date = $r->input('publishDate');
            // $book->save();
            // $book->name =  $r->name;
            // $book->author = $r->authorName;
            // $book->publish_date = $r->publishDate;
            // $book->save();

            return response()->json([
                "message" => "Book Updated Successfully."
            ]);
        }else{
            return response()->json([
                "message" => "Book not Found."
            ],404);
        }
    }

    public function destroy($id)
    {
        if(Books::where('id',$id)->exists())
        {
            $book = Books::find($id);
            $book->delete();

            return response()->json([
                "Book Deleted Successfully."
            ],202);
        }
        else{
            return response()->json([
                "message" => "Book not found"
            ],404);
        }
    }


    public function customview($bookName)
    {
        // $books = Books::where('name',$r->input('bookName'))->get();
        // return response()->json([
        //         "message" => "Book Found"
        //     ]);
        // return $r->input('bookName');
        if(Books::where('name',$bookName)->exists())
        {
            $books = Books::where('name',$bookName)->get();
            return response()->json($books);
        }
        else{
            return response()->json([
                "message" => "Book not Found"
            ],404);
        }
    }




    public function index2()
    {
        $books = Books::all();
        return response()->json($books);
    }

    public function show2($id)
    {
        $book = Books::find($id);
        if(!empty($book))
        {
            // return $book;
            return response()->json($book);
        }
        else{
            return response()->json([
                "message" => "Book not Found!!!"
            ],404);
        }
    }

    public function store2(Request $r)
    {
        // if($r->input('submitBook')){
            echo "Hello Successfull";
            $book = new Books;
            $book->name = $r->input('bookName');
            $book->author = $r->input('authorName');
            $book->publish_date =$r->input('publishDate');
            $book->save();
            
            return response()->json([
                "message" => "Book Added."
            ],201);
            // return redirect()->route('home');
        // }
        // return view('AddBook');
    }


    public function update2(Request $r,$id)
    {
        if(Books::where('id',$id)->exists())
        {
            $book = Books::find($id);
            $book->name = is_null($r->name) ? $book->name : $r->name;
            $book->author = is_null($r->author) ? $book->author : $r->author;
            $book->publish_date = is_null($r->publish_date) ? $book->publish_date : $r->publish_date;
            $book->save();

            return response()->json([
                "message" => "Book Updated Successfully."
            ],404);
        }else{
            return response()->json([
                "message" => "Book not Found."
            ],404);
        }
    }

    public function destroy2($id)
    {
        if(Books::where('id',$id)->exists())
        {
            $book = Books::find($id);
            $book->delete();

            return response()->json([
                "Book Deleted Successfully."
            ],202);
        }
        else{
            return response()->json([
                "message" => "Book not found"
            ],404);
        }
    }
}
