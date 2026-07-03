<?php

namespace App\Http\Controllers;
use App\Enums\ReadingPlanStatus;
use Illuminate\Http\Request;
use App\Models\ReadingPlan;
use App\Models\Book;
class ReadingplanController extends Controller
{
    public function index(Request $request){

        $currentStatus = $request->status;

        $readingPlans =auth()->user()
        ->readingPlans()
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

    public function store(Request $request){
        $book = ReadingPlan::create([
            'user_id'=>auth()->id(),
            'book_id'=>$request->book_id,
            'target_date'=>$request->target_date, 
            'status'=> ReadingPlanStatus::Planing->value,
        ]);

        return redirect()
        ->route('reading-plans.index');
    }

    public function complete(Request $request,$id){

        $readingPlan = ReadingPlan::findOrFail($id);
        $readingPlan->update([
            'status'=> ReadingPlanStatus::Completed->value,
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function edit(Request $request,$id){
        $readingPlan = ReadingPlan::findOrFail($id);
        return view('reading-plans.edit',compact('readingPlan'));
    }

    public function update(Request $request,$id){

        $readingPlan = ReadingPlan::findOrFail($id);
        $readingPlan->update([
            'target_date'=>$request->target_date,
        ]);

        return redirect()->route('reading-plans.index');
    }

    public function destroy(ReadingPlan $plan){
    
        $plan->delete();
        return redirect()->route('reading-plans.index');
    }
    
}
