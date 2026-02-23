import { Link, Head } from '@inertiajs/react';

export default function AuthenticatedLayout({ user, children }) {
    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex items-center">
                            <Link href="/" className="text-xl font-bold text-gray-800">
                                Todo List
                            </Link>
                        </div>
                        <div className="flex items-center">
                            <span className="text-gray-700 mr-4">{user.name}</span>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className="text-gray-500 hover:text-gray-700"
                            >
                                Déconnexion
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            <main className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {children}
                </div>
            </main>
        </div>
    );
}
