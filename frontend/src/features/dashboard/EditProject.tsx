import { useMutation, useQuery } from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";
import { STATUS, type Project, type ProjectStatus } from "../../types/ProjectType";
import { useForm } from "react-hook-form";
import { useEffect } from "react";
import type { ResponseData } from "../../types/ResponseData";
import { zodResolver } from "@hookform/resolvers/zod";
import { projectSchema } from "../../types/ProjectSchema";
import { capitalize } from "../../lib/utils";

type ProjectForm = {
  name: string;
  description: string | null;
  status: ProjectStatus;
};

export default function EditProject(){
  const { id } = useParams();
  const navigate = useNavigate();
  const { register, handleSubmit, reset, formState: { errors } } = useForm<ProjectForm>({
    defaultValues: {
      name: '',
      description: '',
      status: STATUS.Draft,
    },
    resolver: zodResolver(projectSchema)
  });

  const fetchProject = async () => {
    const res = await fetch(`http://localhost:8000/api/projects/${id}`, {
      credentials: 'include',
      method: 'GET',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    });

    const json = await res.json();
    if (res.status !== 200) throw json;
    return json.data;
  }


  const { data: project, isLoading, isError } = useQuery<ProjectForm>({
      queryKey: ['project'],
      queryFn: fetchProject,
      retry: false
  });

  useEffect(()=>{
    reset({
      name: project?.name,
      description: project?.description,
      status: project?.status
    });
  },[project]);


  const editMutation = useMutation({
    mutationFn: async (project: ProjectForm) => {
      const res = await fetch(`http://localhost:8000/api/projects/${id}`, {
        method: 'PUT',
        credentials: 'include',
        body: JSON.stringify(project),
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      });
      const json = await res.json();
      if (res.status !== 200) throw json;
      return json;
    },
    onSuccess:(res: ResponseData)=>{
      alert(res.message);
      navigate('/dashboard');
    },
    onError: (error: ResponseData) => {
      alert(error.message ?? 'Something went wrong');
    }
  });

  const onSubmit = async (data: ProjectForm) => {
    editMutation.mutate(data);
  }


  useEffect(()=>{
    if(isError){
      alert("Something is wrong");
      navigate('/dashboard');
    }
  }, [isError])

  if(isLoading) return <p>Loading ...</p>

  if(!project) return (<>
  </>)

//Load project values
//fetch
  return (
    <>
      <h3 className="mt-5 font-bold text-left">Edit Project</h3>
      <div className="mt-4 p-5 border-black">
        <form className="max-w-lg mx-auto" onSubmit={handleSubmit(onSubmit)}>
          <div className="formGroup flex flex-col">
            <label className="text-left mb-1 font-semibold">Name</label>
            <input type="text" placeholder="Name" className="p-1 rounded-md text-sm border-black px-2"
              {
              ...register("name")
              } />
               { errors.name && (
                <div className="text-red-500 text-left text-sm mt-1">{errors.name.message}</div>
              )}
          </div>
          <div className="formGroup flex flex-col mt-4">
            <label className="text-left mb-1 font-semibold">Description</label>
            <textarea  placeholder="Description" className="p-1 rounded-md text-sm border-black px-2"
              {
              ...register("description")
              } />
          </div>
          <div className="formGroup flex flex-col mt-4">
            <label className="text-left mb-1 font-semibold">Status</label>
            <select className="border-black rounded-md text-sm p-1" {
                ...register("status")
               }>
                 <option value={STATUS.Draft}>{capitalize(STATUS.Draft)}</option>
                  <option value={STATUS.Active}>{capitalize(STATUS.Active)}</option>
                  <option value={STATUS.Archived}>{capitalize(STATUS.Archived)}</option>
            </select>
            {errors.status && (
              <div className="text-red-500 text-left text-sm mt-1">{errors.status.message}</div>
            )}
          </div>
          <button type="submit" className="px-2 mt-3 color-white border-black rounded-md text-sm cursor-point">Save</button>
        </form>
      </div>
    </>
  )
}