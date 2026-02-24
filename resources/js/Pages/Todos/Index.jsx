import { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ auth, todos, flash }) {
    const [editingId, setEditingId] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        title: '',
        description: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/todos', {
            onSuccess: () => reset(),
        });
    };

    const toggleComplete = (todo) => {
        router.put(`/todos/${todo.id}`, {
            completed: !todo.completed,
        });
    };

    const deleteTodo = (id) => {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
            router.delete(`/todos/${id}`);
        }
    };

    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title="Mes Todos" />

            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="p-6">
                    {/* Flash messages */}
                    {flash.success && (
                        <div className="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {flash.success}
                        </div>
                    )}

                    {/* Form for new todo */}
                    <form onSubmit={submit} className="mb-8">
                        <div className="grid gap-4">
                            <div>
                                <input
                                    type="text"
                                    value={data.title}
                                    onChange={e => setData('title', e.target.value)}
                                    placeholder="Titre de la tâche"
                                    className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                {errors.title && (
                                    <p className="mt-1 text-sm text-red-600">{errors.title}</p>
                                )}
                            </div>
                            <div>
                                <textarea
                                    value={data.description}
                                    onChange={e => setData('description', e.target.value)}
                                    placeholder="Description (optionnelle)"
                                    rows="3"
                                    className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                                >
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </form>

                    {/* List of todos */}
                    <div className="space-y-4">
                        {todos.map(todo => (
                            <div
                                key={todo.id}
                                className="flex items-center justify-between p-4 border rounded-lg hover:shadow-md transition"
                            >
                                <div className="flex items-center space-x-4 flex-1">
                                    <input
                                        type="checkbox"
                                        checked={todo.completed}
                                        onChange={() => toggleComplete(todo)}
                                        className="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    />
                                    <div className="flex-1">
                                        <h3 className={`text-lg font-medium ${todo.completed ? 'line-through text-gray-400' : 'text-gray-900'}`}>
                                            {todo.title}
                                        </h3>
                                        {todo.description && (
                                            <p className={`text-sm ${todo.completed ? 'text-gray-300' : 'text-gray-600'}`}>
                                                {todo.description}
                                            </p>
                                        )}
                                    </div>
                                </div>
                                <button
                                    onClick={() => deleteTodo(todo.id)}
                                    className="ml-4 px-3 py-1 text-sm text-red-600 hover:text-red-800"
                                >
                                    Supprimer
                                </button>
                            </div>
                        ))}

                        {todos.length === 0 && (
                            <p className="text-center text-gray-500 py-8">
                                Aucune tâche pour le moment. Créez-en une !
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
