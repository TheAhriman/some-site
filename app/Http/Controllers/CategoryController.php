<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('backend.category.index')->with('categories', Category::getAllCategory());
    }

    /**
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function create()
    {
        return view('backend.category.create')->with(
            'parent_cats',
            Category::where('is_parent',1)->orderBy('title','ASC')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'photo'=>'string|nullable',
            'status'=>'required|in:active,inactive',
            'is_parent'=>'sometimes|in:1',
            'parent_id'=>'nullable|exists:categories,id',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Category::where('slug',$slug)->count();

        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0,999);
        }

        $data['slug'] = $slug;
        $data['is_parent'] = $request->input('is_parent',0);

        if (Category::create($data)) {
            request()->session()->flash('success','Категория успешно добавлена');
        } else {
            request()->session()->flash('error','Произошла ошибка. Пожалуйста, попробуйте снова!');
        }

        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function edit($id)
    {
        $parent_cats = Category::where('is_parent', 1)->get();
        $category = Category::findOrFail($id);

        return view('backend.category.edit')->with('category', $category)->with('parent_cats', $parent_cats);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|nullable',
            'photo'=>'string|nullable',
            'status'=>'required|in:active,inactive',
            'is_parent'=>'sometimes|in:1',
            'parent_id'=>'nullable|exists:categories,id',
        ]);
        $data = $request->all();
        $data['is_parent'] = $request->input('is_parent', 0);
        $status = $category->fill($data)->save();
        if ($status) {
            request()->session()->flash('success','Категория успешно обновлена');
        } else {
            request()->session()->flash('error','Произошла ошибка. Пожалуйста, попробуйте снова!');
        }

        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $child_cat_id = Category::where('parent_id',$id)->pluck('id');

        if ($category->delete()) {
            if (count($child_cat_id) > 0) {
                Category::shiftChild($child_cat_id);
            }
            request()->session()->flash('success','Категория успешно удалена');
        }
        else {
            request()->session()->flash('error','Ошибка при удалении категории');
        }

        return redirect()->route('category.index');
    }

    public function getChildByParent(Request $request)
    {
        $child_cat = Category::getChildByParentID($request->id);

        return count($child_cat) <= 0
            ? response()->json(['status' => false,'msg' => '', 'data' => null])
            : response()->json(['status' => true,'msg' => '', 'data' => $child_cat]);
    }
}
