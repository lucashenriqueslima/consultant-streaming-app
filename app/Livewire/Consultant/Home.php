<?php

namespace App\Livewire\Consultant;

use App\Enums\Panel;
use App\Models\Course;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;

class Home extends Component
{
    public Collection $courses;

    //mount
    public function mount()
    {
        $this->courses = Course::with([
            'lessons' => function ($query) {
                $query->orderBy('order')
                    ->with(['userProgress' => function ($query) {
                        $query->where('user_id', Auth::id());
                    }]);
            }
        ])
            ->where('panel', Panel::Consultant)
            ->get();
    }

    #[On('lessonStarted')]
    public function createUserProgressIfDontExist(string $lessonId): void
    {
        UserProgress::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $lessonId,
            ],
            [
                'started_at' => now(),
                'is_completed' => false,
            ]
        );
    }

    #[On('lessonCompleted')]
    public function updateUserProgress(string $lessonId): void
    {
        UserProgress::where('user_id', Auth::id())
            ->where('lesson_id', $lessonId)
            ->update([
                'completed_at' => now(),
                'is_completed' => true,
            ]);
    }

    public function getStorageUrl(string $path): string
    {
        return Storage::url($path);
    }

    public function getUserProgressStatusLabel(?bool $isCompleted): string
    {
        return match ($isCompleted) {
            true => 'Completo',
            false => 'Em Progresso',
            default => 'Não Iniciado',
        };
    }

    public function getUserProgressStatusIcon(?bool $isCompleted): string
    {
        return match ($isCompleted) {
            true => 'heroicon-o-check',
            false => 'heroicon-o-arrow-path',
            default => 'heroicon-o-x-circle',
        };
    }

    public function render()
    {
        return view(
            'livewire.consultant.home',
            [
                'courses' => $this->courses,
            ]
        );
    }
}
