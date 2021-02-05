<?php
/**
 * Review class
 */
class Review
{
	private string $user_name, $comment, $date;
	private int $rating;

	public function getUserName() : string
	{
		return $this->user_name;
	}

	public function getComment() : string
	{
		return $this->comment;
	}

	public function getDate() : string
	{
		return $this->date;
	}

	public function getRating() : int
	{
		return $this->rating;
	}

	public static function getPostReviewFields() : array
	{
		return [
			'user_name' => [
				'title' => 'Ім`я',
				'required' => true
			],
			'comment' => [
				'title' => 'Коментар',
				'required' => true
			],
			'rating' => [
				'title' => 'Оцінка',
				'required' => true
			]
		];
	}

}