import { useMutation, useQuery } from "@tanstack/react-query";
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { useCookies } from "react-cookie";


export default function Login(){
   const navigate = useNavigate();
const [cookies, setCookie, removeCookie] = useCookies(['jwt']);

  useEffect(()=>{
    console.log("REMOVE");
    removeCookie('jwt');
  },[])

  const { register, watch, handleSubmit, formState: { errors } } = useForm({
    defaultValues: {
      email: "manager@example.com",
      password: ""
    }
  });

  const loginMutation = useMutation({
    mutationFn: async ({ email, password }: { email: string, password: string }) => {
      const res = await fetch('http://localhost:8000/api/login', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      if (res.status !== 200) throw new Error('Login failed');

      return res.json();
    },
    onSuccess: (data) => {
      console.log('Logged in:', data);
      navigate('/dashboard');
    },
    onError: (error) => {
      console.error('Login error:', error.message);
      //Error alert
    },
  });

  // const testMutation = useMutation({
  //   mutationFn: async()=>{
  //     const res = await fetch('http://localhost:8000/api/projects/8/activate',{
  //       credentials: 'include',
  //       method: 'PATCH',
  //       headers: { 'Content-Type': 'application/json', 'Accept':'application/json' }
  //     });
  //     debugger;
  //     if(!res.ok) throw new Error('Login failed');
  //     return res.json();
  //   },
  //   onSuccess: (data) => {
  //     console.log('Token sent', data);
  //   },
  // })


  async function onSubmit(data: any) {
    try {
      const {email, password} = data;
      console.log("DATA ",email,password);
      loginMutation.mutate({ email, password });
      //validta
    } catch (error) {
      console.log("ERROR ",error);
    }
  }

  return <>
      <div className="w-full flex border-black p-3 mt-10">
        <div className="w-full">
          <h4 className="mb-2">Login</h4>
          <form onSubmit={handleSubmit(onSubmit)}>
            <div className="formGroup">
              <input type="text" placeholder="Email" value="manager@example.com" className="rounded-md text-sm border-black px-2"
                {
                ...register("email", { required: true })
                } />
              {errors.email && (
                <div className="text-red-500 text-sm mt-1">{errors.email.message}</div>
              )}
            </div>
            <div className="formGroup mt-2">
              <input type="text" placeholder="Password" value="Password#123" className="rounded-md text-sm border-black px-2"
                {
                ...register("password", { required: true })
                } />
              {errors.email && (
                <div className="text-red-500 text-sm mt-1">{errors.email.message}</div>
              )}
            </div>
            <button type="submit" className="px-2 mt-3 color-white border-black rounded-md text-sm">Submit</button>
          </form>
        </div>
      </div>
  </>
}