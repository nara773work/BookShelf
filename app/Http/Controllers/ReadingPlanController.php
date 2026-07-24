<?php

namespace App\Http\Controllers;

use App\Enums\ReadingPlanStatus;
use App\Http\Requests\ReadingPlanRequest;
use App\Models\Book;
use App\Models\ReadingPlan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingPlanController extends Controller
{
    /**
     * 読書計画一覧を表示する。
     *
     * @return View
     */
    public function index(Request $request)
    {
        $currentStatus = $request->status;

        $readingPlans = auth()->user()
            ->readingPlans()
            ->when($currentStatus, function ($query) use ($currentStatus) {
                $query->where('status', $currentStatus);
            })
            ->with('book')
            ->get();

        return view('reading-plans.index', compact('currentStatus', 'readingPlans'));

    }

    /**
     * 読書計画登録画面を表示する。
     *
     * @return View
     */
    public function create()
    {
        $books = Book::all();

        return view('reading-plans.create', compact('books'));
    }

    /**
     * 読書計画を登録する。
     *
     * @param  ReadingPlanRequest  $request  登録する読書計画
     * @return RedirectResponse
     */
    public function store(ReadingPlanRequest $request)
    {
        $readingPlan = ReadingPlan::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'target_date' => $request->target_date,
            'status' => ReadingPlanStatus::Reading->value,
        ]);

        return redirect()
            ->route('reading-plans.index')
            ->with('success', '読書計画を作成しました');
    }

    /**
     * 読書中の読書計画を読了に変更する。
     *
     * @param  ReadingPlan  $plan  更新対象の読書計画
     * @return RedirectResponse
     */
    public function complete(ReadingPlan $plan)
    {
        $this->authorize('update', $plan);
        $plan->update([
            'status' => ReadingPlanStatus::Completed->value,
        ]);

        return redirect()->route('reading-plans.index')
            ->with('success', 'この書籍のステータスを読了に変更しました');
    }

    /**
     * 読書計画編集画面を表示する。
     *
     * @param  ReadingPlan  $plan  編集する読書計画
     * @return View
     */
    public function edit(ReadingPlan $plan)
    {

        $this->authorize('update', $plan);

        $readingPlan = $plan;

        return view('reading-plans.edit', compact('readingPlan'));
    }

    /**
     * 読書計画を更新する。
     *
     * @param  ReadingPlanRequest  $request  更新する読書計画
     * @param  ReadingPlan  $plan  更新する読書計画
     * @return RedirectResponse
     */
    public function update(ReadingPlanRequest $request, ReadingPlan $plan)
    {

        $this->authorize('update', $plan);
        $today = Carbon::today();

        $plan->update([

            'target_date' => $request->target_date,
        ]);

        if ($plans = ReadingPlan::whereDate('target_date', '<', $today)) {
            $plan->update([

                'status' => ReadingPlanStatus::Expired,
            ]);
        }

        return redirect()->route('reading-plans.index')
            ->with('success', '読書計画を更新しました');
    }

    /**
     * 読書計画を削除する。
     *
     * @param  ReadingPlan  $plan  削除する読書計画
     * @return RedirectResponse
     */
    public function destroy(ReadingPlan $plan)
    {
        $this->authorize('delete', $plan);
        $plan->delete();

        return redirect()->route('reading-plans.index')
            ->with('success', '読書計画を削除しました');
    }
}
