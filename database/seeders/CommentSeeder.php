<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Story;
use App\Models\User;
use App\Models\Writer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stories = Story::select('id')->pluck('id');
        $users = User::select('id')->pluck('id');
        $writers = User::select('id')->pluck('id');

        Comment::factory()
            ->count(random_int(25, 75))
            ->afterMaking(function (Comment $comment) use ($users, $stories): void {
                $comment->author_id = $users->random();
                $comment->author_type = User::class;
                $comment->commentable_id = $stories->random();
                $comment->commentable_type = Story::class;
            })
            ->create();

        Comment::factory()
            ->count(random_int(25, 75))
            ->afterMaking(function (Comment $comment) use ($writers, $stories): void {
                $comment->author_id = $writers->random();
                $comment->author_type = Writer::class;
                $comment->commentable_id = $stories->random();
                $comment->commentable_type = Story::class;
            })
            ->create();
    }
}
