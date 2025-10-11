<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Story;
use App\Models\User;
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
        Comment::factory()
            ->count(rand(100, 150))
            ->afterMaking(function (Comment $comment) use ($users, $stories) {
                $comment->author_id = $users->random();
                $comment->author_type = User::class;
                $comment->commentable_id = $stories->random();
                $comment->commentable_type = Story::class;
            })
            ->create();
    }
}
