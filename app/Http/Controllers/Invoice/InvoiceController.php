<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Invoice;
use Illuminate\Http\Request;
use Session;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 0;

        if (!empty($keyword)) {
            $invoice = Invoice::where('fechanacimiento', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $invoice = Invoice::paginate($perPage);
        }

        return view('invoice.invoice.index', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $clients = DB::table('clients')->pluck('nombre','id');

        //return view('invoice.invoice.create', compact('clients'));

        $payments=DB::table('payments')->pluck('nombre','id');
        return view('invoice.invoice.create', compact('clients','payments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        Invoice::create($requestData);

        Session::flash('flash_message', 'Invoice added!');

        return redirect('invoice');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        return view('invoice.invoice.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $clients = DB::table('clients')->pluck('nombre','id');
        return view('invoice.invoice.edit', compact('invoice','clients'));
        $payments=DB::table('payments')->pluck('nombre','id');
        return view('invoice.invoice.edit',compact('invoice','payments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        
        $invoice = Invoice::findOrFail($id);
        $invoice->update($requestData);

        Session::flash('flash_message', 'Invoice updated!');

        return redirect('invoice');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Invoice::destroy($id);

        Session::flash('flash_message', 'Invoice deleted!');

        return redirect('invoice');
    }
}
