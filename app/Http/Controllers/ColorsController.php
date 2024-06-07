<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorStoreRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.colors.index')->with('colors', Color::query()->paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColorStoreRequest $request)
    {
        $data = $request->validated();

        if (Color::query()->create($data)) {
            request()->session()->flash('success','Цвет успешно создан');
        } else {
            request()->session()->flash('error','Ошибка. Пожалуйста, попробуйте снова');
        }

        return redirect()->route('colors.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('backend.colors.edit')->with('color', Color::query()->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColorStoreRequest $request, string $id)
    {
        $data = $request->validated();
        data_forget($data, '*.file');

        if (Color::query()->find($id)->update($data)) {
            request()->session()->flash('success', 'Цвет успешно обновлен');
        } else {
            request()->session()->flash('error', 'Ошибка, пожалуйста, попробуйте снова');
        }

        return redirect()->route('colors.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($colors = Color::query()->find($id)) {
            !empty($colors->delete())
                ? request()->session()->flash('success','Цвет успешно обновлен')
                : request()->session()->flash('error','Ошибка, пожалуйста, попробуйте снова');

            return redirect()->route('colors.index');
        }
        request()->session()->flash('error','Цвет не найден');

        return redirect()->back();
    }
}
