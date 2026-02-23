import { useForm } from '@inertiajs/react'

export default function Index({ todos }) {
  const { data, setData, post, delete: destroy } = useForm({
    title: ''
  })

  function submit(e) {
    e.preventDefault()
    post('/todos')
  }

  return (
    <div className="max-w-xl mx-auto mt-10">
      <h1 className="text-2xl font-bold mb-4">Todo List</h1>

      <form onSubmit={submit} className="flex gap-2 mb-4">
        <input
          type="text"
          value={data.title}
          onChange={e => setData('title', e.target.value)}
          className="border p-2 flex-1"
        />
        <button className="bg-blue-500 text-white px-4 py-2">
          Add
        </button>
      </form>

      <ul>
        {todos.map(todo => (
          <li key={todo.id} className="flex justify-between border-b py-2">
            {todo.title}
            <button
              onClick={() => destroy(`/todos/${todo.id}`)}
              className="text-red-500"
            >
              Delete
            </button>
          </li>
        ))}
      </ul>
    </div>
  )
}
