import { useState } from "react"
import { useQuery } from "@tanstack/react-query";
import type { Project } from "../../types/ProjectType";

export default function Dashboard(){


  async function fetchProjects(): Promise<Project[]> {
    const res = await fetch('http://localhost:8000/api/projects', {
      credentials: 'include',
      method: 'GET',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });

    if (res.status !== 200) throw new Error('Failed to fetch projects');

    const json = await res.json();
    return json.data;
  }

  const { data: projects = [], isLoading, isError } = useQuery({
    queryKey: ['projects'],
    queryFn: fetchProjects,
    retry: false
  });

  if (isError) return <p className="mt-4">Something went wrong. Please try again.</p>;

  return (
    <>
      <h3 className="mt-5 font-bold text-left">Projects</h3>
      <div className="wrapper mt-4">
        <div className="overflow-x-auto">
          <table className="w-full border-collapse border border-gray-300 text-sm">
            <thead className="bg-gray-100">
              <tr>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Name</th>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Description</th>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Status</th>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody>
              {projects.length === 0 ? (
                <tr>
                  <td colSpan={4} className="border border-gray-300 px-4 py-6 text-center text-gray-400">
                    No projects found.
                  </td>
                </tr>
              ) : (projects.map((project) => (
                <tr key={project.id} className="hover:bg-gray-50">
                  <td className="border border-gray-300 px-4 py-2">{project.name}</td>
                  <td className="border border-gray-300 px-4 py-2">{project.description}</td>
                  <td className="border border-gray-300 px-4 py-2">{project.status}</td>
                  <td className="border border-gray-300 px-4 py-2">
                    ...
                  </td>
                </tr>
              ))
              )
              }
            </tbody>
          </table>
        </div>
      </div>
    </>
  )
}