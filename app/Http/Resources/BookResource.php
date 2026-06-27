<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //共通
            'id' => $this->id,
            'title' => $this->title,
            'author'=>$this->author,
            'isbn'=>$this->isbn,
            'published_date' => $this->published_date,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'genres'=> $this->genres->pluck('name'),
            
            //indexでのみ取得
            'reviews_avg_rating'=>$this->when($request->routeIs('books.index'), $this->reviews_avg_rating),
            'reviews_count'=>$this->when($request->routeIs('books.index'), $this->reviews_count),
            
            //showでのみ取得
            'reviews' => $this->when($request->routeIs('books.show'), function () {
                return $this->reviews->map(function ($review) {
                    return [
                    'user_name' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => optional($review->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => optional($review->updated_at)->format('Y-m-d H:i:s'),
                ];
            });
            }),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
