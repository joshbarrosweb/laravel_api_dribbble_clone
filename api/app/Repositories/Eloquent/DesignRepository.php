<?php

namespace App\Repositories\Eloquent;
use App\Models\Design;
use Illuminate\Http\Request;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\BaseRepository;

class DesignRepository extends BaseRepository implements IDesign
{
	public function model()
	{
		return Design::class;
	}

	public function applyTags($id, array $data)
	{
		$design = $this->find($id);
		$design->retag($data);
	}

	public function addComment($designId, array $data)
	{
		$design = $this->find($designId);

		$comment = $design->comments()->create($data);

		return $comment;
	}

	public function like($id)
	{
		$design = $this->model->findOrFail($id);
		if($design->isLikedByUser(auth()->id())){
			$design->unlike();
		} else {
			$design->like();
		}
	}

	public function isLikedByUser($id)
	{
		$design = $this->model->findOrFail($id);
		return $design->isLikedByUser(auth()->id());
	}

	public function search(Request $request)
	{
		$query = (new $this->model)->newQuery();

		$query->where('is_live', true);

		if($request->has_comments){
			$query->has('comments');
		}

		if($request->has_team){
			$query->has('team');
		}

		if($request->q){
			$query->where(function($q) use ($request){
				$q->where('title', 'like', '%'.$request->q.'%')
					->orWhere('description', 'like', '%'.$request->q.'%');
			});
		}

		if($request->orderBy=='likes'){
			$query->withCount('likes')
				->orderByDesc('likes_count');
		} else {
			$query->latest();
		}

		return $query->get();
	}
}