<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Transaction::with('item');
        if ($request->has('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('ItemName', 'LIKE', "%{$request->search}%");
            });
        }
        if ($request->has('sort_by')) {
            $query->orderBy($request->sort_by, $request->sort_dir ?? 'asc');
        }
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('TransactionDate', [$request->start_date, $request->end_date]);
        }
        if ($request->has('comparison') && $request->has('amount')) {
            $comparison = $request->comparison;
            $amount = $request->amount;
            if ($comparison === 'more') {
                $query->where('Sold', '>', $amount);
            } elseif ($comparison === 'less') {
                $query->where('Sold', '<', $amount);
            }
        }
        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Transaction::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $transaction->load('item');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $transaction->update($request->all());
        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response(null, 204);
    }
}
