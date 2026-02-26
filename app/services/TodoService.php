<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TodoService
{
    /**
     * Récupère tous les todos d'un utilisateur
     */
    public function getUserTodos(int $userId): Collection
    {
        return Todo::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Crée un nouveau todo
     */
    public function createTodo(int $userId, array $data): Todo
    {
        return DB::transaction(function () use ($userId, $data) {
            return Todo::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'completed' => false,
            ]);
        });
    }

    /**
     * Met à jour un todo existant
     */
    public function updateTodo(int $todoId, int $userId, array $data): Todo
    {
        return DB::transaction(function () use ($todoId, $userId, $data) {
            $todo = $this->findTodoForUser($todoId, $userId);

            $todo->update([
                'title' => $data['title'] ?? $todo->title,
                'description' => $data['description'] ?? $todo->description,
                'completed' => $data['completed'] ?? $todo->completed,
            ]);

            return $todo->fresh();
        });
    }

    /**
     * Supprime un todo
     */
    public function deleteTodo(int $todoId, int $userId): bool
    {
        return DB::transaction(function () use ($todoId, $userId) {
            $todo = $this->findTodoForUser($todoId, $userId);
            return $todo->delete();
        });
    }

    /**
     * Marque un todo comme complété
     */
    public function markAsCompleted(int $todoId, int $userId): Todo
    {
        return $this->updateTodo($todoId, $userId, ['completed' => true]);
    }

    /**
     * Marque un todo comme non complété
     */
    public function markAsPending(int $todoId, int $userId): Todo
    {
        return $this->updateTodo($todoId, $userId, ['completed' => false]);
    }

    /**
     * Récupère les todos complétés d'un utilisateur
     */
    public function getCompletedTodos(int $userId): Collection
    {
        return Todo::where('user_id', $userId)
            ->where('completed', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupère les todos en attente d'un utilisateur
     */
    public function getPendingTodos(int $userId): Collection
    {
        return Todo::where('user_id', $userId)
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Compte le nombre de todos par statut
     */
    public function countTodosByStatus(int $userId): array
    {
        return [
            'total' => Todo::where('user_id', $userId)->count(),
            'completed' => Todo::where('user_id', $userId)->where('completed', true)->count(),
            'pending' => Todo::where('user_id', $userId)->where('completed', false)->count(),
        ];
    }

    /**
     * Trouve un todo pour un utilisateur spécifique
     */
    private function findTodoForUser(int $todoId, int $userId): Todo
    {
        $todo = Todo::where('id', $todoId)
            ->where('user_id', $userId)
            ->first();

        if (!$todo) {
            throw new NotFoundHttpException('Todo non trouvé ou non autorisé.');
        }

        return $todo;
    }
}
