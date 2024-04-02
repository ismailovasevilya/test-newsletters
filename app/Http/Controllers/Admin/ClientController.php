<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Import\ClientImport;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::paginate($request->per_page);

        return Inertia::render('Clients/Index', [
            'clients' => $clients
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Edit');
    }

    public function store(Request $request)
    {
        $birthday = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $request->birth_date)->format('Y-m-d');
        $request->merge([
            'birth_date' => $birthday
        ]);
        $client = Client::create($request->all());

        return redirect(route('clients.edit', $client));
    }

    public function edit(Client $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client,
        ]);
    }

    public function update(Client $client, Request $request)
    {
        $birthday = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $request->birth_date)->format('Y-m-d');
        $request->merge([
            'birth_date' => $birthday
        ]);
        $client->update($request->all());

        return redirect(route('clients.edit', $client));
    }

    public function import(Request $request)
    {
        Excel::import(new ClientImport(), $request->file);

        return redirect()->route('clients.index')->with('success', 'Clients Imported Successfully');
    }
}
