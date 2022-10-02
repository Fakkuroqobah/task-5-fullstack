<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    private $paginate = 10;
    private $substrStringFile = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Article::with(['user', 'category'])->latest()->paginate($this->paginate);
        
        return $this->res(200, 'Berhasil', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|max:2048',
            'user_id' => 'required',
            'category_id' => 'required'
        ]);

        try {
            $data = new Article();
            $data->title = $request->title;
            $data->content = $request->content;
            $data->user_id = $request->user_id;
            $data->category_id = $request->category_id;

            if($request->hasFile('image')) {
                $filenamewithextension = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $filenametostore = substr($filename, 0, $this->substrStringFile) .'.'. $extension;

                $request->image->move(storage_path('article'), $filenametostore);

                $data->image = 'article/' . $filenametostore;
            }

            $data->save();

            return $this->res(201, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Article::with(['user', 'category'])->findOrFail($id);
        
        return $this->res(200, 'Berhasil', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png|max:2048',
            'category_id' => 'required'
        ]);

        try {
            $data = Article::findOrFail($id);
            $data->title = $request->title;
            $data->content = $request->content;
            $data->category_id = $request->category_id;

            if($request->hasFile('image')) {
                $oldImage = $data->image;

                $filenamewithextension = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $filenametostore = substr($filename, 0, $this->substrStringFile) .'.'. $extension;

                $request->image->move(storage_path('article'), $filenametostore);

                $data->image = 'article/' . $filenametostore;

                File::delete('storage/' . $oldImage);
            }

            $data->save();

            return $this->res(200, 'Berhasil', $data);
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Article::findOrFail($id);
        $oldImage = $data->image;

        try {
            $data->delete();
            File::delete('storage/' . $oldImage);

            return $this->res(200, 'Berhasil', $data);
        } catch (\Illuminate\Database\QueryException $ex) {
            if($ex->getCode() === '23000') 
                return $this->errorFk();
        } catch (\Throwable $e) {
            return $this->res(500, 'Gagal', $e->getMessage());
        }
    }
}
