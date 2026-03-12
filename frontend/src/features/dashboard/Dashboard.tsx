import { useState } from "react"
import { useQuery } from "@tanstack/react-query";
import type { Project } from "../../types/ProjectType";
import { LucideArchive, LucidePen, LucidePlay, LucideRotateCcw } from "lucide-react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../Context/AuthContext";
import { ROLES } from "../../lib/utils";

export default function Dashboard(){
  const navigate = useNavigate();
  const [toggleActions, setToggleActions] = useState(false);
  const [toggleId, setToggleId] = useState(-1);
  const {user} = useAuth();

  async function fetchProjects(): Promise<Project[]> {
    const res = await fetch('http://localhost:8000/api/projects', {
      credentials: 'include',
      method: 'GET',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });
    const json = await res.json();

    if (res.status !== 200) throw json;
    return json.data;
  }

  const archiveProject = async(id: number)=>{
    const res = await fetch(`http://localhost:8000/api/projects/${id}/archive`, {
      credentials: 'include',
      method: 'PATCH',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });
    const json = await res.json();
    if(res.status !== 200) throw json;

    alert('archived');
    setToggleActions(false);
    refetch();
  };

  const activateProject = async(id: number)=>{
    const res = await fetch(`http://localhost:8000/api/projects/${id}/activate`, {
      credentials: 'include',
      method: 'PATCH',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });
    const json = await res.json();

    if(res.status !== 200) throw json;
    alert('activated');
    setToggleActions(false);
    refetch();
  };

    const restoreProject = async(id: number)=>{
    const res = await fetch(`http://localhost:8000/api/projects/${id}/restore`, {
      credentials: 'include',
      method: 'PATCH',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });
    const json = await res.json();

    if(res.status !== 200) throw json;
    alert('activated');
    setToggleActions(false);
    refetch();
  };

  const isManager =  user?.role === ROLES.MANAGER;

  const { data: projects = [], isLoading, isError, refetch } = useQuery({
    queryKey: ['projects'],
    queryFn: fetchProjects,
    retry: false
  });

  const handleArchive = (id: number) => {
    //Call archive
    archiveProject(id);
  }

  const handleActivate = (id: number) => {
    activateProject(id);
  }

  const handleRestore = (id: number) => {
    restoreProject(id);
  }

  if(isLoading) return <p>Loading ...</p>

  if (isError) return <p className="mt-4">Something went wrong. Please try again.</p>;

  return (
    <>
    <div className="flex w-full mt-5 justify-between">
      <h3 className="font-bold text-left my-auto">Projects</h3>
      {isManager &&
      <button type="submit"
      onClick={()=>{navigate('/project/create')}}
      className="px-2 color-white border-black rounded-md text-sm cursor-point">New Project</button>
      }
    </div>
      <div className="wrapper mt-4">
        <div className="overflow-x-auto">
          <table className="w-full border-collapse border border-gray-300 text-sm">
            <thead className="bg-gray-100">
              <tr>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Name</th>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Description</th>
                <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Status</th>
                {
                  isManager &&
                  <th className="border border-gray-300 px-4 py-2 text-left font-semibold">Actions</th>
                }
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
                   { isManager &&
                    <td className="border border-gray-300 px-4 py-2 relative" >
                    <button className="cursor-pointer" onClick={()=>{setToggleActions(!toggleActions); setToggleId(project.id)}}>...</button>
                     { toggleId === project.id && toggleActions &&
                      <div className="w-4/5 flex flex-col border-black absolute bg-white top-8 right-0 z-10">
                        <button className="py-1 pl-2 flex hover:not-focus:bg-gray-200 cursor-pointer" onClick={()=>{navigate(`/project/edit/${project.id}`)}}>
                          <LucidePen className="my-auto mr-2 max-w-4 max-h-4 text-gray-600 "></LucidePen>
                          <span className="text-sm my-auto text-gray-600">Edit</span>
                        </button>
                          {(() => {
                            switch (project.status) {
                              case 'active':
                                return (
                                    <button className="py-1 pl-2 flex hover:not-focus:bg-gray-200 cursor-pointer" onClick={() => handleArchive(project.id)}>
                                      <LucideArchive className="my-auto mr-2 max-w-4 max-h-4 text-gray-600" />
                                      <span className="text-sm my-auto text-gray-600">Archive</span>
                                    </button>
                                );
                              case 'draft':
                                return (
                                    <button className="py-1 pl-2 flex hover:not-focus:bg-gray-200 cursor-pointer" onClick={() => handleActivate(project.id)}>
                                      <LucidePlay className="my-auto mr-2 max-w-4 max-h-4 text-gray-600" />
                                      <span className="text-sm my-auto text-gray-600">Activate</span>
                                    </button>
                                );
                              case 'archived':
                                return (
                                  <button className="py-1 pl-2 flex hover:not-focus:bg-gray-200 cursor-pointer" onClick={() => handleRestore(project.id)}>
                                    <LucideRotateCcw className="my-auto mr-2 max-w-4 max-h-4 text-gray-600" />
                                    <span className="text-sm my-auto text-gray-600">Restore</span>
                                  </button>
                                );
                              default:
                                return null;
                            }
                          })()}
                      </div>}
                  </td>
                      }
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