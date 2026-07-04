<?php

namespace App\Http\Controllers;
use App\Enums\ReadingPlanStatus;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Book;
use App\Http\Requests\PlanRequest;

class PlanController extends Controller
{
    public function index(Request $request){

        $currentStatus = $request->status;

        $readingPlans =auth()->user()
        ->Plans()
        ->when($currentStatus, function ($query) use ($currentStatus) {
            $query->where('status', $currentStatus);
        })
        ->with('book')
        ->get();

        return view('reading-plans.index',compact('currentStatus','readingPlans'));
        
    }

    public function create(){
        $books = Book::all();
        return view('reading-plans.create',compact('books'));
    }

    public function store(PlanRequest $request,Plan $plan){
        $book = Plan::create([
            'user_id'=>auth()->id(),
            'book_id'=>$request->book_id,
            'target_date'=>$request->target_date, 
            'status'=> ReadingPlanStatus::Planing->value,
        ]);

        return redirect()
        ->route('reading-plans.index');
    }

    public function complete(Request $request,Plan $plan){
        $this->authorize('update', $plan);

        $plan->update([
            'status'=> ReadingPlanStatus::Completed->value,
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function edit(PlanRequest $request,Plan $plan){
        $this->authorize('update', $plan);
        return view('reading-plans.edit',compact('readingPlan'));
    }

    public function update(PlanRequest $request,Plan $plan){
        $this->authorize('update', $plan);
        $plan->update([
            'target_date'=>$request->target_date,
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function destroy(Plan $plan){
        $this->authorize('delete', $plan);
        $plan->delete();
        return redirect()->route('reading-plans.index');
    }
    
}
