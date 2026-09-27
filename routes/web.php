<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/form-mahasiswa', function () {
    return view('form-mahasiswa');
})->name('form.mahasiswa');

Route::post('/form-mahasiswa', function (Request $request) {

    $request->merge([
        'nama' => strip_tags(trim($request->nama ?? '')),
        'email' => trim($request->email ?? ''),
        'usia' => trim($request->usia ?? ''),
        'nim' => trim($request->nim ?? ''),
    ]);

    $validated = $request->validate([
        'nama' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:100'],
        'usia' => ['required', 'integer', 'min:1', 'max:100'],
        'nim' => ['required', 'regex:/^[0-9]{8,12}$/'],
    ], [
        'nama.required' => 'Nama wajib diisi.',
        'nama.max' => 'Nama maksimal 100 karakter.',

        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.max' => 'Email maksimal 100 karakter.',

        'usia.required' => 'Usia wajib diisi.',
        'usia.integer' => 'Usia harus berupa angka.',
        'usia.min' => 'Usia minimal 1 tahun.',
        'usia.max' => 'Usia maksimal 100 tahun.',

        'nim.required' => 'NIM wajib diisi.',
        'nim.regex' => 'NIM harus terdiri dari 8–12 digit angka.',
    ]);

    return view('hasil-form', [
        'data' => $validated
    ]);
})->name('form.proses');