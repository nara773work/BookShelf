<?php

namespace App\Http\Controllers;
use App\Enums\ReadingPlanStatus;
use Illuminate\Http\Request;
use App\Models\ReadingPlan;
use App\Models\Book;
use App\Http\Requests\ReadingPlanRequest;

class ReadingPlanController extends Controller
{
    public function index(Request $request){

        $currentStatus = $request->status;

        $readingPlans =auth()->user()
        ->ReadingPlans()
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

    public function store(ReadingPlanRequest $request,ReadingPlan $plan){
        $book = ReadingPlan::create([
            'user_id'=>auth()->id(),
            'book_id'=>$request->book_id,
            'target_date'=>$request->target_date, 
            'status'=> ReadingPlanStatus::Reading->value,
        ]);

        return redirect()
        ->route('reading-plans.index')
        ->with('success', '読書計画を作成しました');
    }

    public function complete(ReadingRequest $request,ReadingPlan $plan){
        $this->authorize('update', $plan);

        $plan->update([
            'status'=> ReadingPlanStatus::Completed->value,
        ]);

        return redirect()->route('reading-plans.index')
        ->with('success', 'この書籍のステータスを読了に変更しました');
    }

    public function edit(ReadingPlanRequest $request,ReadingPlan $plan){
        $this->authorize('update', $plan);
        return view('reading-plans.edit',compact('readingPlan'));
    }

    public function update(ReadingPlanRequest $request,ReadingPlan $plan){
        $this->authorize('update', $plan);
        $plan->update([
            'target_date'=>$request->target_date,
        ]);

        return redirect()->route('reading-plans.index')
        ->with('success', '読書計画を更新しました');
    }

    public function destroy(ReadingPlan $plan){
        $this->authorize('delete', $plan);
        $plan->delete();
        return redirect()->route('reading-plans.index')
        ->with('error', '読書計画を削除しました');
    }
    
}
